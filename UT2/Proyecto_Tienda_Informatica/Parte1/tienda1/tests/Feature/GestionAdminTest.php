<?php

namespace Tests\Feature;

use App\Models\Aviso;
use App\Models\Imagen;
use App\Models\Producto;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class GestionAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed();
    }

    private function imagen(): Imagen
    {
        return Imagen::create([
            'nombre' => 'Foto de prueba', 'autor' => 'Ana',
            'ruta_imagen' => UploadedFile::fake()->image('original.jpg')->store('escaparate', 'public'),
        ]);
    }

    private function documento(string $html): DOMXPath
    {
        $documento = new DOMDocument();
        // DOMDocument usa un parser HTML4 y puede advertir sobre etiquetas HTML5.
        @$documento->loadHTML($html);

        return new DOMXPath($documento);
    }

    public function test_listados_son_tablas_y_cada_fila_permite_editar_y_eliminar(): void
    {
        $this->imagen();

        foreach (['productos', 'avisos', 'imagenes'] as $modulo) {
            $respuesta = $this->get('/admin/'.$modulo)->assertOk()->assertDontSee('class="card', false);
            $dom = $this->documento($respuesta->getContent());
            $filas = $dom->query('//table/tbody/tr');
            $registros = $respuesta->viewData($modulo);
            $this->assertCount(count($registros), $filas);

            foreach ($registros as $indice => $registro) {
                $fila = $filas->item($indice);
                $editar = route('admin.'.$modulo.'.edit', $registro);
                $eliminar = route('admin.'.$modulo.'.destroy', $registro);
                $this->assertSame(1, $dom->query('.//a[@href="'.$editar.'"]', $fila)->length);
                $this->assertSame(1, $dom->query('.//form[@action="'.$eliminar.'"]//input[@name="_method" and @value="DELETE"]', $fila)->length);
                $this->assertSame(1, $dom->query('.//form[@action="'.$eliminar.'"]//input[@name="_token"]', $fila)->length);
            }
        }
    }

    public function test_todas_las_pantallas_tienen_volver_con_destino_coherente(): void
    {
        $destinoPanel = Route::has('inicio') ? route('inicio') : route('admin.productos.index');
        $paginas = ['/admin' => $destinoPanel];
        $registros = ['productos' => Producto::firstOrFail(), 'avisos' => Aviso::firstOrFail(), 'imagenes' => $this->imagen()];

        foreach ($registros as $modulo => $registro) {
            $listado = '/admin/'.$modulo;
            $paginas[$listado] = route('admin.index');
            $paginas[route('admin.'.$modulo.'.create')] = route('admin.'.$modulo.'.index');
            $paginas[$listado.'/'.$registro->id] = route('admin.'.$modulo.'.index');
            $paginas[$listado.'/'.$registro->id.'/edit'] = route('admin.'.$modulo.'.index');
        }

        foreach ($paginas as $pagina => $destino) {
            $respuesta = $this->get($pagina)->assertOk()->assertSee('← Volver')->assertDontSee('class="card', false);
            $dom = $this->documento($respuesta->getContent());
            $botones = $dom->query('//a[contains(concat(" ", normalize-space(@class), " "), " back-button ")]');
            $this->assertCount(1, $botones);
            $this->assertSame($destino, $botones->item(0)->getAttribute('href'));
        }
    }

    public function test_crud_completo_de_avisos(): void
    {
        $this->post('/admin/avisos', ['titulo' => 'Aviso de prueba', 'contenido' => 'Primer contenido.'])
            ->assertRedirect('/admin/avisos')->assertSessionMissing('ok');
        $aviso = Aviso::where('titulo', 'Aviso de prueba')->firstOrFail();
        $url = '/admin/avisos/'.$aviso->id;
        $this->get($url)->assertOk()->assertSee('Primer contenido.');
        $this->get($url.'/edit')->assertOk()->assertSee('value="Aviso de prueba"', false);
        $this->put($url, ['titulo' => 'Aviso actualizado', 'contenido' => 'Segundo contenido.'])
            ->assertRedirect('/admin/avisos')->assertSessionMissing('ok');
        $this->assertDatabaseHas('avisos', ['id' => $aviso->id, 'titulo' => 'Aviso actualizado', 'contenido' => 'Segundo contenido.']);
        $this->get('/admin/avisos')->assertSee('Aviso actualizado')->assertDontSee('Primer contenido.');
        $this->delete($url)->assertRedirect('/admin/avisos');
        $this->assertDatabaseMissing('avisos', ['id' => $aviso->id]);
    }

    public function test_edicion_invalida_de_aviso_conserva_los_datos(): void
    {
        $aviso = Aviso::firstOrFail();
        $this->from('/admin/avisos/'.$aviso->id.'/edit')
            ->put('/admin/avisos/'.$aviso->id, ['titulo' => str_repeat('a', 121), 'contenido' => ''])
            ->assertRedirect('/admin/avisos/'.$aviso->id.'/edit')
            ->assertSessionHasErrors(['titulo', 'contenido']);
        $this->assertSame($aviso->titulo, $aviso->fresh()->titulo);
        $this->assertSame($aviso->contenido, $aviso->fresh()->contenido);
    }

    public function test_editar_nombre_y_autor_sin_archivo_conserva_la_foto(): void
    {
        $imagen = $this->imagen();
        $this->put('/admin/imagenes/'.$imagen->id, ['nombre' => 'Foto actualizada', 'autor' => 'Luis'])
            ->assertRedirect('/admin/imagenes')->assertSessionMissing('ok');
        $this->assertSame('Foto actualizada', $imagen->fresh()->nombre);
        $this->assertSame('Luis', $imagen->fresh()->autor);
        $this->assertSame($imagen->ruta_imagen, $imagen->fresh()->ruta_imagen);
        Storage::disk('public')->assertExists($imagen->ruta_imagen);
        $this->assertCount(1, Storage::disk('public')->allFiles());
    }

    public function test_edicion_multipart_sustituye_la_foto_y_el_borrado_la_retira(): void
    {
        $imagen = $this->imagen();
        $url = '/admin/imagenes/'.$imagen->id;
        $this->post($url, [
            '_method' => 'PUT', 'nombre' => 'Foto nueva', 'autor' => 'Luis',
            'imagen' => UploadedFile::fake()->image('nueva.png'),
        ])->assertRedirect('/admin/imagenes');
        $nuevaRuta = $imagen->fresh()->ruta_imagen;
        $this->assertNotSame($imagen->ruta_imagen, $nuevaRuta);
        Storage::disk('public')->assertMissing($imagen->ruta_imagen);
        Storage::disk('public')->assertExists($nuevaRuta);
        $this->get($url)->assertOk()->assertSee('Foto nueva')->assertSee(Storage::disk('public')->url($nuevaRuta), false);
        $this->delete($url)->assertRedirect('/admin/imagenes');
        $this->assertDatabaseMissing('imagenes', ['id' => $imagen->id]);
        Storage::disk('public')->assertMissing($nuevaRuta);
    }

    public function test_fotografia_invalida_no_cambia_el_registro_ni_los_archivos(): void
    {
        $imagen = $this->imagen();
        foreach ([UploadedFile::fake()->create('documento.pdf', 20), UploadedFile::fake()->image('grande.jpg')->size(2049)] as $archivo) {
            $this->put('/admin/imagenes/'.$imagen->id, ['nombre' => 'Rechazada', 'autor' => 'Luis', 'imagen' => $archivo])
                ->assertSessionHasErrors('imagen');
        }
        $this->assertSame($imagen->nombre, $imagen->fresh()->nombre);
        $this->assertSame($imagen->ruta_imagen, $imagen->fresh()->ruta_imagen);
        Storage::disk('public')->assertExists($imagen->ruta_imagen);
        $this->assertCount(1, Storage::disk('public')->allFiles());
    }

    public function test_fallo_al_editar_imagen_conserva_el_archivo_anterior(): void
    {
        $imagen = $this->imagen();
        $this->withoutExceptionHandling();
        Imagen::updating(function (): void {
            throw new RuntimeException('Fallo de escritura simulado.');
        });
        try {
            $this->put('/admin/imagenes/'.$imagen->id, [
                'nombre' => 'Rechazada', 'autor' => 'Luis', 'imagen' => UploadedFile::fake()->image('nueva.jpg'),
            ]);
            $this->fail('Se esperaba un fallo de escritura.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Fallo de escritura simulado.', $exception->getMessage());
        }
        $this->assertSame($imagen->nombre, $imagen->fresh()->nombre);
        $this->assertSame($imagen->ruta_imagen, $imagen->fresh()->ruta_imagen);
        Storage::disk('public')->assertExists($imagen->ruta_imagen);
        $this->assertCount(1, Storage::disk('public')->allFiles());
    }

    public function test_fallo_al_crear_imagen_no_deja_un_archivo_huerfano(): void
    {
        $this->withoutExceptionHandling();
        Imagen::creating(function (): void {
            throw new RuntimeException('Fallo de alta simulado.');
        });
        try {
            $this->post('/admin/imagenes', [
                'nombre' => 'Rechazada', 'autor' => 'Luis', 'imagen' => UploadedFile::fake()->image('nueva.jpg'),
            ]);
            $this->fail('Se esperaba un fallo de alta.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Fallo de alta simulado.', $exception->getMessage());
        }
        $this->assertDatabaseCount('imagenes', 0);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_registros_inexistentes_devuelven_404(): void
    {
        foreach (['avisos', 'imagenes'] as $modulo) {
            $url = '/admin/'.$modulo.'/999999';
            $this->get($url)->assertNotFound();
            $this->get($url.'/edit')->assertNotFound();
            $this->put($url, [])->assertNotFound();
            $this->delete($url)->assertNotFound();
        }
    }

    public function test_fotos_se_cargan_desde_el_servidor_actual_sin_fijar_un_puerto(): void
    {
        $imagen = $this->imagen();
        $producto = Producto::firstOrFail();
        $ruta = UploadedFile::fake()->image('producto.jpg')->store('productos', 'public');
        $producto->update(['ruta_imagen' => $ruta]);

        foreach (['http://127.0.0.1:8000', 'http://localhost:8085'] as $servidor) {
            $this->get($servidor.'/admin/productos/'.$producto->id)->assertOk()
                ->assertSee('src="/storage/'.$ruta.'"', false);
            $this->get($servidor.'/admin/imagenes')->assertOk()
                ->assertSee('src="/storage/'.$imagen->ruta_imagen.'"', false);
        }
    }
}

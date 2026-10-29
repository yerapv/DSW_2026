<?php

namespace Tests\Feature\Productos;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class ProductoImagenTest extends TestCase
{
    use RefreshDatabase;

    /** Campos comunes de un producto válido, sin el archivo de la fotografía. */
    private function datos(): array
    {
        $categoria = Categoria::firstOrCreate(['nombre' => 'Periféricos']);

        return [
            'nombre' => 'Teclado de prueba', 'marca' => 'Nexo', 'anio' => 2026,
            'precio' => '24,90', 'categoria_id' => $categoria->id,
            'tipo' => 2, 'disponible' => 1,
        ];
    }

    /** Prepara un registro con una foto que permitirá comprobar la edición. */
    private function productoConImagen(): Producto
    {
        $datos = $this->datos();
        unset($datos['precio']);
        $datos['precio_centimos'] = 2490;
        $datos['ruta_imagen'] = UploadedFile::fake()->image('anterior.jpg')->store('productos', 'public');

        return Producto::create($datos);
    }

    /** El alta guarda una ruta del servidor y la foto aparece en ambas áreas. */
    public function test_crear_producto_con_fotografia(): void
    {
        Storage::fake('public');
        $datos = $this->datos();
        $datos['imagen'] = UploadedFile::fake()->image('teclado.jpg');
        // Un campo extra enviado por el cliente no decide dónde se guarda la foto.
        $datos['ruta_imagen'] = 'ruta-inventada.jpg';
        $this->post('/admin/productos', $datos)->assertRedirect('/admin/productos');

        $producto = Producto::firstOrFail();
        $this->assertStringStartsWith('productos/', $producto->ruta_imagen);
        Storage::disk('public')->assertExists($producto->ruta_imagen);
        $url = Storage::disk('public')->url($producto->ruta_imagen);
        foreach (['/productos', '/productos/'.$producto->id, '/admin/productos',
            '/admin/productos/'.$producto->id, '/admin/productos/'.$producto->id.'/edit'] as $pagina) {
            $this->get($pagina)->assertOk()->assertSee($url, false);
        }
    }

    /** La fotografía es obligatoria en las nuevas altas del panel. */
    public function test_alta_sin_fotografia_no_crea_producto(): void
    {
        Storage::fake('public');
        $this->post('/admin/productos', $this->datos())->assertSessionHasErrors('imagen');
        $this->assertDatabaseCount('productos', 0);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    /** Editar texto sin adjuntar otro archivo conserva la fotografía anterior. */
    public function test_editar_sin_nueva_fotografia_conserva_la_actual(): void
    {
        Storage::fake('public');
        $producto = $this->productoConImagen();
        $datos = $this->datos();
        $datos['nombre'] = 'Teclado actualizado';
        $this->put('/admin/productos/'.$producto->id, $datos)->assertRedirect('/admin/productos');
        $this->assertSame($producto->ruta_imagen, $producto->fresh()->ruta_imagen);
        $this->assertSame('Teclado actualizado', $producto->fresh()->nombre);
        Storage::disk('public')->assertExists($producto->ruta_imagen);
        $this->assertCount(1, Storage::disk('public')->allFiles('productos'));
    }

    /** Un POST multipart con _method=PUT sustituye la foto y retira la anterior. */
    public function test_sustituir_fotografia_desde_edicion(): void
    {
        Storage::fake('public');
        $producto = $this->productoConImagen();
        $datos = $this->datos();
        $datos['_method'] = 'PUT';
        $datos['imagen'] = UploadedFile::fake()->image('nueva.png');
        $this->post('/admin/productos/'.$producto->id, $datos)->assertRedirect('/admin/productos');
        $nuevaRuta = $producto->fresh()->ruta_imagen;
        $this->assertNotSame($producto->ruta_imagen, $nuevaRuta);
        Storage::disk('public')->assertMissing($producto->ruta_imagen);
        Storage::disk('public')->assertExists($nuevaRuta);
    }

    /** El servidor rechaza archivos de otro tipo o imágenes que excedan 2 MB. */
    public function test_rechaza_archivo_invalido_y_foto_demasiado_grande(): void
    {
        Storage::fake('public');
        foreach ([UploadedFile::fake()->create('documento.pdf', 20),
            UploadedFile::fake()->image('grande.jpg')->size(2049)] as $archivo) {
            $datos = $this->datos();
            $datos['imagen'] = $archivo;
            $this->post('/admin/productos', $datos)->assertSessionHasErrors('imagen');
        }
        $this->assertDatabaseCount('productos', 0);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    /** Una sustitución que no supera la validación deja intacta la foto actual. */
    public function test_edicion_invalida_conserva_foto_y_datos(): void
    {
        Storage::fake('public');
        $producto = $this->productoConImagen();
        $datos = $this->datos();
        $datos['nombre'] = 'Nombre rechazado';
        $datos['imagen'] = UploadedFile::fake()->create('documento.pdf', 20);
        $this->put('/admin/productos/'.$producto->id, $datos)->assertSessionHasErrors('imagen');
        $this->assertSame($producto->nombre, $producto->fresh()->nombre);
        $this->assertSame($producto->ruta_imagen, $producto->fresh()->ruta_imagen);
        Storage::disk('public')->assertExists($producto->ruta_imagen);
    }

    /** El evento deleted del modelo también elimina la fotografía del producto. */
    public function test_borrar_producto_borra_su_fotografia(): void
    {
        Storage::fake('public');
        $producto = $this->productoConImagen();
        $this->delete('/admin/productos/'.$producto->id)->assertRedirect('/admin/productos');
        $this->assertDatabaseCount('productos', 0);
        Storage::disk('public')->assertMissing($producto->ruta_imagen);
    }

    /** Si falla la escritura en BD, no queda una fotografía nueva sin registro. */
    public function test_fallo_al_crear_retira_el_archivo_subido(): void
    {
        Storage::fake('public');
        $this->withoutExceptionHandling();
        Producto::creating(function (): void {
            throw new RuntimeException('Fallo simulado al crear.');
        });
        $datos = $this->datos();
        $datos['imagen'] = UploadedFile::fake()->image('teclado.jpg');
        try {
            $this->post('/admin/productos', $datos);
            $this->fail('Se esperaba el fallo de escritura en BD.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Fallo simulado al crear.', $exception->getMessage());
        }
        $this->assertDatabaseCount('productos', 0);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    /** Una edición fallida conserva la imagen anterior y elimina solo la nueva. */
    public function test_fallo_al_editar_conserva_la_fotografia_anterior(): void
    {
        Storage::fake('public');
        $producto = $this->productoConImagen();
        $this->withoutExceptionHandling();
        Producto::updating(function (): void {
            throw new RuntimeException('Fallo simulado al editar.');
        });
        $datos = $this->datos();
        $datos['imagen'] = UploadedFile::fake()->image('nueva.jpg');
        try {
            $this->put('/admin/productos/'.$producto->id, $datos);
            $this->fail('Se esperaba el fallo de escritura en BD.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Fallo simulado al editar.', $exception->getMessage());
        }
        $this->assertSame($producto->ruta_imagen, $producto->fresh()->ruta_imagen);
        Storage::disk('public')->assertExists($producto->ruta_imagen);
        $this->assertCount(1, Storage::disk('public')->allFiles('productos'));
    }
}

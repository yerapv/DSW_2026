<?php

namespace Tests\Feature;

use App\Models\Genero;
use App\Models\Videojuego;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VideojuegoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function datos(array $cambios = []): array
    {
        $genero = Genero::firstOrCreate(['nombre' => 'Aventura']);

        return array_replace([
            'titulo' => 'La isla del tesoro',
            'precio' => '29.95',
            'genero_id' => $genero->id,
            'imagen' => UploadedFile::fake()->image('portada.png'),
        ], $cambios);
    }

    public function test_crud_y_ciclo_de_vida_de_la_portada(): void
    {
        $this->get('/')->assertRedirect('/videojuegos');
        $this->get('/videojuegos')->assertOk()->assertSee('Todavía no hay videojuegos.');
        $datos = $this->datos();
        $this->get('/videojuegos/create')->assertOk()->assertSee('Aventura');
        $this->post('/videojuegos', $datos)->assertRedirect('/videojuegos')->assertSessionHas('success');

        $videojuego = Videojuego::firstOrFail();
        $original = $videojuego->imagen;
        Storage::disk('public')->assertExists($original);
        $this->get('/videojuegos')->assertOk()->assertSee('La isla del tesoro')->assertSee('Aventura')->assertSee('29,95');
        $this->get('/videojuegos/'.$videojuego->id.'/edit')->assertOk()->assertSee('La isla del tesoro');
        $this->assertTrue($videojuego->genero->videojuegos->contains($videojuego));

        unset($datos['imagen']);
        $datos['titulo'] = 'Título editado';
        $datos['precio'] = '0';
        $datos['genero_id'] = Genero::create(['nombre' => 'Deportes'])->id;
        $this->put('/videojuegos/'.$videojuego->id, $datos)->assertRedirect('/videojuegos')->assertSessionHasNoErrors();
        $this->assertSame($original, $videojuego->fresh()->imagen);
        $this->assertSame('Deportes', $videojuego->fresh()->genero->nombre);
        Storage::disk('public')->assertExists($original);

        $datos['imagen'] = UploadedFile::fake()->image('nueva.jpg');
        $this->put('/videojuegos/'.$videojuego->id, $datos)->assertSessionHasNoErrors();
        $nueva = $videojuego->fresh()->imagen;
        $this->assertNotSame($original, $nueva);
        Storage::disk('public')->assertMissing($original);
        Storage::disk('public')->assertExists($nueva);

        $this->delete('/videojuegos/'.$videojuego->id)->assertRedirect('/videojuegos');
        $this->assertDatabaseCount('videojuegos', 0);
        Storage::disk('public')->assertMissing($nueva);
        $this->get('/videojuegos/999999/edit')->assertNotFound();
    }

    public function test_valida_campos_y_archivos_sin_guardar_datos_invalidos(): void
    {
        $datos = $this->datos([
            'titulo' => '', 'precio' => '-1', 'genero_id' => 999999,
            'imagen' => UploadedFile::fake()->create('falsa.jpg', 10, 'text/plain'),
        ]);
        $this->from('/videojuegos/create')->post('/videojuegos', $datos)
            ->assertRedirect('/videojuegos/create')->assertSessionHasErrors(['titulo', 'precio', 'genero_id', 'imagen']);

        $this->post('/videojuegos', $this->datos(['precio' => '1.234']))->assertSessionHasErrors('precio');
        $this->post('/videojuegos', $this->datos([
            'imagen' => UploadedFile::fake()->image('grande.png')->size(2049),
        ]))->assertSessionHasErrors('imagen');

        $sinImagen = $this->datos();
        unset($sinImagen['imagen']);
        $this->post('/videojuegos', $sinImagen)->assertSessionHasErrors('imagen');
        $this->assertDatabaseCount('videojuegos', 0);
        $this->assertCount(0, Storage::disk('public')->allFiles('videojuegos'));
    }

    public function test_edicion_invalida_conserva_datos_y_archivo_originales(): void
    {
        $this->post('/videojuegos', $this->datos())->assertSessionHasNoErrors();
        $videojuego = Videojuego::firstOrFail();
        $ruta = $videojuego->imagen;

        $this->from('/videojuegos/'.$videojuego->id.'/edit')->put('/videojuegos/'.$videojuego->id, $this->datos([
            'titulo' => 'Cambio rechazado', 'precio' => -10,
        ]))->assertSessionHasErrors('precio')->assertSessionHasInput('titulo', 'Cambio rechazado');

        $this->assertSame('La isla del tesoro', $videojuego->fresh()->titulo);
        $this->assertSame($ruta, $videojuego->fresh()->imagen);
        Storage::disk('public')->assertExists($ruta);
        $this->assertCount(1, Storage::disk('public')->allFiles('videojuegos'));
    }

    public function test_filtra_por_genero_y_permite_volver_al_listado_completo(): void
    {
        $aventura = Genero::create(['nombre' => 'Aventura']);
        $deportes = Genero::create(['nombre' => 'Deportes']);
        $sinJuegos = Genero::create(['nombre' => 'Estrategia']);

        Videojuego::create([
            'titulo' => 'La isla del tesoro', 'precio' => '29.95',
            'genero_id' => $aventura->id, 'imagen' => 'videojuegos/aventura.png',
        ]);
        Videojuego::create([
            'titulo' => 'Fútbol del aula', 'precio' => '19.95',
            'genero_id' => $deportes->id, 'imagen' => 'videojuegos/deportes.png',
        ]);

        $this->get('/videojuegos?genero_id='.$aventura->id)->assertOk()
            ->assertSee('La isla del tesoro')->assertDontSee('Fútbol del aula')
            ->assertSee('value="'.$aventura->id.'" selected', false);
        $this->get('/videojuegos?genero_id='.$deportes->id)->assertOk()
            ->assertSee('Fútbol del aula')->assertDontSee('La isla del tesoro');
        $this->get('/videojuegos')->assertOk()->assertSee('La isla del tesoro')->assertSee('Fútbol del aula');
        $this->get('/videojuegos?genero_id=')->assertOk()->assertSee('La isla del tesoro')->assertSee('Fútbol del aula');
        $this->get('/videojuegos?genero_id='.$sinJuegos->id)->assertOk()->assertSee('No hay videojuegos de este género.');
        $this->from('/videojuegos?genero_id=invalido')->get('/videojuegos?genero_id=invalido')
            ->assertRedirect(route('videojuegos.index'))->assertSessionHasErrors('genero_id');
        $this->get('/videojuegos?genero_id=999999')->assertRedirect(route('videojuegos.index'))
            ->assertSessionHasErrors('genero_id');
        $this->get('/videojuegos')->assertOk();
    }
}

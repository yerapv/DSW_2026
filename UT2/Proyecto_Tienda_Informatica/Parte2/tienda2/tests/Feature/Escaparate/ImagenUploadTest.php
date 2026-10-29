<?php

namespace Tests\Feature\Escaparate;

use App\Models\Imagen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImagenUploadTest extends TestCase
{
    use RefreshDatabase;

    /** Una subida válida debe dejar tanto registro como archivo físico. */
    public function test_subida_y_borrado_de_imagen(): void
    {
        Storage::fake('public');
        $this->post('/admin/imagenes', ['nombre' => 'Teclado', 'autor' => 'Ana', 'imagen' => UploadedFile::fake()->image('teclado.jpg')])
            ->assertRedirect('/admin/imagenes');
        $imagen = Imagen::firstOrFail();
        Storage::disk('public')->assertExists($imagen->ruta_imagen);
        $this->get('/imagenes')->assertOk()->assertSee('Teclado');

        // El borrado prueba el evento deleted del modelo y la coordinación BD/disco.
        $this->delete('/admin/imagenes/'.$imagen->id)->assertRedirect('/admin/imagenes');
        $this->assertDatabaseCount('escaparate_imagenes', 0);
        Storage::disk('public')->assertMissing($imagen->ruta_imagen);
    }

    /** Un archivo que no es una imagen debe rechazarse antes de guardarlo. */
    public function test_rechaza_archivos_que_no_son_imagenes(): void
    {
        Storage::fake('public');
        $this->post('/admin/imagenes', ['nombre' => 'Documento', 'autor' => 'Ana', 'imagen' => UploadedFile::fake()->create('archivo.pdf', 20)])
            ->assertSessionHasErrors('imagen');
        $this->assertDatabaseCount('escaparate_imagenes', 0);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }
}

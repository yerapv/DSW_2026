<?php

namespace Tests\Feature;

use App\Models\Pieza;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PiezaUploadTest extends TestCase
{
    use RefreshDatabase;
    public function test_subida(): void
    {
        Storage::fake('public');
        $r = $this->post('/piezas', ['nombre' => 'Vaso', 'autor' => 'Anónimo', 'imagen' => UploadedFile::fake()->image('vaso.jpg')]);
        $r->assertRedirect('/piezas');
        $p = Pieza::firstOrFail();
        Storage::disk('public')->assertExists($p->ruta_imagen);
    }
}

<?php

namespace Tests\Feature;

use App\Ejercicios\E10\Models\Libro as LibroBiblioteca;
use App\Ejercicios\E11\Models\Editorial;
use App\Ejercicios\E12\Models\Actividad;
use App\Ejercicios\E12\Models\Alumno;
use App\Ejercicios\E14\Enums\TipoVehiculo;
use App\Ejercicios\E14\Models\Vehiculo;
use App\Ejercicios\E17\Models\Pieza;
use App\Ejercicios\E18\Models\Presupuesto;
use App\Ejercicios\E18\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class IntegracionEjerciciosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_resumen_enlaza_los_dieciocho_ejercicios(): void
    {
        $respuesta = $this->get('/')->assertOk();

        $this->assertCount(18, config('ejercicios'));
        foreach (config('ejercicios') as $ejercicio) {
            $respuesta->assertSee($ejercicio['titulo'])->assertSee(url($ejercicio['url']));
            $this->get($ejercicio['url'])->assertOk();
        }
    }

    public static function paginas(): array
    {
        return [
            ['/e01/bienvenida', 'Bienvenido a nuestra programación de actividades.'],
            ['/e02/pelicula', 'Alien, el octavo pasajero'],
            ['/e03/menu', 'Disponibles: 4'],
            ['/e04/eventos?tipo=teatro', 'Hamlet'],
            ['/e05/envios', 'Urgente: 10 €'],
            ['/e06/inscripcion', 'Inscripción'],
            ['/e07/reserva', 'Reservar'],
            ['/e07/reserva/correcta', 'Resultado'],
            ['/e08/mascotas/nueva', 'Clínica Huellas'],
            ['/e09/notas', 'No hay notas.'],
            ['/e09/notas/nueva', 'Nueva nota'],
            ['/e10/libros', 'Libros'],
            ['/e10/libros/create', 'Nuevo libro'],
            ['/e11/editoriales', 'Isla de papel'],
            ['/e11/editoriales/libros/nuevo', 'Norte'],
            ['/e12/alumnos/1', 'Alumno Demo'],
            ['/e13/talleres', 'Monitor:'],
            ['/e14/vehiculos', 'Vehículos'],
            ['/e14/vehiculos/nuevo', 'Nuevo vehículo'],
            ['/e15/imprenta', 'Presupuesto imprenta'],
            ['/e16/residencia', 'Solicitud'],
            ['/e17/piezas', 'Piezas'],
            ['/e17/piezas/nueva', 'Nueva pieza'],
            ['/e18/alquiler', 'Cámara'],
        ];
    }

    #[DataProvider('paginas')]
    public function test_paginas_y_formularios_conservan_el_prefijo_del_ejercicio(string $url, string $contenido): void
    {
        $respuesta = $this->get($url)->assertOk()->assertSee($contenido);
        $respuesta->assertSee('Todos los ejercicios');
        $prefijo = explode('/', $url)[1];

        preg_match_all('/(?:action|href)="([^"]+)"/', $respuesta->getContent(), $enlaces);
        foreach ($enlaces[1] as $enlace) {
            if (str_starts_with($enlace, '#')) {
                continue;
            }
            $ruta = parse_url(html_entity_decode($enlace), PHP_URL_PATH) ?? '/';
            if ($ruta === '/' || $ruta === '/css/laboratorio.css') {
                continue;
            }
            $this->assertStringStartsWith('/'.$prefijo.'/', $ruta);
        }
    }

    public function test_e04_filtra_los_eventos_del_codigo_original(): void
    {
        $this->get('/e04/eventos?tipo=teatro')->assertOk()
            ->assertSee('Hamlet')->assertSee('Comedia breve')->assertDontSee('Jazz de otoño');
    }

    public static function camposObligatorios(): array
    {
        return [
            'E07 reserva' => ['/e07/reserva', [
                'nombre' => 'Indica el nombre de la persona que realiza la reserva.',
                'email' => 'Indica un correo electrónico para la reserva.',
                'personas' => 'Indica el número de personas de la reserva.',
                'fecha' => 'Selecciona la fecha de la reserva.',
            ]],
            'E08 mascota' => ['/e08/mascotas', [
                'nombre' => 'Indica el nombre de la mascota.',
                'especie' => 'Selecciona la especie de la mascota.',
                'edad' => 'Indica la edad de la mascota.',
                'responsable' => 'Indica el nombre de la persona responsable de la mascota.',
            ]],
            'E09 nota' => ['/e09/notas', [
                'titulo' => 'Escribe el título de la nota.',
                'contenido' => 'Escribe el contenido de la nota.',
            ]],
            'E10 biblioteca' => ['/e10/libros', [
                'titulo' => 'Indica el título del libro.',
                'autor' => 'Indica el autor del libro.',
                'anio' => 'Indica el año de publicación del libro.',
            ]],
            'E11 editorial' => ['/e11/editoriales/libros', [
                'titulo' => 'Indica el título del libro.',
                'editorial_id' => 'Selecciona la editorial del libro.',
            ]],
            'E12 actividad' => ['/e12/alumnos/1/actividades', [
                'actividad_id' => 'Selecciona la actividad en la que deseas inscribir al alumno.',
            ]],
            'E14 vehiculo' => ['/e14/vehiculos', [
                'nombre' => 'Indica el nombre del vehículo.',
                'matricula' => 'Indica la matrícula del vehículo.',
                'tipo' => 'Selecciona el tipo de vehículo.',
            ]],
            'E15 imprenta' => ['/e15/imprenta', [
                'copias' => 'Indica el número de copias que deseas imprimir.',
            ]],
            'E16 residencia' => ['/e16/residencia', [
                'edad' => 'Indica la edad de la persona solicitante.',
                'renta' => 'Indica la renta de la persona solicitante.',
                'distancia_km' => 'Indica la distancia al centro en kilómetros.',
            ]],
            'E17 pieza' => ['/e17/piezas', [
                'nombre' => 'Indica el nombre de la pieza.',
                'autor' => 'Indica el autor de la pieza.',
                'imagen' => 'Selecciona una imagen de la pieza.',
            ]],
            'E18 alquiler' => ['/e18/alquiler/lineas', [
                'producto_id' => 'Selecciona el producto que deseas alquilar.',
                'dias' => 'Indica el número de días de alquiler.',
            ]],
        ];
    }

    #[DataProvider('camposObligatorios')]
    public function test_los_campos_obligatorios_muestran_mensajes_especificos_del_ejercicio(string $url, array $mensajes): void
    {
        $this->from('/')->post($url, [])->assertRedirect('/')->assertSessionHasErrors($mensajes);
    }

    public function test_e06_muestra_los_datos_enviados(): void
    {
        $this->post('/e06/inscripcion', ['nombre' => 'Ana', 'email' => 'ana@example.com', 'edad' => 20, 'nivel' => 'medio'])
            ->assertOk()->assertSee('ana@example.com')->assertSee('medio');
    }

    public function test_e07_valida_y_redirige_dentro_del_ejercicio(): void
    {
        $this->from('/e07/reserva')->post('/e07/reserva', [])->assertRedirect('/e07/reserva')
            ->assertSessionHasErrors(['nombre', 'email', 'personas', 'fecha']);
        $this->post('/e07/reserva', ['nombre' => 'Ana', 'email' => 'ana@example.com', 'personas' => 2, 'fecha' => '2026-10-20'])
            ->assertRedirect('/e07/reserva/correcta')->assertSessionHas('ok');
        $this->get('/e07/reserva/correcta')->assertOk()->assertSee('Reserva recibida correctamente.');
    }

    public function test_e08_form_request_y_layout(): void
    {
        $this->post('/e08/mascotas', [])->assertSessionHasErrors(['nombre', 'especie', 'edad', 'responsable']);
        $this->post('/e08/mascotas', ['nombre' => 'Luna', 'especie' => 'gato', 'edad' => 3, 'responsable' => 'Ana'])
            ->assertOk()->assertSee('Luna')->assertSee('Clínica Huellas')->assertSee('Responsable: Ana');
    }

    public function test_e09_crea_y_lista_notas(): void
    {
        $this->post('/e09/notas', ['titulo' => 'Nota de prueba', 'contenido' => 'Contenido de prueba'])
            ->assertRedirect('/e09/notas');
        $this->assertDatabaseHas('notas', ['titulo' => 'Nota de prueba']);
        $this->get('/e09/notas')->assertOk()->assertSee('Contenido de prueba');
    }

    public function test_e10_crud_completo_y_aislamiento_de_e11(): void
    {
        $this->post(route('e10.libros.store'), ['titulo' => 'Libro de prueba', 'autor' => 'Ana', 'anio' => 2020, 'disponible' => 1])
            ->assertRedirect(route('e10.libros.index'));
        $libro = LibroBiblioteca::firstOrFail();
        $this->get(route('e10.libros.show', $libro))->assertOk()->assertSee('Disponible');
        $this->get(route('e10.libros.edit', $libro))->assertOk()->assertSee('Libro de prueba');
        $this->put(route('e10.libros.update', $libro), ['titulo' => 'Libro editado', 'autor' => 'Ana', 'anio' => 2021])
            ->assertRedirect(route('e10.libros.index'));
        $this->assertFalse($libro->fresh()->disponible);
        $this->get(route('e10.libros.show', $libro))->assertOk()->assertSee('Libro editado');
        $this->delete(route('e10.libros.destroy', $libro))->assertRedirect(route('e10.libros.index'));
        $this->assertDatabaseCount('libros', 0);
        $this->assertDatabaseCount('libros_editoriales', 3);
    }

    public function test_e11_crea_libro_en_su_editorial(): void
    {
        $editorial = Editorial::where('nombre', 'Norte')->firstOrFail();
        $this->post('/e11/editoriales/libros', ['titulo' => 'Libro de prueba', 'editorial_id' => $editorial->id])
            ->assertRedirect('/e11/editoriales');
        $this->assertDatabaseHas('libros_editoriales', ['titulo' => 'Libro de prueba', 'editorial_id' => $editorial->id]);
        $this->assertDatabaseCount('libros', 0);
        $this->get('/e11/editoriales')->assertOk()->assertSee('Libro de prueba');
    }

    public function test_e12_inscribe_sin_duplicados_y_quita_actividad(): void
    {
        $alumno = Alumno::firstOrFail();
        $actividad = Actividad::where('nombre', 'Ajedrez')->firstOrFail();
        $url = '/e12/alumnos/'.$alumno->id;
        for ($i = 0; $i < 2; $i++) {
            $this->from($url)->post($url.'/actividades', ['actividad_id' => $actividad->id])->assertRedirect($url);
        }
        $this->assertDatabaseCount('actividad_alumno', 1);
        $this->get($url)->assertOk()->assertSee(now()->toDateString());
        $this->from($url)->delete($url.'/actividades/'.$actividad->id)->assertRedirect($url);
        $this->assertDatabaseCount('actividad_alumno', 0);
    }

    public function test_e13_filtra_y_pagina_con_los_datos_originales(): void
    {
        $this->get('/e13/talleres?nivel=avanzado&q=Taller+1')->assertOk()
            ->assertSee('Taller 11')->assertSee('Taller 14')->assertSee('Taller 17')->assertDontSee('Taller 10');
        $this->get('/e13/talleres?nivel=inicial')->assertOk()->assertSee('nivel=inicial&amp;page=2', false);
        $this->get('/e13/talleres?nivel=inicial&page=2')->assertOk()->assertSee('Taller 9');
    }

    public static function busquedasE13(): array
    {
        return [
            'monitor Ana' => ['Ana', '', ['Taller 1', 'Taller 11', 'Taller 13', 'Taller 15', 'Taller 17'], 9],
            'monitor Luis' => ['Luis', '', ['Taller 10', 'Taller 12', 'Taller 14', 'Taller 16', 'Taller 18'], 9],
            'monitor y nivel' => ['Ana', 'avanzado', ['Taller 11', 'Taller 17', 'Taller 5'], 3],
            'otro monitor y nivel' => ['Luis', 'inicial', ['Taller 12', 'Taller 18', 'Taller 6'], 3],
            'nombre inexistente' => ['Maria', '', [], 0],
            'titulo y nivel' => ['Taller 1', 'avanzado', ['Taller 11', 'Taller 14', 'Taller 17'], 3],
        ];
    }

    #[DataProvider('busquedasE13')]
    public function test_e13_busca_por_titulo_o_monitor_y_respeta_el_nivel(string $busqueda, string $nivel, array $titulos, int $total): void
    {
        $respuesta = $this->get('/e13/talleres?'.http_build_query(['q' => $busqueda, 'nivel' => $nivel]))->assertOk();
        $talleres = $respuesta->viewData('talleres');

        $this->assertSame($total, $talleres->total());
        $this->assertSame($titulos, $talleres->getCollection()->pluck('titulo')->all());

        if ($total === 0) {
            $respuesta->assertSee('No hay talleres que coincidan con los filtros.');
        }
    }

    public function test_e13_conserva_la_busqueda_por_monitor_al_paginar(): void
    {
        $this->get('/e13/talleres?q=Ana')->assertOk()->assertSee('q=Ana&amp;page=2', false);
        $respuesta = $this->get('/e13/talleres?q=Ana&page=2')->assertOk();

        $this->assertSame(['Taller 3', 'Taller 5', 'Taller 7', 'Taller 9'], $respuesta->viewData('talleres')->getCollection()->pluck('titulo')->all());
        $this->assertSame(9, $respuesta->viewData('talleres')->total());
        $respuesta->assertDontSee('Monitor: Luis');
    }

    public function test_e14_guarda_enum_json_y_valida_matricula_unica(): void
    {
        $datos = ['nombre' => 'Moto de prueba', 'matricula' => '1234ABC', 'tipo' => 'moto', 'cilindrada' => 125];
        $this->post('/e14/vehiculos', $datos)->assertRedirect('/e14/vehiculos');
        $vehiculo = Vehiculo::firstOrFail();
        $this->assertInstanceOf(TipoVehiculo::class, $vehiculo->tipo);
        $this->assertSame(['cilindrada' => 125], $vehiculo->caracteristicas);
        $this->get('/e14/vehiculos')->assertOk()->assertSee('125 cc');
        $this->post('/e14/vehiculos', $datos)->assertSessionHasErrors('matricula');
        $this->post('/e14/vehiculos', ['nombre' => 'Coche de prueba', 'matricula' => '5678DEF', 'tipo' => 'coche', 'plazas' => 5])
            ->assertRedirect('/e14/vehiculos');
        $this->get('/e14/vehiculos')->assertOk()->assertSee('5 plazas');
    }

    public function test_e15_calcula_con_las_tarifas_originales(): void
    {
        $this->post('/e15/imprenta', ['copias' => 100])->assertOk()->assertSee('5.00 €');
        $this->post('/e15/imprenta', ['copias' => 100, 'color' => 1])->assertOk()->assertSee('8.00 €');
        $this->post('/e15/imprenta', ['copias' => 100, 'encuadernado' => 1])->assertOk()->assertSee('7.50 €');
        $this->post('/e15/imprenta', ['copias' => 0])->assertSessionHasErrors('copias');
    }

    public function test_e16_muestra_resultado_y_reglas_originales(): void
    {
        $this->post('/e16/residencia', ['edad' => 20, 'renta' => 20000, 'distancia_km' => 20, 'documentacion_completa' => 1])
            ->assertOk()->assertSee('Solicitud válida');
        $this->post('/e16/residencia', ['edad' => 17, 'renta' => 50000, 'distancia_km' => 5])
            ->assertOk()->assertSee('Debe ser mayor de edad.')->assertSee('La renta supera el máximo permitido.')
            ->assertSee('Falta documentación.')->assertSee('La distancia al centro es baja');
    }

    public function test_e17_rechaza_ficheros_invalidos_y_borra_imagen(): void
    {
        Storage::fake('public');
        $this->post('/e17/piezas', ['nombre' => 'Vaso', 'autor' => 'Anónimo', 'imagen' => UploadedFile::fake()->create('texto.txt')])
            ->assertSessionHasErrors('imagen');
        $this->post('/e17/piezas', ['nombre' => 'Vaso', 'autor' => 'Anónimo', 'imagen' => UploadedFile::fake()->image('vaso.png')])
            ->assertRedirect('/e17/piezas');
        $pieza = Pieza::firstOrFail();
        $this->get('/e17/piezas')->assertOk()->assertSee('Vaso')->assertSee(Storage::disk('public')->url($pieza->ruta_imagen));
        $this->from('/e17/piezas')->delete('/e17/piezas/'.$pieza->id)->assertRedirect('/e17/piezas');
        $this->assertDatabaseCount('piezas', 0);
        Storage::disk('public')->assertMissing($pieza->ruta_imagen);
    }

    public function test_e18_carrito_presupuesto_e_instantanea(): void
    {
        $this->from('/e18/alquiler')->post('/e18/alquiler/presupuesto')->assertRedirect('/e18/alquiler')->assertSessionHasErrors('carrito');
        $producto = Producto::where('nombre', 'Cámara')->firstOrFail();
        $this->from('/e18/alquiler')->post('/e18/alquiler/lineas', ['producto_id' => $producto->id, 'dias' => 2])->assertRedirect('/e18/alquiler');
        $this->post('/e18/alquiler/presupuesto')->assertRedirect('/e18/alquiler/presupuestos/1');
        $presupuesto = Presupuesto::firstOrFail();
        $this->assertSame(8000, $presupuesto->total_centimos);
        $producto->update(['nombre' => 'Cámara actualizada', 'precio_dia_centimos' => 5500]);
        $this->get('/e18/alquiler/presupuestos/'.$presupuesto->id)->assertOk()
            ->assertSee('Cámara')->assertSee('80.00 €')->assertDontSee('Cámara actualizada');
    }

    public function test_seeders_reutilizan_los_datos_originales_sin_duplicarlos(): void
    {
        $this->seed();

        $this->assertDatabaseCount('editoriales', 2);
        $this->assertDatabaseCount('libros_editoriales', 3);
        $this->assertDatabaseCount('alumnos', 1);
        $this->assertDatabaseCount('actividades', 3);
        $this->assertDatabaseCount('monitores', 2);
        $this->assertDatabaseCount('talleres', 18);
        $this->assertDatabaseCount('productos', 3);
    }
}

<?php

namespace Tests\Feature;

use App\Ejercicios\E06\Enums\Rol;
use App\Ejercicios\E07\Models\Nota;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class IntegracionEjerciciosTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalogo_contiene_solo_los_siete_ejercicios_de_ut3(): void
    {
        $response = $this->get('/')->assertOk()->assertSee('Ejercicios de la UT3')->assertDontSee('UT2');
        $this->assertCount(7, config('ejercicios'));
        foreach (config('ejercicios') as $ejercicio) {
            $response->assertSee($ejercicio['titulo'])->assertSee(url($ejercicio['url']), false);
        }
        $this->get('/e08')->assertNotFound();
    }

    public function test_contador_incrementa_y_reinicia_sin_borrar_favoritos(): void
    {
        $this->withSession(['favoritos' => [2]])->get('/e01/contador')->assertOk()->assertSee('Visitas en esta sesión: 1');
        $this->get('/e01/contador')->assertSee('Visitas en esta sesión: 2');
        $this->post('/e01/contador/reiniciar')->assertRedirect('/e01/contador')->assertSessionMissing('visitas')->assertSessionHas('favoritos', [2]);
        $this->get('/e01/contador')->assertSee('Visitas en esta sesión: 1');
    }

    public function test_cookie_se_guarda_se_lee_y_se_elimina(): void
    {
        $this->get('/e02/lectura')->assertOk()->assertSee('Tema actual: claro');
        $this->post('/e02/lectura/tema', ['tema' => 'oscuro'])->assertRedirect('/e02/lectura')->assertCookie('tema', 'oscuro');
        $this->withCookie('tema', 'oscuro')->get('/e02/lectura')->assertSee('Tema actual: oscuro');
        $this->delete('/e02/lectura/tema')->assertRedirect('/e02/lectura')->assertCookieExpired('tema');
        $this->post('/e02/lectura/tema', ['tema' => 'invalido'])->assertSessionHasErrors('tema');
    }

    public function test_favoritos_no_duplican_y_pueden_quitarse_o_vaciarse(): void
    {
        $this->get('/e03/recetas')->assertOk()->assertSee('Tortilla');
        $this->from('/e03/recetas')->post('/e03/recetas/1/favorito')->assertSessionHas('favoritos', [1]);
        $this->post('/e03/recetas/1/favorito')->assertSessionHas('favoritos', [1]);
        $this->post('/e03/recetas/2/favorito')->assertSessionHas('favoritos', [1, 2]);
        $this->delete('/e03/recetas/1/favorito')->assertSessionHas('favoritos', [2]);
        $this->post('/e03/recetas/99/favorito')->assertNotFound();
        $this->delete('/e03/favoritos')->assertSessionMissing('favoritos');
    }

    public function test_registro_crea_socio_con_hash_y_no_acepta_rol_del_formulario(): void
    {
        $this->get('/e04/registro')->assertOk();
        $this->post('/e04/registro', ['name' => 'Ana', 'email' => 'ana@example.test', 'password' => 'password', 'password_confirmation' => 'password', 'rol' => 'gestor'])
            ->assertRedirect('/e04/bienvenida');
        $user = User::where('email', 'ana@example.test')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('password', $user->password));
        $this->assertSame(Rol::Socio, $user->rol);
        $this->get('/e04/bienvenida')->assertOk()->assertSee('Ana');
        $this->get('/e04/registro')->assertRedirect('/e05/perfil');
    }

    public function test_registro_rechaza_email_duplicado_y_confirmacion_incorrecta(): void
    {
        $user = User::factory()->create();
        $this->post('/e04/registro', ['name' => 'Ana', 'email' => $user->email, 'password' => 'password', 'password_confirmation' => 'otra'])
            ->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_rutas_protegidas_redirigen_al_acceso_de_e05(): void
    {
        foreach (['/e04/bienvenida', '/e05/perfil', '/e06/gestion', '/e07/notas', '/e07/notas/1'] as $url) {
            $this->get($url)->assertRedirect('/e05/acceso');
        }
        $this->post('/e07/notas')->assertRedirect('/e05/acceso');
        $this->put('/e07/notas/1')->assertRedirect('/e05/acceso');
        $this->delete('/e07/notas/1')->assertRedirect('/e05/acceso');
        $this->post('/e05/salir')->assertRedirect('/e05/acceso');
        $this->get('/e05/salir')->assertMethodNotAllowed();
    }

    public function test_acceso_incorrecto_conserva_email_y_no_autentica(): void
    {
        User::factory()->create(['email' => 'socio@example.test']);
        $this->get('/e05/acceso')->assertOk();
        $this->from('/e05/acceso')->post('/e05/acceso', ['email' => 'socio@example.test', 'password' => 'incorrecta'])
            ->assertRedirect('/e05/acceso')->assertSessionHasErrors('email')->assertSessionHasInput('email', 'socio@example.test');
        $this->assertGuest();
    }

    public function test_login_recordado_respeta_destino_y_logout_invalida_sesion(): void
    {
        $user = User::factory()->create(['password' => 'password']);
        $this->get('/e07/notas')->assertRedirect('/e05/acceso');
        $this->post('/e05/acceso', ['email' => $user->email, 'password' => 'password', 'remember' => '1'])
            ->assertRedirect('/e07/notas')->assertCookie(auth()->guard()->getRecallerName());
        $this->assertAuthenticatedAs($user);
        $this->get('/e05/perfil')->assertOk()->assertSee($user->email);
        $this->get('/e05/acceso')->assertRedirect('/e05/perfil');
        $this->post('/e05/salir')->assertRedirect('/e05/acceso');
        $this->assertGuest();
    }

    public function test_middleware_permite_gestor_y_denega_socio(): void
    {
        $socio = User::factory()->create();
        $gestor = User::factory()->create();
        $gestor->rol = Rol::Gestor;
        $gestor->save();
        $this->actingAs($socio)->get('/e06/gestion')->assertForbidden()->assertSee('Acceso denegado');
        $this->actingAs($gestor)->get('/e06/gestion')->assertOk()->assertSee('Acceso autorizado.');
    }

    public function test_notas_solo_muestran_las_propias_y_guardan_propietario_autenticado(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $other->notas()->create(['titulo' => 'Secreto ajeno', 'contenido' => 'Privado']);
        $this->actingAs($user)->get('/e07/notas')->assertOk()->assertDontSee('Secreto ajeno');
        $this->from('/e07/notas')->post('/e07/notas', ['titulo' => 'Mi nota', 'contenido' => 'Texto', 'user_id' => $other->id])->assertRedirect('/e07/notas');
        $nota = Nota::where('titulo', 'Mi nota')->firstOrFail();
        $this->assertTrue($nota->user->is($user));
        $this->get('/e07/notas')->assertSee('Mi nota');
        $this->get('/e07/notas/'.$nota->id)->assertOk()->assertSee('Guardar cambios')->assertSee('Eliminar nota');
        $this->from('/e07/notas/'.$nota->id)->put('/e07/notas/'.$nota->id, ['titulo' => 'Editada', 'contenido' => 'Nuevo texto'])->assertRedirect('/e07/notas/'.$nota->id);
        $this->assertDatabaseHas('notas', ['id' => $nota->id, 'titulo' => 'Editada']);
        $this->delete('/e07/notas/'.$nota->id)->assertRedirect('/e07/notas');
        $this->assertModelMissing($nota);
    }

    public function test_policy_denega_lectura_edicion_y_borrado_ajenos_incluso_a_gestor(): void
    {
        $owner = User::factory()->create();
        $nota = $owner->notas()->create(['titulo' => 'Privada', 'contenido' => 'Original']);
        $intruder = User::factory()->create();
        $intruder->rol = Rol::Gestor;
        $intruder->save();
        $this->actingAs($intruder)->get('/e07/notas/'.$nota->id)->assertForbidden();
        $this->put('/e07/notas/'.$nota->id, ['titulo' => 'Robada', 'contenido' => 'Cambio'])->assertForbidden();
        $this->delete('/e07/notas/'.$nota->id)->assertForbidden();
        $this->assertDatabaseHas('notas', ['id' => $nota->id, 'titulo' => 'Privada', 'contenido' => 'Original']);
    }

    public function test_notas_validan_datos_y_devuelven_404_para_identificadores_inexistentes(): void
    {
        $user = User::factory()->create();
        $nota = $user->notas()->create(['titulo' => 'Original', 'contenido' => 'Texto']);
        $this->actingAs($user)->post('/e07/notas', ['titulo' => str_repeat('x', 121), 'contenido' => ''])->assertSessionHasErrors(['titulo', 'contenido']);
        $this->put('/e07/notas/'.$nota->id, ['titulo' => '', 'contenido' => ''])->assertSessionHasErrors(['titulo', 'contenido']);
        $this->get('/e07/notas/999')->assertNotFound();
        $this->assertDatabaseCount('notas', 1);
    }

    public function test_seeders_pueden_repetirse_sin_duplicar_ni_cambiar_cuentas(): void
    {
        $this->seed(DatabaseSeeder::class);
        $user = User::where('email', 'socio@ut3.test')->firstOrFail();
        $user->update(['password' => 'nueva-clave']);
        $this->seed(DatabaseSeeder::class);
        $this->assertDatabaseCount('users', 3);
        $this->assertTrue(Hash::check('nueva-clave', $user->fresh()->password));
        $this->assertSame(Rol::Gestor, User::where('email', 'gestor@ut3.test')->firstOrFail()->rol);
    }
}

<?php

namespace Tests\Feature\Persistencia;

use App\Models\Imagen;
use App\Models\Presupuesto;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MigracionAsociacionesTest extends TestCase
{
    use DatabaseMigrations;

    public function test_ampliacion_conserva_imagenes_carritos_y_presupuestos_anteriores(): void
    {
        Artisan::call('migrate:rollback', ['--step' => 8, '--force' => true]);
        $ahora = now();
        $imagenId = DB::table('imagenes')->insertGetId([
            'nombre' => 'Foto anterior', 'autor' => 'Ana', 'ruta_imagen' => 'escaparate/antigua.jpg',
            'created_at' => $ahora, 'updated_at' => $ahora,
        ]);
        $carritoId = DB::table('carritos')->insertGetId(['created_at' => $ahora, 'updated_at' => $ahora]);
        $presupuestoId = DB::table('presupuestos')->insertGetId([
            'total_centimos' => 4900,
            'instantanea' => json_encode([['producto' => 'Artículo anterior', 'cantidad' => 2, 'precio_centimos' => 4900]]),
            'created_at' => $ahora, 'updated_at' => $ahora,
        ]);

        $pedidos = [];
        foreach (['pagado_simulado' => 2, 'enviado' => 2, 'entregado' => 3, 'pendiente' => 1, 'cancelado' => 4] as $anterior => $actual) {
            $id = DB::table('pedidos')->insertGetId([
                'total_centimos' => 4900, 'instantanea' => '[]', 'metodo_pago' => 'tarjeta',
                'estado' => $anterior, 'created_at' => $ahora, 'updated_at' => $ahora,
            ]);
            $pedidos[$id] = $actual;
        }

        $categoriaId = DB::table('categorias')->insertGetId(['nombre' => 'Anterior']);
        $productos = [];
        foreach (['portatil' => 1, 'periferico' => 2, 'componente' => 3] as $texto => $tipo) {
            $id = DB::table('productos')->insertGetId([
                'categoria_id' => $categoriaId, 'nombre' => $texto, 'marca' => 'Anterior',
                'anio' => 2026, 'precio_centimos' => 4900, 'tipo' => $texto,
            ]);
            $productos[$id] = $tipo;
        }

        $productoId = array_key_first($productos);
        DB::table('lineas_carrito')->insert([
            ['carrito_id' => $carritoId, 'producto_id' => $productoId, 'cantidad' => 2, 'precio_centimos' => 8000],
            ['carrito_id' => $carritoId, 'producto_id' => $productoId, 'cantidad' => 3, 'precio_centimos' => 15000],
        ]);

        Artisan::call('migrate', ['--force' => true]);
        $this->assertDatabaseCount('lineas_carrito', 1);
        $this->assertDatabaseHas('lineas_carrito', ['carrito_id' => $carritoId, 'producto_id' => $productoId, 'cantidad' => 5, 'precio_centimos' => 23000]);
        $this->assertTrue(Schema::hasColumns('clientes', ['tipo', 'edad']));
        foreach ($productos as $id => $tipo) {
            $this->assertSame($tipo, DB::table('productos')->where('id', $id)->value('tipo'));
        }
        foreach ($pedidos as $id => $estado) {
            $this->assertDatabaseHas('pedidos', ['id' => $id, 'estado' => $estado, 'total_centimos' => 4900]);
        }
        $this->assertFalse(Schema::hasTable('imagenes'));
        $this->assertSame('escaparate/antigua.jpg', Imagen::findOrFail($imagenId)->ruta_imagen);
        $this->assertDatabaseHas('carritos', ['id' => $carritoId, 'cliente_id' => null]);
        $presupuesto = Presupuesto::findOrFail($presupuestoId);
        $this->assertSame(4900, $presupuesto->total_centimos);
        $this->assertCount(1, $presupuesto->lineas);
        $this->assertSame('Artículo anterior', $presupuesto->lineas->first()->producto_nombre);
        $this->assertNull($presupuesto->lineas->first()->producto_id);
        $this->assertNull($presupuesto->cliente_id);
    }
}

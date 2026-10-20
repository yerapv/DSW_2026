<?php

namespace Tests\Feature\E18;

use App\Ejercicios\E18\Models\Carrito;
use App\Ejercicios\E18\Models\LineaCarrito;
use App\Ejercicios\E18\Models\Producto;
use App\Ejercicios\E18\Services\ServicioPresupuestos;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PresupuestoSnapshotTest extends TestCase
{
    use RefreshDatabase;

    public function test_presupuesto_conserva_precio_historico(): void
    {
        $p = Producto::create(['nombre' => 'Cámara', 'precio_dia_centimos' => 4000]);
        $c = Carrito::create();
        LineaCarrito::create(['carrito_id' => $c->id, 'producto_id' => $p->id, 'dias' => 1, 'precio_centimos' => 4000]);
        $q = app(ServicioPresupuestos::class)->generar($c);
        $p->update(['precio_dia_centimos' => 5500]);
        $this->assertSame(4000, $q->fresh()->instantanea[0]['precio_centimos']);
    }
}

<?php

namespace App\Ejercicios\E18\Http\Controllers;

use App\Ejercicios\E18\Models\Carrito;
use App\Ejercicios\E18\Models\LineaCarrito;
use App\Ejercicios\E18\Models\Presupuesto;
use App\Ejercicios\E18\Models\Producto;
use App\Ejercicios\E18\Services\ServicioPresupuestos;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class AlquilerController extends Controller
{
    public function __construct(private readonly ServicioPresupuestos $presupuestos) {}

    private function carrito(): Carrito
    {
        return Carrito::firstOrCreate(['id' => 1]);
    }

    public function index(): View
    {
        $c = $this->carrito()->load('lineas.producto');

        return view('ejercicios.e18.alquiler.index', ['productos' => Producto::all(), 'carrito' => $c]);
    }

    public function add(Request $r): RedirectResponse
    {
        $d = $r->validate(['producto_id' => ['required', 'exists:productos,id'], 'dias' => ['required', 'integer', 'min:1']], [
            'producto_id.required' => 'Selecciona el producto que deseas alquilar.',
            'dias.required' => 'Indica el número de días de alquiler.',
        ]);
        $p = Producto::findOrFail($d['producto_id']);
        LineaCarrito::create(['carrito_id' => $this->carrito()->id, 'producto_id' => $p->id, 'dias' => $d['dias'], 'precio_centimos' => $p->precio_dia_centimos * $d['dias']]);

        return back();
    }

    public function quote(): RedirectResponse
    {
        try {
            $q = $this->presupuestos->generar($this->carrito());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['carrito' => $exception->getMessage()]);
        }

        return redirect('/e18/alquiler/presupuestos/'.$q->id);
    }

    public function showQuote(Presupuesto $presupuesto): View
    {
        return view('ejercicios.e18.alquiler.quote', compact('presupuesto'));
    }
}

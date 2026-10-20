<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\LineaCarrito;
use App\Models\Presupuesto;
use App\Models\Producto;
use App\Services\ServicioPresupuestos;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
        return view('alquiler.index', ['productos' => Producto::all(), 'carrito' => $c]);
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
        $q = $this->presupuestos->generar($this->carrito());
        return redirect('/alquiler/presupuestos/' . $q->id);
    }
    public function showQuote(Presupuesto $presupuesto): View
    {
        return view('alquiler.quote', compact('presupuesto'));
    }
}

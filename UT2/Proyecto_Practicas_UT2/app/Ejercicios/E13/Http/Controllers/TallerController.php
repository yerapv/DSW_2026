<?php

namespace App\Ejercicios\E13\Http\Controllers;

use App\Ejercicios\E13\Models\Taller;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TallerController extends Controller
{
    public function index(Request $request): View
    {
        $talleres = Taller::query()
            ->with('monitor')
            ->when($request->filled('q'), function (Builder $consulta) use ($request): void {
                $busqueda = '%'.$request->query('q').'%';

                $consulta->where(function (Builder $coincidencias) use ($busqueda): void {
                    $coincidencias->where('titulo', 'like', $busqueda)
                        ->orWhereHas('monitor', fn (Builder $monitores) => $monitores->where('nombre', 'like', $busqueda));
                });
            })
            ->when($request->filled('nivel'), fn (Builder $consulta) => $consulta->where('nivel', $request->query('nivel')))
            ->orderBy('titulo')
            ->paginate(5)
            ->withQueryString();

        return view('ejercicios.e13.talleres.index', compact('talleres'));
    }
}

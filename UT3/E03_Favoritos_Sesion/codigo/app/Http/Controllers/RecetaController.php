<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecetaController extends Controller
{
    // Lista de recetas disponibles, donde la clave es el ID de la receta y el valor es el nombre de la receta
    private array $recetas = [1 => 'Tortilla', 2 => 'Gazpacho', 3 => 'Paella', 4 => 'Pisto', 5 => 'Croquetas', 6 => 'Ensalada'];

    public function index(Request $r): View
    {
        // Muestra la vista 'recetas' con la lista de recetas y los favoritos almacenados en la sesión
        return view('recetas', ['recetas' => $this->recetas, 'favoritos' => $r->session()->get('favoritos', [])]);
    }

    public function add(Request $request, int $id): RedirectResponse
    {
        // Verifica si la receta con el ID proporcionado existe, si no, devuelve un error 404
        abort_unless(isset($this->recetas[$id]), 404);
        // Recupera la lista de favoritos de la sesión, agrega el ID de la receta y actualiza la sesión con los valores únicos
        $favoritos = $request->session()->get('favoritos', []);
        $favoritos[] = $id;
        // Actualiza la sesión con los favoritos únicos y redirige de vuelta a la página anterior
        $request->session()->put('favoritos', array_values(array_unique($favoritos)));
        return back();
    }
    public function remove(Request $request, int $id): RedirectResponse
    {
        // Verifica si la receta con el ID proporcionado existe, si no, devuelve un error 404
        abort_unless(isset($this->recetas[$id]), 404);
        // Recupera la lista de favoritos de la sesión, filtra el ID de la receta y actualiza la sesión con los valores restantes
        $favoritos = array_values(array_filter($request->session()->get('favoritos', []), fn($v) => $v !== $id));
        $request->session()->put('favoritos', $favoritos);
        return back();
    }
    public function clear(Request $request): RedirectResponse
    {
        // Elimina todos los favoritos de la sesión
        $request->session()->forget('favoritos');
        return back();
    }
}

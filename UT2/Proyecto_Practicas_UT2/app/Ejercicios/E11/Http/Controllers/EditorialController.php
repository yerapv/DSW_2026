<?php

namespace App\Ejercicios\E11\Http\Controllers;

use App\Ejercicios\E11\Models\Editorial;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class EditorialController extends Controller
{
    public function index(): View
    {
        return view('ejercicios.e11.editoriales.index', ['editoriales' => Editorial::with('libros')->orderBy('nombre')->get()]);
    }
}

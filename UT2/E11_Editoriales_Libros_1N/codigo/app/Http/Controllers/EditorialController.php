<?php

namespace App\Http\Controllers;

use App\Models\Editorial;
use Illuminate\View\View;

class EditorialController extends Controller
{
    public function index(): View
    {
        return view('editoriales.index', ['editoriales' => Editorial::with('libros')->orderBy('nombre')->get()]);
    }
}

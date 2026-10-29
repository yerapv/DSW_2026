<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

// Entrada separada a las pantallas de gestión, manteniendo un Controller sencillo.
class AdminController extends Controller
{
    /** El acceso es directo: esta práctica todavía no incluye autenticación. */
    public function index(): View
    {
        return view('admin.index');
    }
}

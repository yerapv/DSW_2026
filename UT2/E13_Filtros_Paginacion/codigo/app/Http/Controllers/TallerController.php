<?php

namespace App\Http\Controllers;

use App\Models\Taller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TallerController extends Controller
{
    public function index(Request $r): View
    {
        $talleres = Taller::query()->with('monitor')->when($r->filled('q'), fn($q) => $q->where('titulo', 'like', '%' . $r->query('q') . '%'))->when($r->filled('nivel'), fn($q) => $q->where('nivel', $r->query('nivel')))->orderBy('titulo')->paginate(5)->withQueryString();
        return view('talleres.index', compact('talleres'));
    }
}

@extends('layouts.laboratorio')
@section('laboratorio')
<h1>Alquiler audiovisual</h1>
    <form method="POST" action="/e18/alquiler/lineas">@csrf<select name="producto_id">
            @foreach ($productos as $p)
                <option value="{{ $p->id }}">{{ $p->nombre }} —
                    {{ number_format($p->precio_dia_centimos / 100, 2) }} €/día</option>
            @endforeach
        </select>
        <input type="number" name="dias" min="1" value="1"><button>Añadir</button>
    </form>
    <h2>Carrito</h2>
    <ul>
        @foreach ($carrito->lineas as $l)
            <li>{{ $l->producto->nombre }} × {{ $l->dias }} días = {{ number_format($l->precio_centimos / 100, 2) }} €
            </li>
        @endforeach
    </ul>
    <form method="POST" action="/e18/alquiler/presupuesto">@csrf<button>Generar presupuesto</button></form>
@endsection

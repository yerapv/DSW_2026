@extends('layouts.laboratorio')
@section('laboratorio')
<h1>Nueva pieza</h1>
    <form method="POST" action="/e17/piezas" enctype="multipart/form-data">@csrf<input name="nombre"><input
            name="autor"><input type="file" name="imagen" accept="image/*"><button>Guardar</button></form>
@endsection

@csrf
<label>Título <input name="titulo" value="{{ old('titulo', $libro->titulo ?? '') }}"></label>
@error('titulo')
    {{ $message }}
@enderror
<br>
<label>Autor <input name="autor" value="{{ old('autor', $libro->autor ?? '') }}"></label><br>
<label>Año <input type="number" name="anio" value="{{ old('anio', $libro->anio ?? '') }}"></label><br>
<label><input type="checkbox" name="disponible" value="1" @checked(old('disponible', $libro->disponible ?? true))>
    Disponible</label><br><button>Guardar</button>

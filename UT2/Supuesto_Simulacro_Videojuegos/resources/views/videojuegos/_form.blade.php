{{-- Este parcial se comparte entre alta y edición mediante include. --}}
{{-- old recupera lo enviado si hay errores; si no, utiliza el dato actual del juego. --}}
<label>Título
    <input type="text" name="titulo" value="{{ old('titulo', $videojuego->titulo ?? '') }}" maxlength="100" required>
</label>
<label>Precio en euros
    <input type="number" name="precio" value="{{ old('precio', $videojuego->precio ?? '') }}" min="0"
        max="999999.99" step="0.01" required>
    <small>Por ejemplo: 29.95.</small>
</label>
<label>Género
    <select name="genero_id" required>
        <option value="">Selecciona un género</option>
        @foreach ($generos as $genero)
            {{-- Cada opción envía el id; la etiqueta muestra el nombre del género. --}}
            <option value="{{ $genero->id }}" @selected((string) old('genero_id', $videojuego->genero_id ?? '') === (string) $genero->id)>{{ $genero->nombre }}</option>
        @endforeach
    </select>
</label>
{{-- En edición existe $videojuego; en el alta todavía no se ha creado ese registro. --}}
@isset($videojuego)
    <p>Portada actual:</p>
    <img src="{{ asset('storage/' . $videojuego->imagen) }}" alt="Portada actual">
@endisset
<label>Portada
    {{-- Es obligatoria al crear y opcional al editar. El servidor también valida estas reglas. --}}
    {{-- Los navegadores no permiten repoblar un campo de archivo mediante old(). --}}
    <input type="file" name="imagen" accept="image/jpeg,image/png" @required(!isset($videojuego))>
    <small>JPG o PNG, máximo 2 MB. Al editar, deja este campo vacío para conservar la portada.</small>
</label>
<button type="submit">Guardar</button>
<a href="{{ route('videojuegos.index') }}">Volver al listado</a>

<label>Título<input name="titulo" value="{{ old('titulo', $aviso->titulo ?? '') }}" maxlength="120" required></label>
<label>Contenido<textarea name="contenido" rows="5" required>{{ old('contenido', $aviso->contenido ?? '') }}</textarea></label>
<button>Guardar aviso</button>

<label>Nombre<input name="nombre" maxlength="80" value="{{ old('nombre', $metodo->nombre ?? '') }}" required></label>
<label>Código<input name="codigo" maxlength="20" value="{{ old('codigo', $metodo->codigo ?? '') }}" required><small>Código único en minúsculas, por ejemplo tarjeta o transferencia.</small></label>
<label class="check"><input type="checkbox" name="activo" value="1" @checked(old('activo', $metodo->activo ?? true))> Disponible en el pago simulado</label>
<button>Guardar método de pago</button>

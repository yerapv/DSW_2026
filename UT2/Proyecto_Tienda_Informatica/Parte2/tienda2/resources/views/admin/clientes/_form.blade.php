<label>Nombre<input name="nombre" maxlength="80" value="{{ old('nombre', $cliente->nombre ?? '') }}" required></label>
<label>Correo electrónico<input type="email" name="email" maxlength="150" value="{{ old('email', $cliente->email ?? '') }}" required></label>
<label>Dirección<input name="direccion" maxlength="255" value="{{ old('direccion', $cliente->direccion ?? '') }}"></label>
<label>Tipo<select name="tipo"><option value="">Sin indicar</option>@foreach (config('tienda.tipos_cliente') as $tipo)<option value="{{ $tipo }}" @selected(old('tipo', $cliente->tipo ?? '') === $tipo)>{{ ucfirst($tipo) }}</option>@endforeach</select></label>
<label>Edad<input type="number" name="edad" min="0" max="120" value="{{ old('edad', $cliente->edad ?? '') }}"></label>
<button>Guardar cliente</button>

<label>Nombre<input name="nombre" value="{{ old('nombre', $producto->nombre ?? '') }}" maxlength="150" required></label>
<label>Marca<input name="marca" value="{{ old('marca', $producto->marca ?? '') }}" maxlength="120" required></label>
<label>Año<input type="number" name="anio" value="{{ old('anio', $producto->anio ?? date('Y')) }}" min="2000" max="{{ date('Y') }}" required></label>
<label>Precio (€)<input type="text" name="precio" inputmode="decimal" pattern="[0-9]+([,.][0-9]{1,2})?" value="{{ old('precio', isset($producto) ? number_format($producto->precio_centimos / 100, 2, ',', '') : '') }}" placeholder="24,50" required><small>Introduce el importe en euros, por ejemplo 24,50 o 25,65.</small></label>
<label>Categoría<select name="categoria_id" required><option value="">Selecciona una categoría</option>
    @foreach ($categorias as $categoria)
        <option value="{{ $categoria->id }}" @selected(old('categoria_id', $producto->categoria_id ?? '') == $categoria->id)>{{ $categoria->nombre }}</option>
    @endforeach
</select></label>
<label>Tipo<select name="tipo">
    @foreach (\App\Enums\TipoProducto::cases() as $tipo)<option value="{{ $tipo->value }}" @selected((string) old('tipo', $producto->tipo->value ?? '') === (string) $tipo->value)>{{ $tipo->nombre() }}</option>@endforeach
</select></label>
<label>RAM en GB (solo portátil)<input type="number" name="ram_gb" min="1" max="256" value="{{ old('ram_gb', $producto->caracteristicas['ram_gb'] ?? '') }}"></label>
<label>Conexión (periférico o componente)<input name="conexion" maxlength="80" value="{{ old('conexion', $producto->caracteristicas['conexion'] ?? '') }}"></label>
{{-- @checked controla el checkbox; el FormRequest normaliza su ausencia a false. --}}
<label class="check"><input type="checkbox" name="disponible" value="1" @checked(old('disponible', $producto->disponible ?? false))> Disponible</label>
@if (isset($producto) && $producto->ruta_imagen)
    <div><p>Fotografía actual</p>@include('partials.foto-producto', ['clase' => 'product-photo-preview'])</div>
@endif
<label>Fotografía del producto (JPG, PNG o WebP, hasta 2 MB)
    <input type="file" name="imagen" accept="image/jpeg,image/png,image/webp" @required(!isset($producto))>
    @if (isset($producto))<small>Si no eliges un archivo, se conserva la fotografía actual.</small>@endif
</label>
@error('imagen')<p class="field-error">{{ $message }}</p>@enderror
<button>Guardar producto</button>

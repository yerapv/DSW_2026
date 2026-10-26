@csrf
<label>Nombre de la fotografía<input name="nombre" value="{{ old('nombre', $imagen->nombre ?? '') }}" maxlength="120" required></label>
<label>Autor<input name="autor" value="{{ old('autor', $imagen->autor ?? '') }}" maxlength="120" required></label>
@if (isset($imagen))
    <div><p>Fotografía actual</p><img class="admin-preview" src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($imagen->ruta_imagen) }}" alt="{{ $imagen->nombre }}"></div>
@endif
<label>Imagen (JPG, PNG o WebP, hasta 2 MB)
    <input type="file" name="imagen" accept="image/jpeg,image/png,image/webp" @required(!isset($imagen))>
    @if (isset($imagen))<small>Si no eliges un archivo, se conserva la fotografía actual.</small>@endif
</label>
<button>Guardar fotografía</button>

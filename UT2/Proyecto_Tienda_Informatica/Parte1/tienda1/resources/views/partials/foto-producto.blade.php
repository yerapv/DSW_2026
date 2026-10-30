@if ($producto->ruta_imagen)
{{-- La URL parte de la ruta relativa que el Controller guardó en la BD. --}}
<img class="product-photo {{ $clase ?? '' }}" src="{{ Storage::disk('almacen_imagenes')->url($producto->ruta_imagen) }}" alt="Fotografía de {{ $producto->nombre }}" loading="lazy">
@else
{{-- Los registros anteriores a la migración pueden no tener foto todavía. --}}
<div class="product-photo product-photo-placeholder {{ $clase ?? '' }}">Sin fotografía</div>
@endif

<div class="row-actions">
    @unless ($detalle ?? false)<a class="button secondary small" href="{{ route('admin.'.$recurso.'.show', $registro) }}">Ver</a>@endunless
    <a class="button secondary small" href="{{ route('admin.'.$recurso.'.edit', $registro) }}">Editar</a>
    <form method="POST" action="{{ route('admin.'.$recurso.'.destroy', $registro) }}">@csrf @method('DELETE')<button class="small danger">Eliminar</button></form>
</div>

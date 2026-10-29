<label>Estado<select name="estado" required>
    @foreach ($estados as $estado)<option value="{{ $estado->value }}" @selected((string) old('estado', $pedido->estado->value) === (string) $estado->value)>{{ $estado->nombre() }}</option>@endforeach
</select></label>

@extends('layouts.laboratorio')
@section('laboratorio')
    <h1>Nuevo vehículo</h1>
    <form class="formulario-vehiculo" method="POST" action="{{ route('e14.store') }}">
        @csrf
        <div class="vehiculo-campos">
            <div class="vehiculo-campo">
                <label for="vehiculo-nombre">Nombre</label>
                <input id="vehiculo-nombre" name="nombre" value="{{ old('nombre') }}" required
                    @error('nombre') aria-invalid="true" aria-describedby="vehiculo-nombre-error" @enderror>
                @error('nombre')
                    <span class="vehiculo-error" id="vehiculo-nombre-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="vehiculo-campo">
                <label for="vehiculo-matricula">Matrícula</label>
                <input id="vehiculo-matricula" name="matricula" value="{{ old('matricula') }}" required
                    @error('matricula') aria-invalid="true" aria-describedby="vehiculo-matricula-error" @enderror>
                @error('matricula')
                    <span class="vehiculo-error" id="vehiculo-matricula-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="vehiculo-campo">
                <label for="vehiculo-tipo">Tipo de vehículo</label>
                <select id="vehiculo-tipo" name="tipo" required
                    @error('tipo') aria-invalid="true" aria-describedby="vehiculo-tipo-error" @enderror>
                    <option value="coche" @selected(old('tipo', 'coche') === 'coche')>Coche</option>
                    <option value="moto" @selected(old('tipo') === 'moto')>Moto</option>
                    <option value="furgoneta" @selected(old('tipo') === 'furgoneta')>Furgoneta</option>
                </select>
                @error('tipo')
                    <span class="vehiculo-error" id="vehiculo-tipo-error">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <fieldset class="vehiculo-caracteristicas">
            <legend>Características</legend>
            <div class="vehiculo-campos">
                <div class="vehiculo-campo">
                    <label for="vehiculo-plazas">Plazas</label>
                    <input id="vehiculo-plazas" type="number" name="plazas" min="1" value="{{ old('plazas') }}"
                        aria-describedby="vehiculo-plazas-ayuda{{ $errors->has('plazas') ? ' vehiculo-plazas-error' : '' }}"
                        @error('plazas') aria-invalid="true" @enderror>
                    <small id="vehiculo-plazas-ayuda">Para coches y furgonetas.</small>
                    @error('plazas')
                        <span class="vehiculo-error" id="vehiculo-plazas-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="vehiculo-campo">
                    <label for="vehiculo-cilindrada">Cilindrada (cc)</label>
                    <input id="vehiculo-cilindrada" type="number" name="cilindrada" min="50" value="{{ old('cilindrada') }}"
                        aria-describedby="vehiculo-cilindrada-ayuda{{ $errors->has('cilindrada') ? ' vehiculo-cilindrada-error' : '' }}"
                        @error('cilindrada') aria-invalid="true" @enderror>
                    <small id="vehiculo-cilindrada-ayuda">Para motos.</small>
                    @error('cilindrada')
                        <span class="vehiculo-error" id="vehiculo-cilindrada-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </fieldset>
        <div class="vehiculo-acciones">
            <button type="submit">Guardar vehículo</button>
            <a href="{{ route('e14.index') }}">Volver al listado</a>
        </div>
    </form>
@endsection

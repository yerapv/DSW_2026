<div class="field">
    <label for="titulo">Título</label>
    <input id="titulo" name="titulo" type="text" maxlength="100" required value="" aria-invalid="" <p class="field-error" id="titulo-error"></p>

</div>

<div class="field">
    <label for="">Artista</label>
    <select id="" name="" required aria-invalid="{{ }}" describedby="artista-error" @enderror>
        <option value="">Selecciona un artista</option>
        @foreach ()
        <option value="{{ }}">
            {{ }}
        </option>
        @endforeach
    </select>

    <p class="field-error" id="artista-error">{{ }}</p>
    @enderror
</div>

<div class="field">
    <label for="">Sala</label>
    <select id="" name="" required aria-invalid="">
        <option value="">Selecciona una sala</option>
        @foreach ()
        <option value="">
            {{ }}
        </option>
        @endforeach
    </select>
    @error('sala_id')
    <p class="field-error" id="sala-error">{{ }}</p>
    @enderror
</div>

<div class="field">
    <label for="imagen">{{}}</label>
    <input id="imagen" name="" type="">
    <p class="help" id="imagen-ayuda">
        JPG, JPEG o PNG. Máximo 2 MB.

        Si no seleccionas una imagen, se conservará la actual.

        Tras un error, vuelve a seleccionar el archivo.
    </p>
    @error('')
    <p class="field-error" id="imagen-error">{{ }}</p>
    @enderror
</div>

<div class="form-actions">
    <button class="button" type="submit">{{ }}</button>
    <a class="button button-secondary" href="{{  }}">Volver al listado</a>
</div>

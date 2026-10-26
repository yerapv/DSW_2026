<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarProductoRequest extends FormRequest
{
    /** El panel de esta actividad tiene acceso directo, sin autenticación. */
    public function authorize(): bool
    {
        return true;
    }

    /** Exists verifica la FK; in limita el tipo antes de convertirlo a enum. */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:150'],
            'marca' => ['required', 'string', 'max:120'],
            'anio' => ['required', 'integer', 'between:2000,'.date('Y')],
            'disponible' => ['sometimes', 'boolean'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'precio' => ['bail', 'required', 'numeric', 'regex:/\A[0-9]+(?:\.[0-9]{1,2})?\z/', 'between:0.01,100000'],
            'tipo' => ['required', 'in:portatil,periferico,componente'],
            'ram_gb' => ['nullable', 'integer', 'between:1,256'],
            'conexion' => ['nullable', 'string', 'max:80'],
            // max se expresa en KB; no aceptamos SVG ni archivos ajenos a estos formatos.
            'imagen' => [$this->isMethod('post') ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /** Los checkbox desmarcados no viajan en HTML; los normalizamos a false. */
    protected function prepareForValidation(): void
    {
        $this->merge(['disponible' => $this->boolean('disponible')]);
    }

    /** Acepta coma o punto sin cambiar el texto que recupera old() si hay errores. */
    public function validationData(): array
    {
        $datos = parent::validationData();
        if (isset($datos['precio']) && is_string($datos['precio'])) {
            $datos['precio'] = str_replace(',', '.', trim($datos['precio']));
        }

        return $datos;
    }

    /** Laravel utiliza campo.regla para mostrar un mensaje acorde a este formulario. */
    public function messages(): array
    {
        return [
            'nombre.required' => 'Escribe el nombre del producto.',
            'nombre.string' => 'El nombre del producto debe ser un texto.',
            'nombre.max' => 'El nombre del producto no puede superar :max caracteres.',
            'marca.required' => 'Indica la marca del producto.',
            'marca.string' => 'La marca del producto debe ser un texto.',
            'marca.max' => 'La marca del producto no puede superar :max caracteres.',
            'anio.required' => 'Indica el año del producto.',
            'anio.integer' => 'El año del producto debe ser un número entero.',
            'anio.between' => 'El año del producto debe estar entre :min y :max.',
            'disponible.boolean' => 'La disponibilidad del producto debe estar activada o desactivada.',
            'categoria_id.required' => 'Selecciona la categoría del producto.',
            'categoria_id.exists' => 'La categoría seleccionada para el producto no existe.',
            'precio.required' => 'Indica el precio del producto en euros.',
            'precio.numeric' => 'Introduce un precio válido en euros, por ejemplo 24,50.',
            'precio.regex' => 'Escribe el precio con un máximo de dos decimales, por ejemplo 24,50.',
            'precio.between' => 'El precio debe estar entre 0,01 y 100.000,00 €.',
            'tipo.required' => 'Selecciona el tipo de producto.',
            'tipo.in' => 'El tipo de producto debe ser portátil, periférico o componente.',
            'ram_gb.integer' => 'La memoria RAM del portátil debe ser un número entero de GB.',
            'ram_gb.between' => 'La memoria RAM del portátil debe estar entre :min y :max GB.',
            'conexion.string' => 'La conexión del periférico o componente debe ser un texto.',
            'conexion.max' => 'La conexión del producto no puede superar :max caracteres.',
            'imagen.required' => 'Selecciona una fotografía para el producto.',
            'imagen.image' => 'La fotografía del producto debe ser una imagen válida.',
            'imagen.mimes' => 'La fotografía del producto debe ser JPG, PNG o WebP.',
            'imagen.max' => 'La fotografía del producto no puede superar los 2 MB.',
            'imagen.uploaded' => 'No se pudo subir la fotografía del producto. Vuelve a seleccionarla.',
        ];
    }
}

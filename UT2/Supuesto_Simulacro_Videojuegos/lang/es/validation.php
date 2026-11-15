<?php

// Traducción de las reglas utilizadas: la lógica sigue siendo la de Laravel.
return [
    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser un texto.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'numeric' => 'El campo :attribute debe ser un número.',
    'decimal' => 'El campo :attribute debe tener un máximo de dos decimales.',
    'email' => 'El campo :attribute debe contener un email válido.',
    'date' => 'El campo :attribute debe contener una fecha válida.',
    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
    'in' => 'El valor seleccionado para :attribute no es válido.',
    'exists' => 'El valor seleccionado para :attribute no existe.',
    'uploaded' => 'No se pudo subir el archivo de :attribute. Vuelve a seleccionarlo.',
    'unique' => 'Ya existe un registro con ese valor de :attribute.',
    'image' => 'El archivo :attribute debe ser una imagen.',
    'mimes' => 'El archivo :attribute debe ser de tipo: :values.',
    'max' => [
        'string' => 'El campo :attribute no puede superar :max caracteres.',
        'numeric' => 'El campo :attribute no puede superar :max.',
        'file' => 'El archivo :attribute no puede superar :max KB.',
    ],
    'min' => ['numeric' => 'El campo :attribute debe ser al menos :min.'],
    'between' => ['numeric' => 'El campo :attribute debe estar entre :min y :max.'],
    'attributes' => ['titulo' => 'título', 'precio' => 'precio', 'genero_id' => 'género', 'imagen' => 'portada'],
];

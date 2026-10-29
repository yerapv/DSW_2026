<?php

// Se leen con config('tienda.nombre'), evitando repetirlos en las vistas.
return [
    'nombre' => 'NEXIUM SOFTWARE FACTORY SL',
    'tipos_cliente' => ['particular', 'empresa', 'estudiante'],
    'montaje' => [
        'equipo_centimos' => 2500,
        'instalacion_extra_centimos' => 1000,
        'urgente_centimos' => 1500,
    ],
];

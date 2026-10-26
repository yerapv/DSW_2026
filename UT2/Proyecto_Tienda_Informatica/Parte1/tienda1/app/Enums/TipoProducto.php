<?php

namespace App\Enums;

// El valor de cada caso se guarda como texto en la base de datos.
enum TipoProducto: string
{
    case Portatil = 'portatil';
    case Periferico = 'periferico';
    case Componente = 'componente';
}

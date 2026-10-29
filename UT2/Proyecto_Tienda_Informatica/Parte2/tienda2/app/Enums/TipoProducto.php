<?php

namespace App\Enums;

enum TipoProducto: int
{
    case Portatil = 1;
    case Periferico = 2;
    case Componente = 3;

    public function nombre(): string
    {
        return match ($this) {
            self::Portatil => 'Portátil',
            self::Periferico => 'Periférico',
            self::Componente => 'Componente',
        };
    }
}

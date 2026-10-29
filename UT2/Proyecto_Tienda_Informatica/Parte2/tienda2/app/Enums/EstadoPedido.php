<?php

namespace App\Enums;

enum EstadoPedido: int
{
    case PendienteCobro = 1;
    case EnPreparacion = 2;
    case Completado = 3;
    case Cancelado = 4;

    public function nombre(): string
    {
        return match ($this) {
            self::PendienteCobro => 'Pendiente de cobro',
            self::EnPreparacion => 'En preparación',
            self::Completado => 'Completado',
            self::Cancelado => 'Cancelado',
        };
    }
}

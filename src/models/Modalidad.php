<?php

declare(strict_types=1);

namespace App\Models;

enum Modalidad: string
{
    case Escolarizada = 'escolarizada';
    case Mixta = 'mixta';
    case EnLinea = 'en_linea';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Escolarizada => 'Escolarizada',
            self::Mixta        => 'Mixta',
            self::EnLinea      => 'En línea',
        };
    }
}

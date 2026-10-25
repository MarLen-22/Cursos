<?php

declare(strict_types=1);

namespace App\Models;

enum Genero: string
{
    case Hombre = 'hombre';
    case Mujer = 'mujer';
    case NoBinario = 'no_binario';
    case PrefieroNoDecir = 'prefiero_no_decir';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Hombre          => 'Hombre',
            self::Mujer           => 'Mujer',
            self::NoBinario       => 'No binario',
            self::PrefieroNoDecir => 'Prefiero no decirlo',
        };
    }
}

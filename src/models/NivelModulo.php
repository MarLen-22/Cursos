<?php

declare(strict_types=1);

namespace App\Models;

enum NivelModulo: string
{
    case Inicial = 'inicial';
    case Intermedio = 'intermedio';
    case Avanzado = 'avanzado';

    public function etiqueta(): string
    {
        return 'Nivel ' . $this->value;
    }

    /** @return list<string> */
    public static function valores(): array
    {
        return array_map(static fn (self $n): string => $n->value, self::cases());
    }
}

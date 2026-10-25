<?php

declare(strict_types=1);

namespace App\Models;

/** Static catalogs used by profile forms and validation. */
final class Catalogo
{
    /** Country names must not contain commas (the "in:" validation rule splits on them). */
    public const PAISES = [
        'México', 'Argentina', 'Bolivia', 'Brasil', 'Canadá', 'Chile', 'Colombia', 'Costa Rica', 'Cuba',
        'Ecuador', 'El Salvador', 'España', 'Estados Unidos', 'Guatemala', 'Honduras', 'Nicaragua',
        'Panamá', 'Paraguay', 'Perú', 'Puerto Rico', 'República Dominicana', 'Uruguay', 'Venezuela', 'Otro',
    ];

    /** @return array<string, string> value => label */
    public static function opciones(string $enum): array
    {
        $result = [];
        foreach ($enum::cases() as $case) {
            $result[$case->value] = $case->etiqueta();
        }

        return $result;
    }

    public static function valores(string $enum): string
    {
        return implode(',', array_map(static fn ($c): string => $c->value, $enum::cases()));
    }
}

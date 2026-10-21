<?php

declare(strict_types=1);

namespace App\Models;

/** Visual tone of a module; maps to the existing palette (cyan, indigo, purple, sky). */
enum ColorModulo: string
{
    case Cyan = 'cyan';
    case Indigo = 'indigo';
    case Purple = 'purple';
    case Sky = 'sky';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Cyan   => 'Cian',
            self::Indigo => 'Índigo',
            self::Purple => 'Púrpura',
            self::Sky    => 'Cielo',
        };
    }

    /** @return list<string> */
    public static function valores(): array
    {
        return array_map(static fn (self $c): string => $c->value, self::cases());
    }
}

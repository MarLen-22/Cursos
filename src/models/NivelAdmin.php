<?php

declare(strict_types=1);

namespace App\Models;

enum NivelAdmin: string
{
    case Admin = 'admin';
    case SuperAdmin = 'superadmin';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Admin      => 'Administrador',
            self::SuperAdmin => 'Superadministrador',
        };
    }
}

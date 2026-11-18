<?php

declare(strict_types=1);

namespace App\Models;

enum NivelAdmin: string
{
    case JefeCurso = 'jefe_curso';
    case Admin = 'admin';
    case SuperAdmin = 'superadmin';

    public function etiqueta(): string
    {
        return match ($this) {
            self::JefeCurso  => 'Jefe de Curso',
            self::Admin      => 'Administrador',
            self::SuperAdmin => 'Superadministrador',
        };
    }

    public function icono(): string
    {
        return match ($this) {
            self::JefeCurso  => '👨‍🏫',
            self::Admin      => '🛡️',
            self::SuperAdmin => '👑',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::SuperAdmin => 'bg-cyan text-navy',
            self::Admin      => 'bg-navy text-cyan',
            self::JefeCurso  => 'bg-indigo-subtle text-indigo border border-indigo-subtle',
        };
    }
}

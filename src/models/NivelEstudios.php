<?php

declare(strict_types=1);

namespace App\Models;

enum NivelEstudios: string
{
    case Bachillerato = 'bachillerato';
    case Tsu = 'tsu';
    case Licenciatura = 'licenciatura';
    case Ingenieria = 'ingenieria';
    case Maestria = 'maestria';
    case Doctorado = 'doctorado';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Bachillerato => 'Bachillerato',
            self::Tsu          => 'Técnico Superior Universitario',
            self::Licenciatura => 'Licenciatura',
            self::Ingenieria   => 'Ingeniería',
            self::Maestria     => 'Maestría',
            self::Doctorado    => 'Doctorado',
        };
    }
}

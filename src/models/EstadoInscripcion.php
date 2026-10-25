<?php

declare(strict_types=1);

namespace App\Models;

enum EstadoInscripcion: string
{
    case Inscrito = 'inscrito';
    case EnCurso = 'en_curso';
    case Finalizado = 'finalizado';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Inscrito   => 'Inscrito',
            self::EnCurso    => 'En curso',
            self::Finalizado => 'Finalizado',
        };
    }
}

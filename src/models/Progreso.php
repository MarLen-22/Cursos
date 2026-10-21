<?php

declare(strict_types=1);

namespace App\Models;

final class Progreso
{
    public function __construct(
        public readonly int $id,
        public readonly int $idUsuario,
        public readonly string $indicador,
        public readonly int $porcentaje,
        public readonly string $actualizadoEn,
    ) {
    }

    /** @param array<string, mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id_progreso'],
            (int) $row['id_usuario'],
            (string) $row['indicador'],
            (int) $row['porcentaje'],
            (string) $row['actualizado_en'],
        );
    }
}

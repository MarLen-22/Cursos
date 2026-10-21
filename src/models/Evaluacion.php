<?php

declare(strict_types=1);

namespace App\Models;

final class Evaluacion
{
    public function __construct(
        public readonly int $id,
        public readonly string $tipo,
        public readonly string $titulo,
        public readonly string $etapa,
        public readonly string $descripcion,
        public readonly string $icono,
        public readonly int $orden,
    ) {
    }

    /** @param array<string, mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id_evaluacion'],
            (string) $row['tipo'],
            (string) $row['titulo'],
            (string) $row['etapa'],
            (string) $row['descripcion'],
            (string) $row['icono'],
            (int) $row['orden'],
        );
    }
}

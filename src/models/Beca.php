<?php

declare(strict_types=1);

namespace App\Models;

final class Beca
{
    public function __construct(
        public readonly int $id,
        public readonly string $clave,
        public readonly string $institucion,
        public readonly string $siglas,
        public readonly string $titulo,
        public readonly string $descripcion,
        public readonly string $url,
        public readonly string $estado,
        public readonly string $icono,
        public readonly int $orden,
        public readonly bool $activo,
        public readonly string $actualizadoEn,
    ) {
    }

    /** @param array<string, mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id_beca'],
            (string) $row['clave'],
            (string) $row['institucion'],
            (string) $row['siglas'],
            (string) $row['titulo'],
            (string) $row['descripcion'],
            (string) $row['url'],
            (string) $row['estado'],
            (string) $row['icono'],
            (int) $row['orden'],
            (bool) $row['activo'],
            (string) $row['actualizado_en'],
        );
    }
}


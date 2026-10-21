<?php

declare(strict_types=1);

namespace App\Models;

final class Modulo
{
    public function __construct(
        public readonly int $id,
        public readonly string $slug,
        public readonly string $titulo,
        public readonly string $descripcion,
        public readonly ?string $contenido,
        public readonly string $icono,
        public readonly ColorModulo $color,
        public readonly NivelModulo $nivel,
        public readonly int $orden,
        public readonly bool $activo,
        public readonly string $actualizadoEn,
    ) {
    }

    /** @param array<string, mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id_modulo'],
            (string) $row['slug'],
            (string) $row['titulo'],
            (string) $row['descripcion'],
            $row['contenido'] !== null ? (string) $row['contenido'] : null,
            (string) $row['icono'],
            ColorModulo::from((string) $row['color']),
            NivelModulo::from((string) $row['nivel']),
            (int) $row['orden'],
            (bool) $row['activo'],
            (string) $row['actualizado_en'],
        );
    }
}

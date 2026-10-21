<?php

declare(strict_types=1);

namespace App\Models;

final class Administrador
{
    public function __construct(
        public readonly int $id,
        public readonly int $idUsuario,
        public readonly NivelAdmin $nivel,
        public readonly ?int $otorgadoPor,
        public readonly string $creadoEn,
    ) {
    }

    /** @param array<string, mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id_administrador'],
            (int) $row['id_usuario'],
            NivelAdmin::from((string) $row['nivel']),
            $row['otorgado_por'] !== null ? (int) $row['otorgado_por'] : null,
            (string) $row['creado_en'],
        );
    }
}

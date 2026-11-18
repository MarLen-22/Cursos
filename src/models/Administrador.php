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
        public readonly ?string $cargo = null,
        public readonly ?string $departamento = null,
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
            isset($row['cargo']) && $row['cargo'] !== '' ? (string) $row['cargo'] : null,
            isset($row['departamento']) && $row['departamento'] !== '' ? (string) $row['departamento'] : null,
        );
    }
}

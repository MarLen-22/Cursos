<?php

declare(strict_types=1);

namespace App\Models;

final class EtapaAbp
{
    public function __construct(
        public readonly int $id,
        public readonly int $orden,
        public readonly string $nombre,
        public readonly string $descripcion,
    ) {
    }

    /** @param array<string, mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id_etapa'],
            (int) $row['orden'],
            (string) $row['nombre'],
            (string) $row['descripcion'],
        );
    }
}

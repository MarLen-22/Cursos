<?php

declare(strict_types=1);

namespace App\Models;

/** Read model for the admin users table (user + optional admin level). */
final class UsuarioResumen
{
    public function __construct(
        public readonly int $id,
        public readonly string $nombre,
        public readonly string $email,
        public readonly bool $activo,
        public readonly ?string $ultimoAcceso,
        public readonly string $creadoEn,
        public readonly ?NivelAdmin $nivel,
    ) {
    }

    /** @param array<string, mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id_usuario'],
            (string) $row['nombre'],
            (string) $row['email'],
            (bool) $row['activo'],
            $row['ultimo_acceso'] !== null ? (string) $row['ultimo_acceso'] : null,
            (string) $row['creado_en'],
            $row['nivel'] !== null ? NivelAdmin::from((string) $row['nivel']) : null,
        );
    }

    public function esAdministrador(): bool
    {
        return $this->nivel !== null;
    }
}

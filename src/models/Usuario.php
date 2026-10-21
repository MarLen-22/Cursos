<?php

declare(strict_types=1);

namespace App\Models;

final class Usuario
{
    public function __construct(
        public readonly int $id,
        public readonly string $nombre,
        public readonly string $email,
        public readonly string $passwordHash,
        public readonly bool $activo,
        public readonly bool $bloqueado,
        public readonly ?string $ultimoAcceso,
        public readonly string $creadoEn,
    ) {
    }

    /** @param array<string, mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id_usuario'],
            (string) $row['nombre'],
            (string) $row['email'],
            (string) $row['password_hash'],
            (bool) $row['activo'],
            (bool) $row['bloqueado'],
            $row['ultimo_acceso'] !== null ? (string) $row['ultimo_acceso'] : null,
            (string) $row['creado_en'],
        );
    }

    public function iniciales(): string
    {
        $partes = preg_split('/\s+/u', trim($this->nombre)) ?: [];
        $iniciales = '';
        foreach (array_slice($partes, 0, 2) as $parte) {
            $iniciales .= mb_strtoupper(mb_substr($parte, 0, 1));
        }

        return $iniciales !== '' ? $iniciales : '?';
    }
}

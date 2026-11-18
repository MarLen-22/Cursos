<?php

declare(strict_types=1);

namespace App\Models;

/** Read model for the admin users table (user + optional admin level + academic/profile summary). */
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
        public readonly ?string $numeroControl = null,
        public readonly ?string $telefono = null,
        public readonly ?string $carrera = null,
        public readonly ?int $semestre = null,
        public readonly ?string $institucion = null,
        public readonly ?string $cargo = null,
        public readonly ?string $departamento = null,
        public readonly int $totalInscripciones = 0,
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
            $row['nivel'] !== null ? NivelAdmin::tryFrom((string) $row['nivel']) : null,
            isset($row['numero_control']) && $row['numero_control'] !== '' ? (string) $row['numero_control'] : null,
            isset($row['telefono']) && $row['telefono'] !== '' ? (string) $row['telefono'] : null,
            isset($row['carrera']) && $row['carrera'] !== '' ? (string) $row['carrera'] : null,
            isset($row['semestre']) && $row['semestre'] !== null ? (int) $row['semestre'] : null,
            isset($row['institucion']) && $row['institucion'] !== '' ? (string) $row['institucion'] : null,
            isset($row['cargo']) && $row['cargo'] !== '' ? (string) $row['cargo'] : null,
            isset($row['departamento']) && $row['departamento'] !== '' ? (string) $row['departamento'] : null,
            isset($row['total_inscripciones']) ? (int) $row['total_inscripciones'] : 0,
        );
    }

    public function esAdministrador(): bool
    {
        return $this->nivel !== null;
    }

    public function codigoRol(): string
    {
        return $this->nivel !== null ? $this->nivel->value : 'estudiante';
    }

    public function etiquetaRol(): string
    {
        return $this->nivel !== null ? $this->nivel->etiqueta() : 'Estudiante';
    }

    public function iniciales(): string
    {
        $partes = preg_split('/\s+/u', trim($this->nombre)) ?: [];
        $primera = isset($partes[0]) ? mb_substr($partes[0], 0, 1) : 'U';
        $segunda = isset($partes[1]) ? mb_substr($partes[1], 0, 1) : '';

        return mb_strtoupper($primera . $segunda);
    }
}

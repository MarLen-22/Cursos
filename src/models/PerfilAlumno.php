<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Student profile. Values are kept as nullable strings (as stored) so the
 * same object feeds both the read-only view and the edit form.
 */
final class PerfilAlumno
{
    /** Editable columns, in table order. */
    public const CAMPOS = [
        'numero_control', 'telefono', 'pais_residencia', 'nacionalidad', 'fecha_nacimiento', 'genero',
        'email_alterno', 'ciudad', 'estado_residencia', 'codigo_postal',
        'contacto_emergencia_nombre', 'contacto_emergencia_telefono',
        'institucion', 'carrera', 'semestre', 'modalidad',
        'nivel_estudios', 'area_estudios', 'nota_media',
    ];

    /** Fields counted for the "profile completed" meter. */
    private const CAMPOS_PROGRESO = [
        'numero_control', 'telefono', 'pais_residencia', 'fecha_nacimiento', 'genero',
        'ciudad', 'estado_residencia', 'institucion', 'carrera', 'semestre',
        'nivel_estudios', 'area_estudios', 'nota_media',
    ];

    /** @param array<string, ?string> $datos */
    public function __construct(private readonly array $datos = [])
    {
    }

    /** @param array<string, mixed> $row */
    public static function fromRow(array $row): self
    {
        $datos = [];
        foreach (self::CAMPOS as $campo) {
            $valor = $row[$campo] ?? null;
            $datos[$campo] = $valor !== null && $valor !== '' ? (string) $valor : null;
        }

        return new self($datos);
    }

    public function get(string $campo): ?string
    {
        return $this->datos[$campo] ?? null;
    }

    public function genero(): ?Genero
    {
        return Genero::tryFrom((string) $this->get('genero'));
    }

    public function nivelEstudios(): ?NivelEstudios
    {
        return NivelEstudios::tryFrom((string) $this->get('nivel_estudios'));
    }

    public function modalidad(): ?Modalidad
    {
        return Modalidad::tryFrom((string) $this->get('modalidad'));
    }

    public function porcentajeCompletado(): int
    {
        $llenos = count(array_filter(self::CAMPOS_PROGRESO, fn (string $c): bool => $this->get($c) !== null));

        return (int) round($llenos * 100 / count(self::CAMPOS_PROGRESO));
    }
}

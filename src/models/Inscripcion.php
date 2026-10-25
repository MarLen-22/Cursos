<?php

declare(strict_types=1);

namespace App\Models;

/** Enrollment row joined with its module, as shown in "Mi aprendizaje". */
final class Inscripcion
{
    public const TIPO_CONTENIDO = 'Módulo interactivo';

    public function __construct(
        public readonly int $id,
        public readonly int $idModulo,
        public readonly string $slug,
        public readonly string $titulo,
        public readonly string $icono,
        public readonly ColorModulo $color,
        public readonly NivelModulo $nivel,
        public readonly EstadoInscripcion $estado,
        public readonly int $avance,
        public readonly string $inscritoEn,
        public readonly ?string $finalizadoEn,
    ) {
    }

    /** @param array<string, mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id_inscripcion'],
            (int) $row['id_modulo'],
            (string) $row['slug'],
            (string) $row['titulo'],
            (string) $row['icono'],
            ColorModulo::from((string) $row['color']),
            NivelModulo::from((string) $row['nivel']),
            EstadoInscripcion::from((string) $row['estado']),
            (int) $row['avance'],
            (string) $row['inscrito_en'],
            $row['finalizado_en'] !== null ? (string) $row['finalizado_en'] : null,
        );
    }

    public function finalizado(): bool
    {
        return $this->estado === EstadoInscripcion::Finalizado;
    }
}

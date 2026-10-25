<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class PerfilAlumnoRepository
{
    public function __construct(private readonly PDO $db)
    {
    }

    /** Returns an empty profile when the student has not filled anything yet. */
    public function findByUsuario(int $idUsuario): PerfilAlumno
    {
        $stmt = $this->db->prepare(
            'SELECT ' . implode(', ', PerfilAlumno::CAMPOS) . ' FROM perfil_alumno WHERE id_usuario = :id'
        );
        $stmt->execute(['id' => $idUsuario]);
        $row = $stmt->fetch();

        return $row !== false ? PerfilAlumno::fromRow($row) : new PerfilAlumno();
    }

    public function numeroControlExists(string $numeroControl, int $exceptUsuario = 0): bool
    {
        $stmt = $this->db->prepare(
            'SELECT 1 FROM perfil_alumno WHERE numero_control = :nc AND id_usuario <> :id'
        );
        $stmt->execute(['nc' => $numeroControl, 'id' => $exceptUsuario]);

        return $stmt->fetchColumn() !== false;
    }

    /**
     * Inserts or updates only the given columns (unknown keys are ignored).
     * Empty strings are stored as NULL.
     *
     * @param array<string, string> $datos
     */
    public function guardar(int $idUsuario, array $datos): void
    {
        $datos = array_intersect_key($datos, array_flip(PerfilAlumno::CAMPOS));
        if ($datos === []) {
            return;
        }

        $columnas = array_keys($datos);
        $placeholders = array_map(static fn (string $c): string => ':' . $c, $columnas);
        $updates = array_map(static fn (string $c): string => "{$c} = VALUES({$c})", $columnas);

        $stmt = $this->db->prepare(sprintf(
            'INSERT INTO perfil_alumno (id_usuario, %s) VALUES (:id_usuario, %s) ON DUPLICATE KEY UPDATE %s',
            implode(', ', $columnas),
            implode(', ', $placeholders),
            implode(', ', $updates),
        ));

        $params = ['id_usuario' => $idUsuario];
        foreach ($datos as $columna => $valor) {
            $params[$columna] = $valor === '' ? null : $valor;
        }
        $stmt->execute($params);
    }
}

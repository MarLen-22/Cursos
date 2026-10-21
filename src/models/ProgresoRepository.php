<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class ProgresoRepository
{
    private const COLUMNS = 'id_progreso, id_usuario, indicador, porcentaje, actualizado_en';

    public function __construct(private readonly PDO $db)
    {
    }

    /**
     * @return array<string, int> Returns associative array indicator => percentage
     */
    public function getForUsuario(int $idUsuario): array
    {
        $stmt = $this->db->prepare(
            'SELECT indicador, porcentaje FROM progreso WHERE id_usuario = :id'
        );
        $stmt->execute(['id' => $idUsuario]);

        $rows = $stmt->fetchAll();
        $defaults = [
            'comprension'  => 78,
            'argumentacion' => 64,
            'aplicacion'   => 71,
        ];

        foreach ($rows as $row) {
            $defaults[$row['indicador']] = (int) $row['porcentaje'];
        }

        return $defaults;
    }

    public function setProgreso(int $idUsuario, string $indicador, int $porcentaje): void
    {
        $porcentaje = max(0, min(100, $porcentaje));
        $stmt = $this->db->prepare(
            'INSERT INTO progreso (id_usuario, indicador, porcentaje)
             VALUES (:uid, :indicador, :porcentaje)
             ON DUPLICATE KEY UPDATE porcentaje = :porcentaje_up'
        );
        $stmt->execute([
            'uid'           => $idUsuario,
            'indicador'     => $indicador,
            'porcentaje'    => $porcentaje,
            'porcentaje_up' => $porcentaje,
        ]);
    }
}

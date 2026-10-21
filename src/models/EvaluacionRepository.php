<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class EvaluacionRepository
{
    private const COLUMNS = 'id_evaluacion, tipo, titulo, etapa, descripcion, icono, orden';

    public function __construct(private readonly PDO $db)
    {
    }

    /** @return list<Evaluacion> */
    public function listAll(): array
    {
        $stmt = $this->db->query('SELECT ' . self::COLUMNS . ' FROM evaluacion ORDER BY orden ASC');

        return array_map(Evaluacion::fromRow(...), $stmt->fetchAll());
    }

    public function findByTipo(string $tipo): ?Evaluacion
    {
        $stmt = $this->db->prepare('SELECT ' . self::COLUMNS . ' FROM evaluacion WHERE tipo = :tipo');
        $stmt->execute(['tipo' => $tipo]);
        $row = $stmt->fetch();

        return $row !== false ? Evaluacion::fromRow($row) : null;
    }
}

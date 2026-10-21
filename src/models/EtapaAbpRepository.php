<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class EtapaAbpRepository
{
    private const COLUMNS = 'id_etapa, orden, nombre, descripcion';

    public function __construct(private readonly PDO $db)
    {
    }

    /** @return list<EtapaAbp> */
    public function listAll(): array
    {
        $stmt = $this->db->query('SELECT ' . self::COLUMNS . ' FROM etapa_abp ORDER BY orden ASC');

        return array_map(EtapaAbp::fromRow(...), $stmt->fetchAll());
    }
}

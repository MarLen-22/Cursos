<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class BecaRepository
{
    private const COLUMNS = 'id_beca, clave, institucion, siglas, titulo, descripcion, url, estado, icono, orden, activo, actualizado_en';

    public function __construct(private readonly PDO $db)
    {
    }

    /** @return list<Beca> */
    public function listActive(): array
    {
        $stmt = $this->db->query('SELECT ' . self::COLUMNS . ' FROM beca WHERE activo = 1 ORDER BY orden ASC, id_beca ASC');

        return array_map(Beca::fromRow(...), $stmt->fetchAll());
    }

    /** @return list<Beca> */
    public function listAll(): array
    {
        $stmt = $this->db->query('SELECT ' . self::COLUMNS . ' FROM beca ORDER BY orden ASC, id_beca ASC');

        return array_map(Beca::fromRow(...), $stmt->fetchAll());
    }

    public function findById(int $id): ?Beca
    {
        $stmt = $this->db->prepare('SELECT ' . self::COLUMNS . ' FROM beca WHERE id_beca = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row !== false ? Beca::fromRow($row) : null;
    }

    /** @param array<string, mixed> $datos */
    public function create(array $datos): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO beca (clave, institucion, siglas, titulo, descripcion, url, estado, icono, orden, activo)
             VALUES (:clave, :institucion, :siglas, :titulo, :descripcion, :url, :estado, :icono, :orden, :activo)'
        );
        $stmt->execute([
            'clave'       => $datos['clave'],
            'institucion' => $datos['institucion'],
            'siglas'      => $datos['siglas'],
            'titulo'      => $datos['titulo'],
            'descripcion' => $datos['descripcion'],
            'url'         => $datos['url'],
            'estado'      => $datos['estado'],
            'icono'       => $datos['icono'],
            'orden'       => (int) $datos['orden'],
            'activo'      => (int) $datos['activo'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    /** @param array<string, mixed> $datos */
    public function update(int $id, array $datos): void
    {
        $stmt = $this->db->prepare(
            'UPDATE beca
                SET institucion = :institucion,
                    siglas = :siglas,
                    titulo = :titulo,
                    descripcion = :descripcion,
                    url = :url,
                    estado = :estado,
                    icono = :icono,
                    orden = :orden,
                    activo = :activo
              WHERE id_beca = :id'
        );
        $stmt->execute([
            'institucion' => $datos['institucion'],
            'siglas'      => $datos['siglas'],
            'titulo'      => $datos['titulo'],
            'descripcion' => $datos['descripcion'],
            'url'         => $datos['url'],
            'estado'      => $datos['estado'],
            'icono'       => $datos['icono'],
            'orden'       => (int) $datos['orden'],
            'activo'      => (int) $datos['activo'],
            'id'          => $id,
        ]);
    }

    public function setActivo(int $id, bool $activo): void
    {
        $stmt = $this->db->prepare('UPDATE beca SET activo = :activo WHERE id_beca = :id');
        $stmt->execute(['activo' => (int) $activo, 'id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM beca WHERE id_beca = :id');
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() === 1;
    }
}


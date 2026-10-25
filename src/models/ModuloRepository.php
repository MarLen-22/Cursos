<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class ModuloRepository
{
    private const COLUMNS = 'id_modulo, slug, titulo, descripcion, contenido, icono, color, nivel, orden, activo, actualizado_en';

    public function __construct(private readonly PDO $db)
    {
    }

    /** @return list<Modulo> */
    public function listActive(): array
    {
        $stmt = $this->db->query('SELECT ' . self::COLUMNS . ' FROM modulo WHERE activo = 1 ORDER BY orden, titulo');

        return array_map(Modulo::fromRow(...), $stmt->fetchAll());
    }

    /** @return list<Modulo> */
    public function listAll(): array
    {
        $stmt = $this->db->query('SELECT ' . self::COLUMNS . ' FROM modulo ORDER BY orden, titulo');

        return array_map(Modulo::fromRow(...), $stmt->fetchAll());
    }

    /** @return list<Modulo> Active modules whose title, description or content match the term. */
    public function search(string $term): array
    {
        $like = '%' . addcslashes($term, '%_\\') . '%';
        $stmt = $this->db->prepare(
            'SELECT ' . self::COLUMNS . ' FROM modulo
              WHERE activo = 1 AND (titulo LIKE :t1 OR descripcion LIKE :t2 OR contenido LIKE :t3)
           ORDER BY (titulo LIKE :t4) DESC, orden, titulo
              LIMIT 20'
        );
        $stmt->execute(['t1' => $like, 't2' => $like, 't3' => $like, 't4' => $like]);

        return array_map(Modulo::fromRow(...), $stmt->fetchAll());
    }

    public function findActiveBySlug(string $slug): ?Modulo
    {
        $stmt = $this->db->prepare('SELECT ' . self::COLUMNS . ' FROM modulo WHERE slug = :slug AND activo = 1');
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();

        return $row !== false ? Modulo::fromRow($row) : null;
    }

    public function findById(int $id): ?Modulo
    {
        $stmt = $this->db->prepare('SELECT ' . self::COLUMNS . ' FROM modulo WHERE id_modulo = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row !== false ? Modulo::fromRow($row) : null;
    }

    public function slugExists(string $slug, ?int $exceptId = null): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM modulo WHERE slug = :slug AND id_modulo <> :id');
        $stmt->execute(['slug' => $slug, 'id' => $exceptId ?? 0]);

        return $stmt->fetchColumn() !== false;
    }

    public function create(ModuloDatos $d): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO modulo (slug, titulo, descripcion, contenido, icono, color, nivel, orden, activo)
             VALUES (:slug, :titulo, :descripcion, :contenido, :icono, :color, :nivel, :orden, :activo)'
        );
        $stmt->execute($this->params($d));

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, ModuloDatos $d): void
    {
        $stmt = $this->db->prepare(
            'UPDATE modulo
                SET slug = :slug, titulo = :titulo, descripcion = :descripcion, contenido = :contenido,
                    icono = :icono, color = :color, nivel = :nivel, orden = :orden, activo = :activo
              WHERE id_modulo = :id'
        );
        $stmt->execute($this->params($d) + ['id' => $id]);
    }

    public function setActivo(int $id, bool $activo): void
    {
        $stmt = $this->db->prepare('UPDATE modulo SET activo = :activo WHERE id_modulo = :id');
        $stmt->execute(['activo' => (int) $activo, 'id' => $id]);
    }

    public function countActive(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM modulo WHERE activo = 1')->fetchColumn();
    }

    /** @return array<string, string|int|null> */
    private function params(ModuloDatos $d): array
    {
        return [
            'slug'        => $d->slug,
            'titulo'      => $d->titulo,
            'descripcion' => $d->descripcion,
            'contenido'   => $d->contenido,
            'icono'       => $d->icono,
            'color'       => $d->color->value,
            'nivel'       => $d->nivel->value,
            'orden'       => $d->orden,
            'activo'      => (int) $d->activo,
        ];
    }
}

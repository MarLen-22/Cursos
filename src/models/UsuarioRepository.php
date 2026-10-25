<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class UsuarioRepository
{
    private const COLUMNS = 'id_usuario, nombre, email, password_hash, activo, ultimo_acceso, creado_en,
        (bloqueado_hasta IS NOT NULL AND bloqueado_hasta > NOW()) AS bloqueado';

    public function __construct(private readonly PDO $db)
    {
    }

    public function findById(int $id): ?Usuario
    {
        $stmt = $this->db->prepare('SELECT ' . self::COLUMNS . ' FROM usuario WHERE id_usuario = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row !== false ? Usuario::fromRow($row) : null;
    }

    public function findByEmail(string $email): ?Usuario
    {
        $stmt = $this->db->prepare('SELECT ' . self::COLUMNS . ' FROM usuario WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        return $row !== false ? Usuario::fromRow($row) : null;
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM usuario WHERE email = :email');
        $stmt->execute(['email' => $email]);

        return $stmt->fetchColumn() !== false;
    }

    public function create(string $nombre, string $email, string $passwordHash): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO usuario (nombre, email, password_hash) VALUES (:nombre, :email, :hash)'
        );
        $stmt->execute(['nombre' => $nombre, 'email' => $email, 'hash' => $passwordHash]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Increments failed attempts and locks the account once the limit is
     * reached. bloqueado_hasta is assigned first so both expressions read
     * the original counter (MySQL evaluates SET left to right).
     */
    public function registerFailedLogin(int $id, int $maxAttempts, int $lockMinutes): void
    {
        $stmt = $this->db->prepare(
            'UPDATE usuario
                SET bloqueado_hasta = IF(intentos_fallidos + 1 >= :max1, NOW() + INTERVAL :minutes MINUTE, bloqueado_hasta),
                    intentos_fallidos = IF(intentos_fallidos + 1 >= :max2, 0, intentos_fallidos + 1)
              WHERE id_usuario = :id'
        );
        $stmt->bindValue('max1', $maxAttempts, PDO::PARAM_INT);
        $stmt->bindValue('max2', $maxAttempts, PDO::PARAM_INT);
        $stmt->bindValue('minutes', $lockMinutes, PDO::PARAM_INT);
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function registerSuccessfulLogin(int $id): void
    {
        $stmt = $this->db->prepare(
            'UPDATE usuario SET intentos_fallidos = 0, bloqueado_hasta = NULL, ultimo_acceso = NOW()
              WHERE id_usuario = :id'
        );
        $stmt->execute(['id' => $id]);
    }

    public function updatePasswordHash(int $id, string $hash): void
    {
        $stmt = $this->db->prepare('UPDATE usuario SET password_hash = :hash WHERE id_usuario = :id');
        $stmt->execute(['hash' => $hash, 'id' => $id]);
    }

    public function updateNombre(int $id, string $nombre): void
    {
        $stmt = $this->db->prepare('UPDATE usuario SET nombre = :nombre WHERE id_usuario = :id');
        $stmt->execute(['nombre' => $nombre, 'id' => $id]);
    }

    public function setActivo(int $id, bool $activo): void
    {
        $stmt = $this->db->prepare('UPDATE usuario SET activo = :activo WHERE id_usuario = :id');
        $stmt->execute(['activo' => (int) $activo, 'id' => $id]);
    }

    public function countAll(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM usuario')->fetchColumn();
    }

    /** @return list<UsuarioResumen> */
    public function paginateWithRole(int $page, int $perPage): array
    {
        $stmt = $this->db->prepare(
            "SELECT u.id_usuario, u.nombre, u.email, u.activo, u.ultimo_acceso, u.creado_en, a.nivel
               FROM usuario u
          LEFT JOIN administrador a ON a.id_usuario = u.id_usuario
           ORDER BY CASE a.nivel WHEN 'superadmin' THEN 0 WHEN 'admin' THEN 1 ELSE 2 END, u.nombre
              LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue('offset', max(0, ($page - 1) * $perPage), PDO::PARAM_INT);
        $stmt->execute();

        return array_map(UsuarioResumen::fromRow(...), $stmt->fetchAll());
    }
}

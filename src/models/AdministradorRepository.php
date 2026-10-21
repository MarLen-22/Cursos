<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class AdministradorRepository
{
    public function __construct(private readonly PDO $db)
    {
    }

    public function findByUsuario(int $idUsuario): ?Administrador
    {
        $stmt = $this->db->prepare(
            'SELECT id_administrador, id_usuario, nivel, otorgado_por, creado_en
               FROM administrador WHERE id_usuario = :id'
        );
        $stmt->execute(['id' => $idUsuario]);
        $row = $stmt->fetch();

        return $row !== false ? Administrador::fromRow($row) : null;
    }

    public function grant(int $idUsuario, NivelAdmin $nivel, ?int $otorgadoPor): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO administrador (id_usuario, nivel, otorgado_por) VALUES (:usuario, :nivel, :otorgante)'
        );
        $stmt->execute(['usuario' => $idUsuario, 'nivel' => $nivel->value, 'otorgante' => $otorgadoPor]);
    }

    public function promote(int $idUsuario, NivelAdmin $nivel): void
    {
        $stmt = $this->db->prepare('UPDATE administrador SET nivel = :nivel WHERE id_usuario = :usuario');
        $stmt->execute(['nivel' => $nivel->value, 'usuario' => $idUsuario]);
    }

    /** Only regular admins can be revoked; superadmins are protected at the SQL level too. */
    public function revoke(int $idUsuario): bool
    {
        $stmt = $this->db->prepare("DELETE FROM administrador WHERE id_usuario = :usuario AND nivel = 'admin'");
        $stmt->execute(['usuario' => $idUsuario]);

        return $stmt->rowCount() === 1;
    }

    public function countAll(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM administrador')->fetchColumn();
    }
}

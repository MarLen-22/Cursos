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
            'SELECT id_administrador, id_usuario, nivel, cargo, departamento, otorgado_por, creado_en
               FROM administrador WHERE id_usuario = :id'
        );
        $stmt->execute(['id' => $idUsuario]);
        $row = $stmt->fetch();

        return $row !== false ? Administrador::fromRow($row) : null;
    }

    public function grant(
        int $idUsuario,
        NivelAdmin $nivel,
        ?int $otorgadoPor,
        ?string $cargo = null,
        ?string $departamento = null,
    ): void {
        $stmt = $this->db->prepare(
            'INSERT INTO administrador (id_usuario, nivel, cargo, departamento, otorgado_por)
             VALUES (:usuario, :nivel, :cargo, :departamento, :otorgante)
             ON DUPLICATE KEY UPDATE
                nivel = VALUES(nivel),
                cargo = COALESCE(VALUES(cargo), cargo),
                departamento = COALESCE(VALUES(departamento), departamento),
                otorgado_por = COALESCE(VALUES(otorgado_por), otorgado_por)'
        );
        $stmt->execute([
            'usuario'      => $idUsuario,
            'nivel'        => $nivel->value,
            'cargo'        => $cargo !== '' ? $cargo : null,
            'departamento' => $departamento !== '' ? $departamento : null,
            'otorgante'    => $otorgadoPor,
        ]);
    }

    public function promote(
        int $idUsuario,
        NivelAdmin $nivel,
        ?string $cargo = null,
        ?string $departamento = null,
    ): void {
        $stmt = $this->db->prepare(
            'UPDATE administrador
                SET nivel = :nivel,
                    cargo = COALESCE(:cargo, cargo),
                    departamento = COALESCE(:departamento, departamento)
              WHERE id_usuario = :usuario'
        );
        $stmt->execute([
            'nivel'        => $nivel->value,
            'cargo'        => $cargo !== '' ? $cargo : null,
            'departamento' => $departamento !== '' ? $departamento : null,
            'usuario'      => $idUsuario,
        ]);
    }

    /** Revokes admin or jefe_curso permissions (superadmins are protected unless force is true). */
    public function revoke(int $idUsuario, bool $allowSuperAdmin = false): bool
    {
        $sql = $allowSuperAdmin
            ? 'DELETE FROM administrador WHERE id_usuario = :usuario'
            : "DELETE FROM administrador WHERE id_usuario = :usuario AND nivel <> 'superadmin'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['usuario' => $idUsuario]);

        return $stmt->rowCount() === 1;
    }

    public function countAll(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM administrador')->fetchColumn();
    }

    /** @return array{superadmin:int, admin:int, jefe_curso:int} */
    public function countByNivel(): array
    {
        $counts = ['superadmin' => 0, 'admin' => 0, 'jefe_curso' => 0];
        $rows = $this->db->query('SELECT nivel, COUNT(*) AS total FROM administrador GROUP BY nivel')->fetchAll();
        foreach ($rows as $r) {
            $k = (string) $r['nivel'];
            if (isset($counts[$k])) {
                $counts[$k] = (int) $r['total'];
            }
        }

        return $counts;
    }
}

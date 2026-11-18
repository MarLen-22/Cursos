<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class InscripcionRepository
{
    private const SELECT = 'SELECT i.id_inscripcion, i.id_modulo, i.estado, i.avance, i.inscrito_en, i.finalizado_en,
                                   m.slug, m.titulo, m.icono, m.color, m.nivel
                              FROM inscripcion i
                              JOIN modulo m ON m.id_modulo = i.id_modulo';

    public function __construct(private readonly PDO $db)
    {
    }

    /** @return list<Inscripcion> Active first, then finished; most recent first. */
    public function listForUsuario(int $idUsuario): array
    {
        $stmt = $this->db->prepare(self::SELECT . "
             WHERE i.id_usuario = :id AND m.activo = 1
          ORDER BY FIELD(i.estado, 'en_curso', 'inscrito', 'finalizado'), i.actualizado_en DESC");
        $stmt->execute(['id' => $idUsuario]);

        return array_map(Inscripcion::fromRow(...), $stmt->fetchAll());
    }

    /** @return list<int> */
    public function listModuloIdsForUsuario(int $idUsuario): array
    {
        $stmt = $this->db->prepare('SELECT id_modulo FROM inscripcion WHERE id_usuario = :id');
        $stmt->execute(['id' => $idUsuario]);

        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    public function findForUsuario(int $idUsuario, int $idInscripcion): ?Inscripcion
    {
        $stmt = $this->db->prepare(self::SELECT . ' WHERE i.id_usuario = :uid AND i.id_inscripcion = :id');
        $stmt->execute(['uid' => $idUsuario, 'id' => $idInscripcion]);
        $row = $stmt->fetch();

        return $row !== false ? Inscripcion::fromRow($row) : null;
    }

    public function findById(int $idInscripcion): ?Inscripcion
    {
        $stmt = $this->db->prepare(self::SELECT . ' WHERE i.id_inscripcion = :id');
        $stmt->execute(['id' => $idInscripcion]);
        $row = $stmt->fetch();

        return $row !== false ? Inscripcion::fromRow($row) : null;
    }

    public function findUsuarioIdByInscripcion(int $idInscripcion): ?int
    {
        $stmt = $this->db->prepare('SELECT id_usuario FROM inscripcion WHERE id_inscripcion = :id');
        $stmt->execute(['id' => $idInscripcion]);
        $val = $stmt->fetchColumn();

        return $val !== false ? (int) $val : null;
    }

    public function findByModulo(int $idUsuario, int $idModulo): ?Inscripcion
    {
        $stmt = $this->db->prepare(self::SELECT . ' WHERE i.id_usuario = :uid AND i.id_modulo = :mid');
        $stmt->execute(['uid' => $idUsuario, 'mid' => $idModulo]);
        $row = $stmt->fetch();

        return $row !== false ? Inscripcion::fromRow($row) : null;
    }

    /** Idempotent: enrolling twice keeps the existing record. */
    public function inscribir(int $idUsuario, int $idModulo): void
    {
        $stmt = $this->db->prepare(
            'INSERT IGNORE INTO inscripcion (id_usuario, id_modulo) VALUES (:uid, :mid)'
        );
        $stmt->execute(['uid' => $idUsuario, 'mid' => $idModulo]);
    }

    public function inscribirConAvance(int $idUsuario, int $idModulo, EstadoInscripcion $estado, int $avance): void
    {
        $fin = $estado === EstadoInscripcion::Finalizado ? 1 : 0;
        $stmt = $this->db->prepare(
            'INSERT INTO inscripcion (id_usuario, id_modulo, estado, avance, finalizado_en)
             VALUES (:uid, :mid, :estado, :avance, IF(:fin1 = 1, NOW(), NULL))
             ON DUPLICATE KEY UPDATE
                estado = VALUES(estado),
                avance = VALUES(avance),
                finalizado_en = IF(:fin2 = 1, COALESCE(finalizado_en, NOW()), NULL)'
        );
        $stmt->execute([
            'uid'    => $idUsuario,
            'mid'    => $idModulo,
            'estado' => $estado->value,
            'avance' => max(0, min(100, $avance)),
            'fin1'   => $fin,
            'fin2'   => $fin,
        ]);
    }

    public function actualizarAvance(int $idInscripcion, EstadoInscripcion $estado, int $avance): void
    {
        $stmt = $this->db->prepare(
            'UPDATE inscripcion
                SET estado = :estado,
                    avance = :avance,
                    finalizado_en = IF(:fin = 1, COALESCE(finalizado_en, NOW()), NULL)
              WHERE id_inscripcion = :id'
        );
        $stmt->execute([
            'estado' => $estado->value,
            'avance' => max(0, min(100, $avance)),
            'fin'    => (int) ($estado === EstadoInscripcion::Finalizado),
            'id'     => $idInscripcion,
        ]);
    }

    public function eliminar(int $idUsuario, int $idInscripcion): void
    {
        $stmt = $this->db->prepare('DELETE FROM inscripcion WHERE id_inscripcion = :id AND id_usuario = :uid');
        $stmt->execute(['id' => $idInscripcion, 'uid' => $idUsuario]);
    }

    public function eliminarPorId(int $idInscripcion): bool
    {
        $stmt = $this->db->prepare('DELETE FROM inscripcion WHERE id_inscripcion = :id');
        $stmt->execute(['id' => $idInscripcion]);

        return $stmt->rowCount() === 1;
    }

    /** @return list<array<string, mixed>> */
    public function listAllWithUsuarioAndModulo(): array
    {
        $stmt = $this->db->query(
            'SELECT i.id_inscripcion, i.id_usuario, i.id_modulo, i.estado, i.avance, i.inscrito_en, i.finalizado_en,
                    u.nombre AS usuario_nombre, u.email AS usuario_email,
                    m.titulo AS modulo_titulo, m.slug AS modulo_slug, m.icono AS modulo_icono, m.color AS modulo_color
               FROM inscripcion i
               JOIN usuario u ON u.id_usuario = i.id_usuario
               JOIN modulo m ON m.id_modulo = i.id_modulo
           ORDER BY i.actualizado_en DESC, i.id_inscripcion DESC'
        );

        return $stmt->fetchAll();
    }

    /** @return array{total:int, inscrito:int, en_curso:int, finalizado:int} */
    public function countStats(): array
    {
        $stats = ['total' => 0, 'inscrito' => 0, 'en_curso' => 0, 'finalizado' => 0];
        $rows = $this->db->query('SELECT estado, COUNT(*) AS c FROM inscripcion GROUP BY estado')->fetchAll();
        foreach ($rows as $r) {
            $k = (string) $r['estado'];
            $c = (int) $r['c'];
            $stats['total'] += $c;
            if (isset($stats[$k])) {
                $stats[$k] = $c;
            }
        }

        return $stats;
    }
}

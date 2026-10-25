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

    public function findForUsuario(int $idUsuario, int $idInscripcion): ?Inscripcion
    {
        $stmt = $this->db->prepare(self::SELECT . ' WHERE i.id_usuario = :uid AND i.id_inscripcion = :id');
        $stmt->execute(['uid' => $idUsuario, 'id' => $idInscripcion]);
        $row = $stmt->fetch();

        return $row !== false ? Inscripcion::fromRow($row) : null;
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
}

<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

/**
 * Ensures database schema updates, official scholarships (TecVictoria & TECNM),
 * and required Superadministrador accounts are always present and up to date.
 */
final class SchemaEnsurer
{
    private static bool $ensured = false;

    public static function ensure(PDO $db): void
    {
        if (self::$ensured) {
            return;
        }
        self::$ensured = true;

        try {
            self::migrateAdministradorSchema($db);
            self::migrateBecasTable($db);
            self::ensureSuperAdmins($db);
        } catch (\Throwable $e) {
            error_log('[SchemaEnsurer] Aviso de migración automática: ' . $e->getMessage());
        }
    }

    private static function migrateAdministradorSchema(PDO $db): void
    {
        // Expand administrador.nivel ENUM to support 'jefe_curso', 'admin', 'superadmin'
        $col = $db->query("SHOW COLUMNS FROM administrador LIKE 'nivel'")->fetch();
        if (is_array($col) && isset($col['Type']) && !str_contains((string) $col['Type'], 'jefe_curso')) {
            $db->exec(
                "ALTER TABLE administrador
                 MODIFY COLUMN nivel ENUM('jefe_curso', 'admin', 'superadmin') NOT NULL DEFAULT 'admin'"
            );
        }

        // Add optional cargo and departamento columns to administrador if missing
        $cargoCol = $db->query("SHOW COLUMNS FROM administrador LIKE 'cargo'")->fetch();
        if ($cargoCol === false) {
            $db->exec(
                "ALTER TABLE administrador
                    ADD COLUMN cargo VARCHAR(100) NULL AFTER nivel,
                    ADD COLUMN departamento VARCHAR(120) NULL AFTER cargo"
            );
        }
    }

    private static function migrateBecasTable(PDO $db): void
    {
        $db->exec(
            "CREATE TABLE IF NOT EXISTS beca (
                id_beca        INT UNSIGNED      NOT NULL AUTO_INCREMENT,
                clave          VARCHAR(60)       NOT NULL,
                institucion    VARCHAR(120)      NOT NULL,
                siglas         VARCHAR(40)       NOT NULL,
                titulo         VARCHAR(150)      NOT NULL,
                descripcion    VARCHAR(400)      NOT NULL,
                url            VARCHAR(300)      NOT NULL,
                estado         VARCHAR(40)       NOT NULL DEFAULT 'Abierta',
                icono          VARCHAR(16)       NOT NULL DEFAULT '🎓',
                orden          SMALLINT UNSIGNED NOT NULL DEFAULT 1,
                activo         TINYINT(1)        NOT NULL DEFAULT 1,
                actualizado_en DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id_beca),
                UNIQUE KEY uq_beca_clave (clave)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        // Ensure the 2 official scholarships requested (TecVictoria and TECNM) exist
        $stmt = $db->prepare(
            "INSERT INTO beca (clave, institucion, siglas, titulo, descripcion, url, estado, icono, orden, activo)
             VALUES (:clave, :institucion, :siglas, :titulo, :descripcion, :url, :estado, :icono, :orden, 1)
             ON DUPLICATE KEY UPDATE
                url = VALUES(url),
                institucion = VALUES(institucion),
                siglas = VALUES(siglas)"
        );

        $stmt->execute([
            'clave'       => 'becas-tecvictoria',
            'institucion' => 'Instituto Tecnológico de Ciudad Victoria',
            'siglas'      => 'TecVictoria',
            'titulo'      => 'Portal de Becas y Apoyos Estudiantiles · TecVictoria',
            'descripcion' => 'Consulta convocatorias vigentes, becas alimenticias, apoyos institucionales y trámites para estudiantes del IT de Ciudad Victoria.',
            'url'         => 'https://cdvictoria.tecnm.mx/alumnos/becas.html',
            'estado'      => 'Convocatoria activa',
            'icono'       => '🎓',
            'orden'       => 1,
        ]);

        $stmt->execute([
            'clave'       => 'becas-tecnm',
            'institucion' => 'Tecnológico Nacional de México',
            'siglas'      => 'TECNM',
            'titulo'      => 'Convocatorias Nacionales de Becas · TECNM',
            'descripcion' => 'Portal oficial del Tecnológico Nacional de México con programas de becas académicas, movilidad, investigación y manutención federal.',
            'url'         => 'https://www.tecnm.mx/?vista=Becas',
            'estado'      => 'Convocatoria activa',
            'icono'       => '🏛️',
            'orden'       => 2,
        ]);
    }

    private static function ensureSuperAdmins(PDO $db): void
    {
        $superAdmins = [
            [
                'nombre'   => 'Johan Gael Castillo Gonzalez',
                'email'    => 'admin@logicavisual.edu.mx',
                'password' => 'Admin1234!',
                'cargo'    => 'Superadministrador General',
                'depto'    => 'Dirección de Sistemas y Plataforma ABP',
            ],
            [
                'nombre'   => 'Marlene Jaquelin Escobedo Mejia',
                'email'    => 'adminMarlen@tecvictoria.edu.mx',
                'password' => 'AdminEsc1#',
                'cargo'    => 'Superadministradora Institucional',
                'depto'    => 'Coordinación Académica · TecVictoria',
            ],
        ];

        $findStmt = $db->prepare('SELECT id_usuario, email, password_hash, activo FROM usuario WHERE LOWER(email) = LOWER(:email) LIMIT 1');
        $insertUserStmt = $db->prepare(
            'INSERT INTO usuario (nombre, email, password_hash, activo) VALUES (:nombre, :email, :hash, 1)'
        );
        $updateUserStmt = $db->prepare(
            'UPDATE usuario
                SET email = :email,
                    password_hash = :hash,
                    activo = 1,
                    intentos_fallidos = 0,
                    bloqueado_hasta = NULL
              WHERE id_usuario = :id'
        );
        $upsertAdminStmt = $db->prepare(
            "INSERT INTO administrador (id_usuario, nivel, cargo, departamento)
             VALUES (:id, 'superadmin', :cargo, :depto)
             ON DUPLICATE KEY UPDATE
                nivel = 'superadmin',
                cargo = COALESCE(cargo, VALUES(cargo)),
                departamento = COALESCE(departamento, VALUES(departamento))"
        );

        foreach ($superAdmins as $sa) {
            $findStmt->execute(['email' => $sa['email']]);
            $row = $findStmt->fetch();

            if ($row === false) {
                $hash = password_hash($sa['password'], PASSWORD_DEFAULT);
                $insertUserStmt->execute([
                    'nombre' => $sa['nombre'],
                    'email'  => $sa['email'],
                    'hash'   => $hash,
                ]);
                $userId = (int) $db->lastInsertId();
            } else {
                $userId = (int) $row['id_usuario'];
                $currentHash = (string) $row['password_hash'];
                $needsPassUpdate = !password_verify($sa['password'], $currentHash);
                $needsEmailCaseUpdate = ((string) $row['email']) !== $sa['email'];
                $needsActiveUpdate = !((bool) $row['activo']);

                if ($needsPassUpdate || $needsEmailCaseUpdate || $needsActiveUpdate) {
                    $newHash = $needsPassUpdate ? password_hash($sa['password'], PASSWORD_DEFAULT) : $currentHash;
                    $updateUserStmt->execute([
                        'email' => $sa['email'],
                        'hash'  => $newHash,
                        'id'    => $userId,
                    ]);
                }
            }

            $upsertAdminStmt->execute([
                'id'    => $userId,
                'cargo' => $sa['cargo'],
                'depto' => $sa['depto'],
            ]);
        }
    }
}


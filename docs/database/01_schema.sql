-- =====================================================================
--  Lógica Visual ABP · Esquema de base de datos
--  Compatible con: MariaDB 10.4+ (XAMPP) y MySQL 8.0 (Azure Database
--  for MySQL – Flexible Server).
--
--  Los nombres de tabla van en minúsculas a propósito: Windows guarda
--  los nombres en minúsculas (lower_case_table_names=1) y Linux/Azure
--  distingue mayúsculas. Usar minúsculas evita errores al migrar.
-- =====================================================================

CREATE DATABASE IF NOT EXISTS cursos
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cursos;

SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- ---------------------------------------------------------------------
-- usuario: toda persona con cuenta (estudiantes y administradores)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuario (
    id_usuario         INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    nombre             VARCHAR(100)     NOT NULL,
    email              VARCHAR(150)     NOT NULL,
    password_hash      VARCHAR(255)     NOT NULL,
    activo             TINYINT(1)       NOT NULL DEFAULT 1,
    intentos_fallidos  TINYINT UNSIGNED NOT NULL DEFAULT 0,
    bloqueado_hasta    DATETIME         NULL,
    ultimo_acceso      DATETIME         NULL,
    creado_en          DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en     DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_usuario),
    UNIQUE KEY uq_usuario_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- administrador: SOLO los usuarios registrados aquí tienen permisos de
-- administración. 'superadmin' es el único nivel que puede otorgar o
-- revocar permisos a otros usuarios.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS administrador (
    id_administrador   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_usuario         INT UNSIGNED NOT NULL,
    nivel              ENUM('admin', 'superadmin') NOT NULL DEFAULT 'admin',
    otorgado_por       INT UNSIGNED NULL,
    creado_en          DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_administrador),
    UNIQUE KEY uq_administrador_usuario (id_usuario),
    KEY idx_administrador_otorgado_por (otorgado_por),
    CONSTRAINT fk_administrador_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_administrador_otorgante
        FOREIGN KEY (otorgado_por) REFERENCES usuario (id_usuario)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- modulo: Módulos Interactivos (rutas de aprendizaje)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS modulo (
    id_modulo      INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    slug           VARCHAR(80)       NOT NULL,
    titulo         VARCHAR(120)      NOT NULL,
    descripcion    VARCHAR(500)      NOT NULL,
    contenido      TEXT              NULL,
    icono          VARCHAR(16)       NOT NULL,
    color          ENUM('cyan', 'indigo', 'purple', 'sky') NOT NULL DEFAULT 'cyan',
    nivel          ENUM('inicial', 'intermedio', 'avanzado') NOT NULL DEFAULT 'inicial',
    orden          SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    activo         TINYINT(1)        NOT NULL DEFAULT 1,
    creado_en      DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_modulo),
    UNIQUE KEY uq_modulo_slug (slug),
    KEY idx_modulo_activo_orden (activo, orden)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- evaluacion: tipos de evaluación (diagnóstica, formativa, desempeño)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS evaluacion (
    id_evaluacion  INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    tipo           ENUM('diagnostica', 'formativa', 'desempeno') NOT NULL,
    titulo         VARCHAR(120)      NOT NULL,
    etapa          VARCHAR(80)       NOT NULL,
    descripcion    VARCHAR(400)      NOT NULL,
    icono          VARCHAR(16)       NOT NULL,
    orden          SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (id_evaluacion),
    UNIQUE KEY uq_evaluacion_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- etapa_abp: pasos del proceso de la Metodología ABP
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS etapa_abp (
    id_etapa     INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    orden        TINYINT UNSIGNED NOT NULL,
    nombre       VARCHAR(60)      NOT NULL,
    descripcion  VARCHAR(300)     NOT NULL,
    PRIMARY KEY (id_etapa),
    UNIQUE KEY uq_etapa_abp_orden (orden)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- progreso: indicadores de progreso por usuario (0 – 100 %)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS progreso (
    id_progreso    INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    id_usuario     INT UNSIGNED     NOT NULL,
    indicador      ENUM('comprension', 'argumentacion', 'aplicacion') NOT NULL,
    porcentaje     TINYINT UNSIGNED NOT NULL DEFAULT 0,
    actualizado_en DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_progreso),
    UNIQUE KEY uq_progreso_usuario_indicador (id_usuario, indicador),
    CONSTRAINT chk_progreso_porcentaje CHECK (porcentaje BETWEEN 0 AND 100),
    CONSTRAINT fk_progreso_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

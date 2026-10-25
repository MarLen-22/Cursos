-- =====================================================================
--  Lógica Visual ABP · Perfil del alumno e inscripciones
--  Ejecutar DESPUÉS de 01_schema.sql y 02_seed.sql. Es idempotente.
--  Compatible con MariaDB 10.4+ (XAMPP) y MySQL 8.0 (Azure).
-- =====================================================================

USE cursos;
SET NAMES utf8mb4;

-- ---------------------------------------------------------------------
-- perfil_alumno: datos personales, de contacto y académicos (1:1 con
-- usuario). El nombre completo y el correo viven en la tabla usuario.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS perfil_alumno (
    id_usuario                    INT UNSIGNED     NOT NULL,
    numero_control                VARCHAR(20)      NULL,
    telefono                      VARCHAR(20)      NULL,
    pais_residencia               VARCHAR(60)      NULL,
    nacionalidad                  VARCHAR(60)      NULL,
    fecha_nacimiento              DATE             NULL,
    genero                        ENUM('hombre', 'mujer', 'no_binario', 'prefiero_no_decir') NULL,
    -- Información de contacto
    email_alterno                 VARCHAR(150)     NULL,
    ciudad                        VARCHAR(80)      NULL,
    estado_residencia             VARCHAR(80)      NULL,
    codigo_postal                 VARCHAR(10)      NULL,
    contacto_emergencia_nombre    VARCHAR(100)     NULL,
    contacto_emergencia_telefono  VARCHAR(20)      NULL,
    -- Información académica
    institucion                   VARCHAR(150)     NULL,
    carrera                       VARCHAR(120)     NULL,
    semestre                      TINYINT UNSIGNED NULL,
    modalidad                     ENUM('escolarizada', 'mixta', 'en_linea') NULL,
    -- Información adicional como estudiante
    nivel_estudios                ENUM('bachillerato', 'tsu', 'licenciatura', 'ingenieria', 'maestria', 'doctorado') NULL,
    area_estudios                 VARCHAR(120)     NULL,
    nota_media                    DECIMAL(5,2)     NULL,
    actualizado_en                DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_usuario),
    UNIQUE KEY uq_perfil_alumno_numero_control (numero_control),
    CONSTRAINT chk_perfil_semestre   CHECK (semestre IS NULL OR semestre BETWEEN 1 AND 14),
    CONSTRAINT chk_perfil_nota_media CHECK (nota_media IS NULL OR nota_media BETWEEN 0 AND 100),
    CONSTRAINT fk_perfil_alumno_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- inscripcion: módulos en los que participa cada alumno ("Mi aprendizaje")
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS inscripcion (
    id_inscripcion  INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    id_usuario      INT UNSIGNED     NOT NULL,
    id_modulo       INT UNSIGNED     NOT NULL,
    estado          ENUM('inscrito', 'en_curso', 'finalizado') NOT NULL DEFAULT 'inscrito',
    avance          TINYINT UNSIGNED NOT NULL DEFAULT 0,
    inscrito_en     DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    finalizado_en   DATETIME         NULL,
    actualizado_en  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_inscripcion),
    UNIQUE KEY uq_inscripcion_usuario_modulo (id_usuario, id_modulo),
    KEY idx_inscripcion_modulo (id_modulo),
    CONSTRAINT chk_inscripcion_avance CHECK (avance BETWEEN 0 AND 100),
    CONSTRAINT fk_inscripcion_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_inscripcion_modulo
        FOREIGN KEY (id_modulo) REFERENCES modulo (id_modulo)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

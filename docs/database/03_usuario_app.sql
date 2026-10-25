-- =====================================================================
--  (Recomendado) Usuario de base de datos con privilegios mínimos.
--  Cambia la contraseña antes de ejecutar y colócala en .env
--  (DB_USERNAME / DB_PASSWORD). Evita usar 'root' en producción.
-- =====================================================================

CREATE USER IF NOT EXISTS 'cursos_app' @'localhost' IDENTIFIED BY 'CAMBIA_ESTA_CONTRASENA';

CREATE USER IF NOT EXISTS 'cursos_app' @'127.0.0.1' IDENTIFIED BY 'CAMBIA_ESTA_CONTRASENA';

GRANT
SELECT,
INSERT
,
UPDATE,
DELETE ON cursos.* TO 'cursos_app' @'localhost';

GRANT
SELECT,
INSERT
,
UPDATE,
DELETE ON cursos.* TO 'cursos_app' @'127.0.0.1';

FLUSH PRIVILEGES;
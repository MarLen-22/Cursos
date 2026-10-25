-- =====================================================================
--  Seed de datos para el alumno Johan Gael Castillo Gonzalez
--  Permite visualizar de inmediato "Mi aprendizaje" y "Mi cuenta"
--  tal como se muestra en las imágenes de referencia.
-- =====================================================================

USE cursos;
SET NAMES utf8mb4;

-- Los únicos módulos activos del proyecto son los 4 oficiales definidos en 02_seed.sql:
-- 1. Fundamentos de Programación
-- 2. Cálculo Diferencial e Integral
-- 3. Matemáticas Discretas
-- 4. Física

-- Actualizar nombre completo de usuario 1
UPDATE usuario
   SET nombre = 'Johan Gael Castillo Gonzalez'
 WHERE id_usuario = 1;

-- Perfil del alumno (1:1 con usuario 1)
INSERT INTO perfil_alumno (
    id_usuario, numero_control, telefono, pais_residencia, nacionalidad,
    fecha_nacimiento, genero, email_alterno, ciudad, estado_residencia,
    codigo_postal, contacto_emergencia_nombre, contacto_emergencia_telefono,
    institucion, carrera, semestre, modalidad,
    nivel_estudios, area_estudios, nota_media
) VALUES (
    1,
    '22380123',
    '+52 482 103 873 6',
    'México',
    'México',
    '2004-01-12',
    'hombre',
    'johan.castillo@gmail.com',
    'Ciudad Victoria',
    'Tamaulipas',
    '87000',
    'María González',
    '+52 834 111 2233',
    'Instituto Tecnológico de Ciudad Victoria',
    'Ingeniería en Sistemas Computacionales',
    6,
    'escolarizada',
    'ingenieria',
    'Tecnologías de la Información y Software',
    94.50
) ON DUPLICATE KEY UPDATE
    numero_control = VALUES(numero_control),
    telefono = VALUES(telefono),
    pais_residencia = VALUES(pais_residencia),
    nacionalidad = VALUES(nacionalidad),
    fecha_nacimiento = VALUES(fecha_nacimiento),
    genero = VALUES(genero),
    institucion = VALUES(institucion),
    carrera = VALUES(carrera),
    semestre = VALUES(semestre),
    nota_media = VALUES(nota_media);

-- Inscripciones reflejando la tabla de "Mi aprendizaje" con los 4 módulos oficiales
INSERT INTO inscripcion (id_usuario, id_modulo, estado, avance, inscrito_en, finalizado_en) VALUES
(1, 1, 'finalizado', 100, DATE_SUB(NOW(), INTERVAL 30 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
(1, 2, 'en_curso', 65, DATE_SUB(NOW(), INTERVAL 20 DAY), NULL),
(1, 3, 'en_curso', 40, DATE_SUB(NOW(), INTERVAL 15 DAY), NULL),
(1, 4, 'inscrito', 10, DATE_SUB(NOW(), INTERVAL 5 DAY), NULL)
ON DUPLICATE KEY UPDATE
    estado = VALUES(estado),
    avance = VALUES(avance),
    finalizado_en = VALUES(finalizado_en);

-- =====================================================================
--  Lógica Visual ABP · Datos iniciales (idempotente)
--  El superadministrador NO se crea aquí para no versionar contraseñas:
--  usa  php src/cli/crear_superadmin.php
-- =====================================================================

USE cursos;
SET NAMES utf8mb4;

INSERT INTO modulo (slug, titulo, descripcion, contenido, icono, color, nivel, orden) VALUES
('fundamentos-de-programacion', 'Fundamentos de Programación',
 'Piensa en secuencias, decisiones y patrones antes de escribir una sola línea de código.',
 'En este módulo construirás algoritmos a partir de problemas cotidianos.\n\nTemas: variables y tipos de datos, operadores, estructuras de decisión (if / switch), ciclos (for / while), funciones y arreglos.\n\nCada reto te pide formular una hipótesis, probarla en el simulador y explicar por qué tu solución funciona.',
 '</>', 'cyan', 'inicial', 1),
('calculo-diferencial', 'Cálculo Diferencial',
 'Haz visible el cambio: interpreta funciones, límites, derivadas, pendientes y optimización con enfoque lógico-visual.',
 'El objetivo de este módulo es evitar que el alumno memorice fórmulas, obligándolo a interactuar con simuladores de iteración y cambio con condiciones lógicas de parada.\n\nExplora funciones como cajas negras, límites como ciclos de aproximación, la derivada como razón de cambio instantánea, reconocimiento de patrones en reglas de derivación y optimización de problemas reales.',
 '📈', 'indigo', 'inicial', 2),
('matematicas-discretas', 'Matemáticas Discretas',
 'Descubre cómo la lógica, los conjuntos y las redes ayudan a resolver problemas complejos.',
 'Trabaja con proposiciones lógicas, tablas de verdad, conjuntos, relaciones y grafos.\n\nModela redes reales y encuentra rutas óptimas paso a paso.',
 '⛖', 'purple', 'inicial', 3),
('fisica', 'Física',
 'Relaciona movimiento, fuerzas y energía con situaciones que puedes modelar y comprobar.',
 'Simula tiros parabólicos, planos inclinados y conservación de la energía.\n\nAjusta variables, predice el resultado y compáralo con el modelo.',
 '⚛', 'sky', 'inicial', 4)
ON DUPLICATE KEY UPDATE titulo = VALUES(titulo);

INSERT INTO evaluacion (tipo, titulo, etapa, descripcion, icono, orden) VALUES
('diagnostica', 'Evaluación diagnóstica', 'Punto de partida',
 'Identifica saberes previos y reconoce qué estrategias te conviene activar antes de iniciar.', '📊', 1),
('formativa', 'Evaluación formativa', 'Durante el proceso',
 'Obtén retroalimentación inmediata mientras exploras, fallas, ajustas y vuelves a intentar.', '💬', 2),
('desempeno', 'Evaluación de desempeño', 'Aplicación integrada',
 'Integra tus aprendizajes al resolver un problema, explicar tu razonamiento y defender tu solución.', '🏅', 3)
ON DUPLICATE KEY UPDATE titulo = VALUES(titulo);

INSERT INTO etapa_abp (orden, nombre, descripcion) VALUES
(1, 'Explora',     'Analiza el problema, identifica datos y lo que necesitas averiguar.'),
(2, 'Formula',     'Plantea hipótesis y diseña una estrategia de solución.'),
(3, 'Experimenta', 'Prueba tu estrategia en el simulador y observa los resultados.'),
(4, 'Reflexiona',  'Explica tu razonamiento, compara alternativas y mejora tu solución.')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

CREATE TABLE IF NOT EXISTS beca (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO beca (clave, institucion, siglas, titulo, descripcion, url, estado, icono, orden, activo) VALUES
('becas-tecvictoria', 'Instituto Tecnológico de Ciudad Victoria', 'TecVictoria',
 'Portal de Becas y Apoyos Estudiantiles · TecVictoria',
 'Consulta convocatorias vigentes, becas alimenticias, apoyos institucionales y trámites para estudiantes del IT de Ciudad Victoria.',
 'https://cdvictoria.tecnm.mx/alumnos/becas.html', 'Convocatoria activa', '🎓', 1, 1),
('becas-tecnm', 'Tecnológico Nacional de México', 'TECNM',
 'Convocatorias Nacionales de Becas · TECNM',
 'Portal oficial del Tecnológico Nacional de México con programas de becas académicas, movilidad, investigación y manutención federal.',
 'https://www.tecnm.mx/?vista=Becas', 'Convocatoria activa', '🏛️', 2, 1)
ON DUPLICATE KEY UPDATE url = VALUES(url), titulo = VALUES(titulo);

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
('calculo-diferencial-e-integral', 'Cálculo Diferencial e Integral',
 'Haz visible el cambio: interpreta funciones, pendientes, áreas y comportamiento de gráficas.',
 'Explora límites, derivadas e integrales manipulando gráficas en tiempo real.\n\nObserva cómo cambia la pendiente de una recta tangente y cómo se aproxima el área bajo una curva.',
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

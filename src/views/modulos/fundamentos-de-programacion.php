<?php
/**
 * Vista Principal del Curso: Fundamentos de Programación (Lógica, Estructuras y Simulación)
 * Diseño Split-Screen E-Learning (Sidebar Izquierdo 30% / Panel Principal 70%)
 * Paleta Clean UI: Azul Cobalto (Blue) e Índigo
 *
 * Variables disponibles desde ModuloController::show():
 * @var \App\Models\Modulo $modulo
 * @var \App\Models\Inscripcion|null $inscripcion
 */

use App\Core\Auth;

$usuarioActual = Auth::user();

// =========================================================================
// PARÁMETROS DINÁMICOS DE SESIÓN PARA LA GENERACIÓN DEL CERTIFICADO PDF
// =========================================================================
$nombreAlumno     = $usuarioActual !== null ? $usuarioActual->nombre : 'Estudiante de Ingeniería';
$moduloCompletado = $modulo->titulo ?? 'Fundamentos de Programación (Lógica, Estructuras y Simulación)';
$fechaActual      = ($inscripcion !== null && $inscripcion->finalizadoEn !== null)
    ? fecha($inscripcion->finalizadoEn, 'd/m/Y')
    : date('d/m/Y');
$folioCertificado = sprintf(
    'LV-PROG-%05d-%04d',
    $inscripcion !== null ? $inscripcion->id : 1,
    $usuarioActual !== null ? $usuarioActual->id : 1
);
?>

<link rel="stylesheet" href="<?= asset('css/programacion_fundamentos.css') ?>">

<div class="prog-elearning-wrapper" id="programacionCursoApp">
    <!-- Metadatos dinámicos de sesión para el generador del Certificado PDF -->
    <div id="progCertificateData"
         class="d-none"
         data-nombre-alumno="<?= e($nombreAlumno) ?>"
         data-modulo-completado="<?= e($moduloCompletado) ?>"
         data-fecha-emision="<?= e($fechaActual) ?>"
         data-folio="<?= e($folioCertificado) ?>"></div>

    <!-- Encabezado Superior del Curso (Paleta Azul Cobalto / Índigo) -->
    <section class="prog-hero-banner text-white py-4 position-relative">
        <div class="bg-grid position-absolute top-0 start-0 w-100 h-100 pointer-events-none"></div>
        <div class="container-fluid max-w-7xl mx-auto px-3 px-md-4 position-relative z-1">
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?= url('/modulos') ?>" class="text-slate-300 text-decoration-none">Módulos Interactivos</a></li>
                    <li class="breadcrumb-item active text-info fw-semibold" aria-current="page">Fundamentos de Programación: Lógica y Simulación</li>
                </ol>
            </nav>

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="module-icon-box bg-primary-subtle text-info fs-3 fw-bold" style="background: rgba(59,130,246,0.22); color: #60a5fa;">&lt;/&gt;</div>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <h1 class="h3 fw-bold text-white mb-0">Fundamentos de Programación (Lógica, Estructuras y Simulación)</h1>
                            <span class="badge rounded-pill fw-bold" style="background:#3b82f6; color:#eff6ff;">Nivel Inicial · ABP</span>
                        </div>
                        <p class="small text-slate-200 mb-0 max-w-4xl">
                            En Programación, el pensamiento algorítmico transforma problemas reales en instrucciones ejecutables. Las variables, condicionales (<em>if/else</em>), ciclos (<em>while/for</em>) y funciones son la base de estas 5 lecciones interactivas.
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <?php if (Auth::check() && $inscripcion === null): ?>
                        <form action="<?= url('/modulos/' . e($modulo->slug) . '/inscripcion') ?>" method="POST" class="m-0">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-prog btn-sm fw-bold px-3 shadow-prog">
                                Inscribirme al curso ▷
                            </button>
                        </form>
                    <?php elseif (Auth::check() && $inscripcion !== null && !$inscripcion->finalizado()): ?>
                        <form action="<?= url('/modulos/' . e($modulo->slug) . '/avance') ?>" method="POST" class="m-0">
                            <?= csrf_field() ?>
                            <input type="hidden" name="accion" value="finalizar">
                            <button type="submit" class="btn btn-outline-light btn-sm fw-bold px-3">
                                Marcar curso como completado ✓
                            </button>
                        </form>
                    <?php endif; ?>
                    <a href="<?= url('/modulos') ?>" class="btn btn-outline-light btn-sm">← Catálogo</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Cuerpo Principal Split-Screen (30% Sidebar Izquierdo / 70% Panel Principal) -->
    <div class="container-fluid max-w-7xl mx-auto px-3 px-md-4 pt-4">
        <div class="prog-split-layout">

            <!-- =============================================================
                 1. PANEL LATERAL IZQUIERDO (30%): RUTA DE APRENDIZAJE Y NAVEGACIÓN
                 ============================================================= -->
            <aside class="prog-sidebar-panel" aria-label="Ruta de Aprendizaje y Navegación de Programación">
                <div class="prog-sidebar-card">
                    <div class="prog-sidebar-header">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-uppercase fw-bold text-xs tracking-wider text-prog">Ruta de Aprendizaje</span>
                            <a href="<?= url('/modulos') ?>" class="prog-sidebar-close-btn" title="Volver al catálogo de módulos" aria-label="Cerrar curso">✕</a>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h2 class="h6 fw-bold mb-0">Fundamentos de Programación</h2>
                            <span class="badge bg-primary-subtle text-primary rounded-pill" id="progGlobalProgressText">1/22 elementos (5%)</span>
                        </div>
                        <div class="progress" style="height: 7px;">
                            <div class="progress-bar" id="progGlobalProgressBar" role="progressbar" style="width: 5%; background-color: #2563eb;"></div>
                        </div>
                    </div>

                    <div class="accordion prog-lesson-accordion" id="progSidebarAccordion">
                        <!-- LECCIÓN 1 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="progHeadL1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#progCollapseL1" aria-expanded="true" aria-controls="progCollapseL1">
                                    <div>
                                        Lección 1: Algoritmos y Lógica de Solución
                                        <span class="prog-lesson-meta" id="progLessonCount-1">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="progCollapseL1" class="accordion-collapse collapse show" aria-labelledby="progHeadL1">
                                <ul class="prog-nav-list">
                                    <li>
                                        <button type="button" class="prog-nav-item is-active" data-prog-item="l1-lectura" data-prog-lesson="1" data-prog-type="lectura">
                                            <span class="prog-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Teoría: Entrada, Proceso, Salida y Pseudocódigo</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l1-video" data-prog-lesson="1" data-prog-type="video"
                                                data-video-title="¿Qué es un Algoritmo? Pensamiento Computacional"
                                                data-video-channel="Canal: HolaMundo"
                                                data-video-embed="https://www.youtube.com/embed/chPhlsydl30"
                                                data-video-url="https://www.youtube.com/watch?v=chPhlsydl30"
                                                data-video-alt-title="Algoritmos y Diagramas de Flujo desde Cero"
                                                data-video-alt-channel="Canal: TodoCode"
                                                data-video-alt-embed="https://www.youtube.com/embed/U3CGMyjzlvM"
                                                data-video-alt-url="https://www.youtube.com/watch?v=U3CGMyjzlvM"
                                                data-video-alt-short="Diagramas de Flujo desde Cero">
                                            <span class="prog-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • Qué es un Algoritmo (Pop-up YouTube)</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l1-simulador" data-prog-lesson="1" data-prog-type="simulador">
                                            <span class="prog-nav-icon">⚙</span>
                                            <span><strong>Simulador</strong> • Traza Algorítmica (Entrada → Proceso → Salida)</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l1-pdf" data-prog-lesson="1" data-prog-type="pdf">
                                            <span class="prog-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • Guia_1_Algoritmos_y_Diagramas_ANSI.pdf</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- LECCIÓN 2 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="progHeadL2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#progCollapseL2" aria-expanded="false" aria-controls="progCollapseL2">
                                    <div>
                                        Lección 2: Variables, Tipos de Datos y Operadores
                                        <span class="prog-lesson-meta" id="progLessonCount-2">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="progCollapseL2" class="accordion-collapse collapse" aria-labelledby="progHeadL2">
                                <ul class="prog-nav-list">
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l2-lectura" data-prog-lesson="2" data-prog-type="lectura">
                                            <span class="prog-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Teoría: Tipos Primitivos y Jerarquía de Operadores</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l2-video" data-prog-lesson="2" data-prog-type="video"
                                                data-video-title="Variables y Tipos de Datos en Programación"
                                                data-video-channel="Canal: TodoCode"
                                                data-video-embed="https://www.youtube.com/embed/s2d0_g6H40o"
                                                data-video-url="https://www.youtube.com/watch?v=s2d0_g6H40o"
                                                data-video-alt-title="Operadores Aritméticos, Relacionales y Lógicos"
                                                data-video-alt-channel="Canal: Programación ATS"
                                                data-video-alt-embed="https://www.youtube.com/embed/dN4C1e4g7zM"
                                                data-video-alt-url="https://www.youtube.com/watch?v=dN4C1e4g7zM"
                                                data-video-alt-short="Operadores y Jerarquía">
                                            <span class="prog-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • Variables y Tipos de Datos (Pop-up YouTube)</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l2-reto" data-prog-lesson="2" data-prog-type="reto">
                                            <span class="prog-nav-icon">⚡</span>
                                            <span><strong>Reto ABP</strong> • Evaluador de Expresiones y Precedencia</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l2-pdf" data-prog-lesson="2" data-prog-type="pdf">
                                            <span class="prog-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • Guia_2_Variables_y_Operadores.pdf</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- LECCIÓN 3 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="progHeadL3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#progCollapseL3" aria-expanded="false" aria-controls="progCollapseL3">
                                    <div>
                                        Lección 3: Estructuras Condicionales (if / else)
                                        <span class="prog-lesson-meta" id="progLessonCount-3">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="progCollapseL3" class="accordion-collapse collapse" aria-labelledby="progHeadL3">
                                <ul class="prog-nav-list">
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l3-lectura" data-prog-lesson="3" data-prog-type="lectura">
                                            <span class="prog-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Teoría: Decisiones Simples, Dobles y Anidadas</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l3-video" data-prog-lesson="3" data-prog-type="video"
                                                data-video-title="Estructuras Condicionales if, else if y else"
                                                data-video-channel="Canal: Fazt"
                                                data-video-embed="https://www.youtube.com/embed/2yC7-9_vK7k"
                                                data-video-url="https://www.youtube.com/watch?v=2yC7-9_vK7k"
                                                data-video-alt-title="Tablas de Verdad y Operadores Lógicos AND, OR, NOT"
                                                data-video-alt-channel="Canal: MoureDev"
                                                data-video-alt-embed="https://www.youtube.com/embed/5l3i2w7t89c"
                                                data-video-alt-url="https://www.youtube.com/watch?v=5l3i2w7t89c"
                                                data-video-alt-short="Operadores Lógicos y Condicionales">
                                            <span class="prog-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • Toma de Decisiones con if/else (Pop-up)</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l3-simulador" data-prog-lesson="3" data-prog-type="simulador">
                                            <span class="prog-nav-icon">⚙</span>
                                            <span><strong>Simulador</strong> • Depurador de Rutas Condicionales</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l3-pdf" data-prog-lesson="3" data-prog-type="pdf">
                                            <span class="prog-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • Guia_3_Estructuras_Condicionales.pdf</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- LECCIÓN 4 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="progHeadL4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#progCollapseL4" aria-expanded="false" aria-controls="progCollapseL4">
                                    <div>
                                        Lección 4: Estructuras Cíclicas (while / for)
                                        <span class="prog-lesson-meta" id="progLessonCount-4">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="progCollapseL4" class="accordion-collapse collapse" aria-labelledby="progHeadL4">
                                <ul class="prog-nav-list">
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l4-lectura" data-prog-lesson="4" data-prog-type="lectura">
                                            <span class="prog-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Teoría: Contadores, Acumuladores y Bucles</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l4-video" data-prog-lesson="4" data-prog-type="video"
                                                data-video-title="Bucles e Iteraciones: while, do-while y for"
                                                data-video-channel="Canal: TodoCode"
                                                data-video-embed="https://www.youtube.com/embed/6iOlB0QLy84"
                                                data-video-url="https://www.youtube.com/watch?v=6iOlB0QLy84"
                                                data-video-alt-title="Cómo evitar Bucles Infinitos y usar Acumuladores"
                                                data-video-alt-channel="Canal: Programación ATS"
                                                data-video-alt-embed="https://www.youtube.com/embed/j7S9k3L4p2w"
                                                data-video-alt-url="https://www.youtube.com/watch?v=j7S9k3L4p2w"
                                                data-video-alt-short="Control de Ciclos y Acumuladores">
                                            <span class="prog-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • Bucles while y for explicados (Pop-up)</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l4-reto" data-prog-lesson="4" data-prog-type="reto">
                                            <span class="prog-nav-icon">⚡</span>
                                            <span><strong>Reto ABP</strong> • Simulador de Trazas de Bucle (Contador/Suma)</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l4-pdf" data-prog-lesson="4" data-prog-type="pdf">
                                            <span class="prog-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • Guia_4_Patrones_Iterativos.pdf</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- LECCIÓN 5 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="progHeadL5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#progCollapseL5" aria-expanded="false" aria-controls="progCollapseL5">
                                    <div>
                                        Lección 5: Funciones y Arreglos (Listas)
                                        <span class="prog-lesson-meta" id="progLessonCount-5">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="progCollapseL5" class="accordion-collapse collapse" aria-labelledby="progHeadL5">
                                <ul class="prog-nav-list">
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l5-lectura" data-prog-lesson="5" data-prog-type="lectura">
                                            <span class="prog-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Teoría: Modularidad, Parámetros e Índices [0..n-1]</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l5-video" data-prog-lesson="5" data-prog-type="video"
                                                data-video-title="Funciones y Arreglos (Vectores/Listas) en Programación"
                                                data-video-channel="Canal: TodoCode"
                                                data-video-embed="https://www.youtube.com/embed/9J2h7m4p3wQ"
                                                data-video-url="https://www.youtube.com/watch?v=9J2h7m4p3wQ"
                                                data-video-alt-title="Paso de Parámetros y Retorno de Valores en Funciones"
                                                data-video-alt-channel="Canal: HolaMundo"
                                                data-video-alt-embed="https://www.youtube.com/embed/tR8k2M5n9xL"
                                                data-video-alt-url="https://www.youtube.com/watch?v=tR8k2M5n9xL"
                                                data-video-alt-short="Funciones y Retorno (HolaMundo)">
                                            <span class="prog-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • Funciones y Arreglos en Memoria (Pop-up)</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l5-simulador" data-prog-lesson="5" data-prog-type="simulador">
                                            <span class="prog-nav-icon">⚙</span>
                                            <span><strong>Simulador</strong> • Inspector de Arreglos y Llamadas a Función</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="l5-pdf" data-prog-lesson="5" data-prog-type="pdf">
                                            <span class="prog-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • Guia_5_Funciones_y_Arreglos.pdf</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- MÓDULO DE CIERRE Y CERTIFICACIÓN -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="progHeadL6">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#progCollapseL6" aria-expanded="false" aria-controls="progCollapseL6">
                                    <div>
                                        Módulo de Cierre y Certificación
                                        <span class="prog-lesson-meta" id="progLessonCount-6">0/2 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="progCollapseL6" class="accordion-collapse collapse" aria-labelledby="progHeadL6">
                                <ul class="prog-nav-list">
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="cierre-encuesta" data-prog-lesson="6" data-prog-type="cierre">
                                            <span class="prog-nav-icon">📊</span>
                                            <span><strong>Encuesta</strong> • Satisfacción e Impacto del Módulo (Escala Likert)</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="prog-nav-item" data-prog-item="cierre-certificado" data-prog-lesson="6" data-prog-type="cierre">
                                            <span class="prog-nav-icon">🎓</span>
                                            <span><strong>Certificado</strong> • Descargar acreditación de Programación</span>
                                            <span class="prog-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- =============================================================
                 2. PANEL PRINCIPAL (70%): ÁREA DE TRABAJO INTERACTIVA
                 ============================================================= -->
            <div class="prog-main-panel" id="progMainWorkspace">
                <div class="prog-workspace-card">

                    <!-- =====================================================
                         LECCIÓN 1 · ITEM 1: LECTURA (Algoritmos y Lógica de Solución)
                         ===================================================== -->
                    <article class="prog-content-pane is-active" data-prog-pane="l1-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <div>
                                <span class="prog-type-badge prog-badge-reading mb-1">📖 Lección 1 · Lectura Activa</span>
                                <h2 class="h3 fw-bold mb-0">Algoritmos y Lógica de Solución (Pensamiento Computacional)</h2>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-prog fw-semibold" data-prog-note-toggle="l1">
                                📝 Guardar nota
                            </button>
                        </div>

                        <!-- Cajón de Notas Personales -->
                        <div class="prog-note-drawer d-none" id="progNoteDrawer-l1">
                            <label for="progNoteInput-l1" class="form-label small fw-bold text-dark mb-1">📝 Tus apuntes de la Lección 1 (Algoritmos):</label>
                            <textarea id="progNoteInput-l1" class="form-control form-control-sm mb-2" rows="2" placeholder="Escribe aquí tus notas sobre Entrada, Proceso, Salida y símbolos ANSI..."></textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-primary fw-semibold" id="progNoteStatus-l1"></span>
                                <button type="button" class="btn btn-xs btn-prog fw-bold px-3" id="progNoteSave-l1">Guardar apunte</button>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <strong>Tema:</strong> Definición de algoritmo, características fundamentales, arquitectura Entrada–Proceso–Salida y pseudocódigo.
                        </p>

                        <h3 class="h5 fw-bold text-prog mb-2">¿Qué es? (Introducción Teórica)</h3>
                        <p class="text-secondary leading-relaxed mb-3">
                            Un <strong>algoritmo</strong> es una secuencia ordenada, finita y sin ambigüedades de pasos lógicos diseñados para resolver un problema específico. Antes de escribir una sola línea en Python, PHP, Java o C++, todo programa nace como un modelo lógico dividido en tres etapas esenciales:
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-4 border bg-light h-100">
                                    <h4 class="h6 fw-bold text-primary mb-2">📥 1. Entrada (Input)</h4>
                                    <p class="small text-secondary mb-0">
                                        Son los datos iniciales que el sistema recibe del usuario, de un archivo o de un sensor para poder operar (ejemplo: <code>nota1, nota2, nota3</code>).
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-4 border border-primary-subtle h-100" style="background: rgba(219,234,254,0.45);">
                                    <h4 class="h6 fw-bold text-prog mb-2">⚙️ 2. Proceso (Process)</h4>
                                    <p class="small text-secondary mb-0">
                                        Es el conjunto de operaciones matemáticas y comparaciones lógicas que transforman los datos de entrada (ejemplo: <code>promedio = (nota1 + nota2 + nota3) / 3</code>).
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-4 border bg-light h-100">
                                    <h4 class="h6 fw-bold text-indigo mb-2" style="color:#4338ca;">📤 3. Salida (Output)</h4>
                                    <p class="small text-secondary mb-0">
                                        Es el resultado final ya procesado que se muestra en pantalla o se almacena (ejemplo: mostrar <code>"Aprobado"</code> con su promedio).
                                    </p>
                                </div>
                            </div>
                        </div>

                        <h4 class="h6 fw-bold mb-2">Todo algoritmo válido debe cumplir con tres propiedades obligatorias:</h4>
                        <ol class="small text-secondary leading-relaxed mb-4">
                            <li class="mb-1"><strong>Precisión:</strong> Cada instrucción debe indicar exactamente qué hacer, sin dejar lugar a interpretaciones dobles.</li>
                            <li class="mb-1"><strong>Determinismo:</strong> Si se ejecuta dos veces con los mismos datos de entrada, debe producir exactamente la misma salida.</li>
                            <li class="mb-1"><strong>Finitud:</strong> Debe terminar después de un número limitado de pasos; un algoritmo que nunca termina es un error lógico.</li>
                        </ol>

                        <h3 class="h5 fw-bold text-prog mb-3">Estructura Estándar en Pseudocódigo (Para el Simulador)</h3>

                        <div class="prog-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Bloque de Declaración y Lectura:</div>
                                <div class="small text-muted">Captura de variables de entrada desde el entorno</div>
                            </div>
                            <div class="prog-formula-math"><code>LEER n1, n2, n3</code></div>
                        </div>

                        <div class="prog-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Bloque de Procesamiento y Asignación:</div>
                                <div class="small text-muted">Transformación aritmética de los datos capturados</div>
                            </div>
                            <div class="prog-formula-math"><code>promedio ← (n1 + n2 + n3) / 3</code></div>
                        </div>

                        <div class="prog-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                            <div>
                                <div class="fw-bold text-dark">Bloque de Salida:</div>
                                <div class="small text-muted">Entrega del resultado verificado al usuario</div>
                            </div>
                            <div class="prog-formula-math"><code>ESCRIBIR "Resultado: ", promedio</code></div>
                        </div>

                        <!-- Acordeón Expandible: Tips para el Simulador -->
                        <div class="prog-tips-accordion mb-4">
                            <button class="prog-tips-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#progTipsL1" aria-expanded="true">
                                <span>✦ Tips para el Simulador de Traza Algorítmica</span>
                                <span>[ ˅ ]</span>
                            </button>
                            <div id="progTipsL1" class="collapse show">
                                <div class="prog-tips-body">
                                    Observa cómo en un <strong>diagrama de flujo ANSI</strong> cada forma geométrica tiene un significado estricto: el <strong>óvalo</strong> marca el Inicio/Fin, el <strong>paralelogramo</strong> indica Entrada/Salida de datos, el <strong>rectángulo</strong> ejecuta procesos matemáticos y el <strong>rombo</strong> evalúa una condición lógica (Sí/No).
                                </div>
                            </div>
                        </div>

                        <!-- Barra Inferior: Feedback y Acción Directa -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                            <div class="d-flex align-items-center gap-2 prog-feedback-group">
                                <span class="small text-muted">¿Te resultó clara esta lectura?</span>
                                <button type="button" class="prog-feedback-btn" data-prog-feedback="up" aria-label="Me gustó">👍</button>
                                <button type="button" class="prog-feedback-btn" data-prog-feedback="down" aria-label="No me gustó">👎</button>
                                <span class="small text-prog fw-semibold prog-feedback-msg"></span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-prog btn-sm fw-bold" data-prog-goto="l1-video">
                                    ▶ Ver Vídeo Pop-up
                                </button>
                                <button type="button" class="btn btn-prog fw-bold px-4 shadow-prog" data-prog-goto="l1-simulador">
                                    Ir al Simulador: Traza Algorítmica ➔
                                </button>
                            </div>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 1 · ITEM 2: VÍDEO (Pop-up YouTube)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l1-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="prog-type-badge prog-badge-reading">▶ Lección 1 · Recursos Audiovisuales (Pop-up)</span>
                            <span class="text-muted small">Video: "¿Qué es un Algoritmo?"</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Recursos Audiovisuales: Algoritmos y Pensamiento Computacional</h2>
                        <p class="text-secondary small mb-4">
                            Para mantener enfocado tu espacio de trabajo, los vídeos se reproducen en una <strong>ventana emergente (Modal con fondo desenfocado)</strong>. Elige cualquiera de los recursos recomendados o ábrelos directamente en YouTube:
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-primary text-white mb-2">Video Recomendado Principal</span>
                                        <h3 class="h6 fw-bold mb-1">¿Qué es un Algoritmo? Pensamiento Computacional</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>HolaMundo</strong></p>
                                        <p class="small text-secondary mb-3">Explicación clara y cotidiana sobre cómo descomponer problemas reales en instrucciones lógicas secuenciales.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-prog btn-sm fw-bold px-3" data-open-prog-video="l1-video" data-video-mode="main">
                                            ▶ Reproducir en Modal
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=chPhlsydl30" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-secondary mb-2">Video Alternativo (Diagramación)</span>
                                        <h3 class="h6 fw-bold mb-1">Algoritmos y Diagramas de Flujo desde Cero</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>TodoCode</strong></p>
                                        <p class="small text-secondary mb-3">Repaso práctico de simbología ANSI y pruebas de escritorio paso a paso.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-prog btn-sm fw-bold px-3" data-open-prog-video="l1-video" data-video-mode="alt">
                                            ▶ Reproducir Alternativo
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=U3CGMyjzlvM" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 border bg-white small mb-4">
                            <strong>🌐 Herramienta Recomendada (Para Diagramas y Pseudocódigo):</strong>
                            <a href="https://pseint.sourceforge.net" target="_blank" rel="noopener noreferrer" class="fw-bold text-prog">PSeInt (pseint.sourceforge.net)</a> — Intérprete educativo de pseudocódigo en español con generación automática de diagramas de flujo.
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-prog-goto="l1-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-prog fw-bold px-4" data-prog-goto="l1-simulador">Siguiente: Simulador Traza Algorítmica ⚙</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 1 · ITEM 3: SIMULADOR (Traza Paso a Paso de Algoritmo)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l1-simulador">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="prog-type-badge prog-badge-sim">⚙ Lección 1 · Simulador Interactivo</span>
                            <span class="text-muted small">Prueba de Escritorio en Tiempo Real</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Simulador: Traza Algorítmica (Entrada → Proceso → Salida)</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Ejercicio:</strong> Modifica las tres calificaciones de entrada y ejecuta paso a paso el flujo del algoritmo para observar cómo cambian las variables en memoria y qué ruta toma el diagrama ANSI.
                        </p>

                        <div class="prog-sim-box mb-4">
                            <div class="prog-sim-topbar">
                                <span class="fw-bold text-info small">⚙ Intérprete Visual · Prueba de Escritorio ANSI</span>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-info text-dark font-monospace" id="progL1StepBadge">Paso 1/4: Entrada</span>
                                    <span class="badge bg-primary text-white font-monospace" id="progL1AvgBadge">promedio = 85.0</span>
                                    <span class="badge bg-success text-white font-monospace" id="progL1StatusBadge">APROBADO</span>
                                </div>
                            </div>
                            <div class="prog-sim-body">
                                <div class="row g-4 align-items-center mb-3">
                                    <div class="col-12 col-md-6">
                                        <div class="prog-sim-canvas-wrap">
                                            <svg viewBox="0 0 320 260" class="w-100" style="max-width:320px;" aria-label="Diagrama de Flujo Interactivo">
                                                <!-- Inicio -->
                                                <rect x="115" y="10" width="90" height="28" rx="14" fill="#1e293b" stroke="#60a5fa" stroke-width="2"/>
                                                <text x="160" y="28" font-size="11" fill="#e2e8f0" font-weight="bold" text-anchor="middle">INICIO</text>
                                                <line x1="160" y1="38" x2="160" y2="56" stroke="#64748b" stroke-width="2"/>

                                                <!-- Entrada (Paralelogramo) -->
                                                <polygon id="progL1NodeInput" points="75,56 260,56 245,92 60,92" fill="#1d4ed8" stroke="#93c5fd" stroke-width="2"/>
                                                <text x="160" y="78" font-size="10" fill="#ffffff" font-weight="bold" text-anchor="middle">LEER n1, n2, n3</text>
                                                <line x1="160" y1="92" x2="160" y2="110" stroke="#64748b" stroke-width="2"/>

                                                <!-- Proceso (Rectángulo) -->
                                                <rect id="progL1NodeProcess" x="65" y="110" width="190" height="36" rx="6" fill="#0f172a" stroke="#38bdf8" stroke-width="2"/>
                                                <text x="160" y="132" font-size="10" fill="#38bdf8" font-weight="bold" text-anchor="middle">prom = (n1 + n2 + n3) / 3</text>
                                                <line x1="160" y1="146" x2="160" y2="162" stroke="#64748b" stroke-width="2"/>

                                                <!-- Decisión (Rombo) -->
                                                <polygon id="progL1NodeDecision" points="160,162 225,190 160,218 95,190" fill="#0f172a" stroke="#f59e0b" stroke-width="2"/>
                                                <text x="160" y="194" font-size="10" fill="#fbbf24" font-weight="bold" text-anchor="middle">prom &gt;= 70?</text>

                                                <!-- Ramas Salida -->
                                                <line x1="225" y1="190" x2="275" y2="190" stroke="#10b981" stroke-width="2"/>
                                                <text x="245" y="183" font-size="9" fill="#34d399" font-weight="bold">SÍ</text>
                                                <rect id="progL1NodePass" x="235" y="205" width="75" height="28" rx="4" fill="#065f46" stroke="#10b981" stroke-width="1.5"/>
                                                <text x="272" y="223" font-size="9" fill="#ffffff" font-weight="bold" text-anchor="middle">APROBADO</text>

                                                <line x1="95" y1="190" x2="45" y2="190" stroke="#f43f5e" stroke-width="2"/>
                                                <text x="72" y="183" font-size="9" fill="#fb7185" font-weight="bold">NO</text>
                                                <rect id="progL1NodeFail" x="10" y="205" width="78" height="28" rx="4" fill="#881337" stroke="#f43f5e" stroke-width="1.5"/>
                                                <text x="49" y="223" font-size="9" fill="#ffffff" font-weight="bold" text-anchor="middle">REPROBADO</text>
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="row g-2 mb-3">
                                            <div class="col-4">
                                                <label for="progL1N1" class="form-label small text-slate-300 mb-1">Nota 1 ($n_1$):</label>
                                                <input type="number" id="progL1N1" class="form-control form-control-sm bg-dark text-white border-primary font-monospace" value="85" min="0" max="100">
                                            </div>
                                            <div class="col-4">
                                                <label for="progL1N2" class="form-label small text-slate-300 mb-1">Nota 2 ($n_2$):</label>
                                                <input type="number" id="progL1N2" class="form-control form-control-sm bg-dark text-white border-primary font-monospace" value="90" min="0" max="100">
                                            </div>
                                            <div class="col-4">
                                                <label for="progL1N3" class="form-label small text-slate-300 mb-1">Nota 3 ($n_3$):</label>
                                                <input type="number" id="progL1N3" class="form-control form-control-sm bg-dark text-white border-primary font-monospace" value="80" min="0" max="100">
                                            </div>
                                        </div>

                                        <div class="row g-2 text-center mb-3">
                                            <div class="col-4">
                                                <div class="p-2 rounded-3 border border-primary bg-dark">
                                                    <div class="text-xs text-info">Suma Total</div>
                                                    <div class="fw-bold font-monospace small text-white" id="progL1SumDisplay">255</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="p-2 rounded-3 border border-info bg-dark">
                                                    <div class="text-xs text-info">Promedio</div>
                                                    <div class="fw-bold font-monospace small text-white" id="progL1AvgDisplay">85.00</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="p-2 rounded-3 border border-success bg-dark">
                                                    <div class="text-xs text-emerald">Salida</div>
                                                    <div class="fw-bold font-monospace small text-white" id="progL1OutDisplay">Aprobado</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-light" id="progL1ResetBtn">Reiniciar</button>
                                            <button type="button" class="btn btn-sm btn-prog fw-bold flex-grow-1" id="progL1StepBtn">Ejecutar siguiente paso ▷</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="prog-sim-console" id="progL1Console"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-prog-goto="l1-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-prog fw-bold px-4" data-prog-goto="l1-pdf">Siguiente: Guía Visual 01 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 1 · ITEM 4: GUÍA PDF (Guia_1_Algoritmos_y_Diagramas_ANSI.pdf)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l1-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="prog-type-badge prog-badge-pdf">📄 Lección 1 · Guía Visual Incorporada</span>
                            <div class="d-flex align-items-center gap-2">
                                <code class="small">Guia_1_Algoritmos_y_Diagramas_ANSI.pdf</code>
                                <button type="button" class="btn btn-prog btn-sm fw-bold" data-print-guide="progGuideContent-1" data-guide-title="Guía 01 - Algoritmos y Diagramación ANSI">
                                    🖨️ Imprimir / Guardar PDF
                                </button>
                            </div>
                        </div>

                        <div class="prog-guide-container mb-4" id="progGuideContent-1">
                            <header class="border-bottom border-4 border-primary pb-3 mb-4">
                                <p class="text-xs fw-bold text-primary text-uppercase tracking-wider mb-1">Módulo: Fundamentos de Programación | Nivel: Inicial</p>
                                <h2 class="h3 fw-extrabold text-dark mb-1">GUÍA 01: Algoritmos y Diagramación ANSI</h2>
                                <p class="text-secondary small mb-0">Traduciendo problemas cotidianos a flujos lógicos estructurados (Entrada, Proceso y Salida).</p>
                            </header>

                            <section class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="prog-guide-step-num bg-primary-subtle text-primary me-3">1</div>
                                    <h3 class="h5 fw-bold mb-0">Arquitectura de Todo Programa (E-P-S)</h3>
                                </div>
                                <div class="p-4 rounded-3 bg-light border">
                                    <p class="small mb-3">Cualquier algoritmo en ingeniería sigue una línea de ensamblaje lógica de tres fases consecutivas:</p>
                                    <div class="row g-3">
                                        <div class="col-12 col-md-4">
                                            <div class="bg-white p-3 rounded shadow-xs border-top border-4 border-primary h-100">
                                                <h4 class="h6 fw-bold text-primary mb-1">1. Entrada (Input)</h4>
                                                <p class="small text-secondary mb-0">Captura de parámetros iniciales mediante teclado, archivo o petición web.</p>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="bg-white p-3 rounded shadow-xs border-top border-4 border-indigo h-100" style="border-color:#4f46e5 !important;">
                                                <h4 class="h6 fw-bold mb-1" style="color:#4f46e5;">2. Proceso (Transformación)</h4>
                                                <p class="small text-secondary mb-0">Cálculos aritméticos, validaciones y manipulación en memoria RAM.</p>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="bg-white p-3 rounded shadow-xs border-top border-4 border-success h-100">
                                                <h4 class="h6 fw-bold text-success mb-1">3. Salida (Output)</h4>
                                                <p class="small text-secondary mb-0">Despliegue claro del resultado obtenido al usuario o sistema destino.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="prog-guide-step-num bg-primary-subtle text-primary me-3">2</div>
                                    <h3 class="h5 fw-bold mb-0">Simbología Estándar ANSI para Diagramas de Flujo</h3>
                                </div>
                                <div class="table-responsive rounded-3 overflow-hidden shadow-sm">
                                    <table class="table table-dark table-hover align-middle small mb-0">
                                        <thead>
                                            <tr class="text-uppercase text-secondary">
                                                <th class="p-3">Símbolo ANSI</th>
                                                <th class="p-3">Nombre Técnico</th>
                                                <th class="p-3">Función Lógica</th>
                                                <th class="p-3">Equivalente en Código</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="p-3 fw-bold text-info">Óvalo / Terminal</td>
                                                <td class="p-3">Inicio / Fin</td>
                                                <td class="p-3">Delimita el punto de arranque y cierre del algoritmo.</td>
                                                <td class="p-3"><code>main() / return 0</code></td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 fw-bold text-primary">Paralelogramo</td>
                                                <td class="p-3">Entrada / Salida</td>
                                                <td class="p-3">Solicita datos al usuario o imprime mensajes.</td>
                                                <td class="p-3"><code>input() / print()</code></td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 fw-bold text-success">Rectángulo</td>
                                                <td class="p-3">Proceso</td>
                                                <td class="p-3">Ejecuta asignaciones y fórmulas matemáticas.</td>
                                                <td class="p-3"><code>total = a + b;</code></td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 fw-bold text-warning">Rombo</td>
                                                <td class="p-3">Decisión Lógica</td>
                                                <td class="p-3">Bifurca el flujo en dos caminos (Verdadero / Falso).</td>
                                                <td class="p-3"><code>if (condicion)</code></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-prog-goto="l1-simulador">← Anterior: Simulador</button>
                            <button type="button" class="btn btn-prog fw-bold px-4" data-prog-goto="l2-lectura">Ir a Lección 2: Variables y Operadores →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 2 · ITEM 1: LECTURA (Variables, Tipos de Datos y Operadores)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l2-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <div>
                                <span class="prog-type-badge prog-badge-reading mb-1">📖 Lección 2 · Lectura Activa</span>
                                <h2 class="h3 fw-bold mb-0">Variables, Tipos de Datos y Operadores (Gestión de Memoria)</h2>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-prog fw-semibold" data-prog-note-toggle="l2">
                                📝 Guardar nota
                            </button>
                        </div>

                        <div class="prog-note-drawer d-none" id="progNoteDrawer-l2">
                            <label for="progNoteInput-l2" class="form-label small fw-bold text-dark mb-1">📝 Tus apuntes de la Lección 2 (Variables y Operadores):</label>
                            <textarea id="progNoteInput-l2" class="form-control form-control-sm mb-2" rows="2" placeholder="Anota reglas de nombres de variables o jerarquía de operadores..."></textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-primary fw-semibold" id="progNoteStatus-l2"></span>
                                <button type="button" class="btn btn-xs btn-prog fw-bold px-3" id="progNoteSave-l2">Guardar apunte</button>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <strong>Tema:</strong> Tipos primitivos (<code>int</code>, <code>float</code>, <code>string</code>, <code>bool</code>), constantes y precedencia de operadores.
                        </p>

                        <h3 class="h5 fw-bold text-prog mb-2">¿Qué es? (Introducción Teórica)</h3>
                        <p class="text-secondary leading-relaxed mb-3">
                            Una <strong>variable</strong> es un espacio reservado en la memoria RAM identificado con un nombre simbólico donde se almacena un dato que puede cambiar durante la ejecución del programa. Para optimizar memoria y evitar errores de tipo, los lenguajes clasifican la información en <strong>tipos de datos primitivos</strong>:
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-3">
                                <div class="p-3 rounded-4 border bg-light h-100">
                                    <span class="badge bg-primary mb-2 code-font">int</span>
                                    <h4 class="h6 fw-bold mb-1">Enteros</h4>
                                    <p class="small text-secondary mb-0">Números exactos sin punto decimal (ej. <code>edad = 20</code>, <code>intentos = 3</code>).</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="p-3 rounded-4 border bg-light h-100">
                                    <span class="badge bg-info text-dark mb-2 code-font">float / double</span>
                                    <h4 class="h6 fw-bold mb-1">Flotantes</h4>
                                    <p class="small text-secondary mb-0">Números reales con parte decimal (ej. <code>precio = 149.90</code>, <code>pi = 3.1416</code>).</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="p-3 rounded-4 border bg-light h-100">
                                    <span class="badge bg-secondary mb-2 code-font">string</span>
                                    <h4 class="h6 fw-bold mb-1">Cadenas de Texto</h4>
                                    <p class="small text-secondary mb-0">Secuencias de caracteres entre comillas (ej. <code>nombre = "Ana"</code>).</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="p-3 rounded-4 border border-primary-subtle h-100" style="background: rgba(219,234,254,0.45);">
                                    <span class="badge bg-success mb-2 code-font">bool</span>
                                    <h4 class="h6 fw-bold mb-1">Booleanos</h4>
                                    <p class="small text-secondary mb-0">Valores lógicos binarios: únicamente <code>true</code> (verdadero) o <code>false</code> (falso).</p>
                                </div>
                            </div>
                        </div>

                        <h3 class="h5 fw-bold text-prog mb-3">Jerarquía de Evaluación de Operadores</h3>
                        <p class="small text-secondary mb-3">Cuando una expresión combina varios operadores, el compilador sigue este orden estricto de prioridad:</p>

                        <div class="prog-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">1. Paréntesis y Agrupación:</div>
                                <div class="small text-muted">Se evalúa siempre desde el paréntesis más interno hacia afuera</div>
                            </div>
                            <div class="prog-formula-math"><code>( ... )</code></div>
                        </div>

                        <div class="prog-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">2. Multiplicación, División y Módulo:</div>
                                <div class="small text-muted">Tienen mayor prioridad que suma y resta; <code>%</code> devuelve el residuo entero</div>
                            </div>
                            <div class="prog-formula-math"><code>*   /   %</code></div>
                        </div>

                        <div class="prog-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                            <div>
                                <div class="fw-bold text-dark">3. Suma, Resta, Relacionales y Lógicos:</div>
                                <div class="small text-muted">Se resuelven después de los productos y antes de la asignación <code>=</code></div>
                            </div>
                            <div class="prog-formula-math"><code>+  -  &gt;  &lt;  ==  &amp;&amp;  ||</code></div>
                        </div>

                        <div class="prog-tips-accordion mb-4">
                            <button class="prog-tips-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#progTipsL2" aria-expanded="true">
                                <span>✦ Tips para el Reto ABP: Evaluador de Expresiones</span>
                                <span>[ ˅ ]</span>
                            </button>
                            <div id="progTipsL2" class="collapse show">
                                <div class="prog-tips-body">
                                    No confundas el operador de <strong>asignación</strong> (<code>=</code>, que guarda un valor en memoria) con el operador de <strong>comparación de igualdad</strong> (<code>==</code>, que pregunta si dos valores son iguales y devuelve <code>true</code> o <code>false</code>). Asimismo, recuerda que <code>17 % 5</code> da como resultado <code>2</code> (el residuo de dividir 17 entre 5).
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                            <div class="d-flex align-items-center gap-2 prog-feedback-group">
                                <span class="small text-muted">¿Útil?</span>
                                <button type="button" class="prog-feedback-btn" data-prog-feedback="up" aria-label="Me gustó">👍</button>
                                <button type="button" class="prog-feedback-btn" data-prog-feedback="down" aria-label="No me gustó">👎</button>
                                <span class="small text-prog fw-semibold prog-feedback-msg"></span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-prog btn-sm fw-bold" data-prog-goto="l2-video">
                                    ▶ Vídeo Variables
                                </button>
                                <button type="button" class="btn btn-prog fw-bold px-4 shadow-prog" data-prog-goto="l2-reto">
                                    Ir al Reto ABP: Evaluador de Expresiones ➔
                                </button>
                            </div>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 2 · ITEM 2: VÍDEO (Pop-up YouTube)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l2-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="prog-type-badge prog-badge-reading">▶ Lección 2 · Recursos Audiovisuales (Pop-up)</span>
                            <span class="text-muted small">Video: "Variables, Tipos de Datos y Operadores"</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Variables, Tipos de Datos y Operadores</h2>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-primary text-white mb-2">Video Recomendado</span>
                                        <h3 class="h6 fw-bold mb-1">Variables y Tipos de Datos en Programación</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>TodoCode</strong></p>
                                        <p class="small text-secondary mb-3">Comprende cómo se almacenan enteros, decimales, cadenas y booleanos en memoria.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-prog btn-sm fw-bold px-3" data-open-prog-video="l2-video" data-video-mode="main">
                                            ▶ Reproducir en Modal
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=s2d0_g6H40o" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-secondary mb-2">Video Alternativo</span>
                                        <h3 class="h6 fw-bold mb-1">Operadores Aritméticos, Relacionales y Lógicos</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>Programación ATS</strong></p>
                                        <p class="small text-secondary mb-3">Ejercicios prácticos aplicando jerarquía de operaciones y módulo.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-prog btn-sm fw-bold px-3" data-open-prog-video="l2-video" data-video-mode="alt">
                                            ▶ Reproducir Alternativo
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=dN4C1e4g7zM" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 border bg-white small mb-4">
                            <strong>🌐 Recurso Web Recomendado:</strong>
                            <a href="https://pythontutor.com" target="_blank" rel="noopener noreferrer" class="fw-bold text-prog">Python Tutor (pythontutor.com)</a> — Visualizador en línea paso a paso del estado de las variables en memoria RAM.
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-prog-goto="l2-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-prog fw-bold px-4" data-prog-goto="l2-reto">Siguiente: Reto ABP Evaluador ⚡</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 2 · ITEM 3: RETO ABP (Evaluador de Expresiones y Precedencia)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l2-reto">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="prog-type-badge prog-badge-abp">⚡ Lección 2 · Reto ABP</span>
                            <span class="text-muted small">Precedencia de Operadores y Tipos</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Reto ABP: Evaluador de Expresiones y Jerarquía de Operadores</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Misión:</strong> Ajusta los valores de las variables <code>a</code>, <code>b</code> y <code>c</code>, selecciona una expresión y verifica cómo el evaluador resuelve primero los paréntesis, multiplicaciones y módulo antes de sumar o comparar.
                        </p>

                        <div class="prog-sim-box mb-4">
                            <div class="prog-sim-topbar">
                                <span class="fw-bold text-info small">⚡ Evaluador de Expresiones · Precedencia de Operadores</span>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-info text-dark font-monospace" id="progL2TypeBadge">Tipo: int</span>
                                    <span class="badge bg-success text-white font-monospace" id="progL2ResultBadge">Resultado = 22</span>
                                </div>
                            </div>
                            <div class="prog-sim-body">
                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-md-6">
                                        <label for="progL2ExprSelect" class="form-label small text-slate-300 mb-1">Expresión a Evaluar:</label>
                                        <select id="progL2ExprSelect" class="form-select form-select-sm bg-dark text-white border-secondary font-monospace">
                                            <option value="expr1" selected>resultado = a + b * c</option>
                                            <option value="expr2">resultado = (a + b) * c</option>
                                            <option value="expr3">residuo = (a * b) % c</option>
                                            <option value="expr4">valido = (a + b &gt; 15) &amp;&amp; (c != 0)</option>
                                        </select>
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <label for="progL2VarA" class="form-label small text-info mb-1">int a:</label>
                                        <input type="number" id="progL2VarA" class="form-control form-control-sm bg-dark text-white border-info font-monospace" value="4" min="-50" max="50">
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <label for="progL2VarB" class="form-label small text-info mb-1">int b:</label>
                                        <input type="number" id="progL2VarB" class="form-control form-control-sm bg-dark text-white border-info font-monospace" value="6" min="-50" max="50">
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <label for="progL2VarC" class="form-label small text-info mb-1">int c:</label>
                                        <input type="number" id="progL2VarC" class="form-control form-control-sm bg-dark text-white border-info font-monospace" value="3" min="1" max="50">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mb-3">
                                    <button type="button" class="btn btn-sm btn-prog fw-bold px-4" id="progL2EvalBtn">
                                        ⚡ Evaluar Paso a Paso
                                    </button>
                                </div>

                                <div class="prog-sim-console" id="progL2Console"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-prog-goto="l2-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-prog fw-bold px-4" data-prog-goto="l2-pdf">Siguiente: Guía Visual 02 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 2 · ITEM 4: GUÍA PDF (Guia_2_Variables_y_Operadores.pdf)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l2-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="prog-type-badge prog-badge-pdf">📄 Lección 2 · Guía Visual Incorporada</span>
                            <div class="d-flex align-items-center gap-2">
                                <code class="small">Guia_2_Variables_y_Operadores.pdf</code>
                                <button type="button" class="btn btn-prog btn-sm fw-bold" data-print-guide="progGuideContent-2" data-guide-title="Guía 02 - Variables, Tipos de Datos y Operadores">
                                    🖨️ Imprimir / Guardar PDF
                                </button>
                            </div>
                        </div>

                        <div class="prog-guide-container mb-4" id="progGuideContent-2" style="border-top: 8px solid #4f46e5;">
                            <header class="border-bottom border-4 pb-3 mb-4" style="border-color:#4f46e5 !important;">
                                <p class="text-xs fw-bold text-uppercase tracking-wider mb-1" style="color:#4f46e5;">Módulo: Fundamentos de Programación | Lección 2</p>
                                <h2 class="h3 fw-extrabold text-dark mb-1">GUÍA 02: Variables, Tipos de Datos y Operadores</h2>
                                <p class="text-secondary small mb-0">Referencia rápida de tipos primitivos, reglas de identificadores y jerarquía de evaluación.</p>
                            </header>

                            <section class="mb-4">
                                <h3 class="h5 fw-bold text-dark mb-3">1. Reglas para Nombrar Identificadores (Variables)</h3>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="border border-2 border-success-subtle rounded-4 p-3 bg-success-subtle bg-opacity-10 h-100">
                                            <h4 class="h6 fw-bold text-success mb-2">✓ Nombres Válidos (Buenas Prácticas)</h4>
                                            <ul class="small text-secondary mb-0">
                                                <li>Iniciar con letra o guion bajo: <code>totalVenta</code>, <code>_contador</code>.</li>
                                                <li>Usar notación camelCase o snake_case: <code>promedioFinal</code>, <code>edad_usuario</code>.</li>
                                                <li>Ser descriptivos respecto al dato que almacenan.</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="border border-2 border-danger-subtle rounded-4 p-3 bg-danger-subtle bg-opacity-10 h-100">
                                            <h4 class="h6 fw-bold text-danger mb-2">✕ Nombres Inválidos (Error de Sintaxis)</h4>
                                            <ul class="small text-secondary mb-0">
                                                <li>Iniciar con un número: <code>1alumno</code>, <code>2dato</code>.</li>
                                                <li>Contener espacios o símbolos especiales: <code>total venta</code>, <code>precio$</code>.</li>
                                                <li>Usar palabras reservadas del lenguaje: <code>if</code>, <code>while</code>, <code>return</code>.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section>
                                <h3 class="h5 fw-bold text-dark mb-3">2. Tabla de Precedencia de Operadores</h3>
                                <div class="table-responsive rounded-3 overflow-hidden shadow-sm">
                                    <table class="table table-dark table-hover align-middle small mb-0">
                                        <thead>
                                            <tr class="text-uppercase text-secondary">
                                                <th class="p-3">Prioridad</th>
                                                <th class="p-3">Categoría</th>
                                                <th class="p-3">Operadores</th>
                                                <th class="p-3">Ejemplo y Resultado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="p-3 fw-bold text-info">Nivel 1 (Alta)</td>
                                                <td class="p-3">Agrupación</td>
                                                <td class="p-3"><code>( )</code></td>
                                                <td class="p-3"><code>(2 + 3) * 4</code> → <code>20</code></td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 fw-bold text-primary">Nivel 2</td>
                                                <td class="p-3">Multiplicativos</td>
                                                <td class="p-3"><code>*  /  %</code></td>
                                                <td class="p-3"><code>10 + 15 % 4</code> → <code>13</code></td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 fw-bold text-success">Nivel 3</td>
                                                <td class="p-3">Aditivos</td>
                                                <td class="p-3"><code>+  -</code></td>
                                                <td class="p-3"><code>20 - 5 + 3</code> → <code>18</code></td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 fw-bold text-warning">Nivel 4</td>
                                                <td class="p-3">Relacionales</td>
                                                <td class="p-3"><code>&lt;  &lt;=  &gt;  &gt;=  ==  !=</code></td>
                                                <td class="p-3"><code>10 &gt;= 10</code> → <code>true</code></td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 fw-bold text-danger">Nivel 5 (Baja)</td>
                                                <td class="p-3">Lógicos</td>
                                                <td class="p-3"><code>!</code> luego <code>&amp;&amp;</code> luego <code>||</code></td>
                                                <td class="p-3"><code>true &amp;&amp; false</code> → <code>false</code></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-prog-goto="l2-reto">← Anterior: Reto ABP</button>
                            <button type="button" class="btn btn-prog fw-bold px-4" data-prog-goto="l3-lectura">Ir a Lección 3: Estructuras Condicionales →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 3 · ITEM 1: LECTURA (Estructuras Condicionales if / else)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l3-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <div>
                                <span class="prog-type-badge prog-badge-reading mb-1">📖 Lección 3 · Lectura Activa</span>
                                <h2 class="h3 fw-bold mb-0">Estructuras Condicionales (<code>if / else if / else</code>)</h2>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-prog fw-semibold" data-prog-note-toggle="l3">
                                📝 Guardar nota
                            </button>
                        </div>

                        <div class="prog-note-drawer d-none" id="progNoteDrawer-l3">
                            <label for="progNoteInput-l3" class="form-label small fw-bold text-dark mb-1">📝 Tus apuntes de la Lección 3 (Condicionales):</label>
                            <textarea id="progNoteInput-l3" class="form-control form-control-sm mb-2" rows="2" placeholder="Anota diferencias entre && (AND) y || (OR)..."></textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-primary fw-semibold" id="progNoteStatus-l3"></span>
                                <button type="button" class="btn btn-xs btn-prog fw-bold px-3" id="progNoteSave-l3">Guardar apunte</button>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <strong>Tema:</strong> Toma de decisiones lógicas, bifurcación de flujo y operadores booleanos (<code>&amp;&amp;</code>, <code>||</code>, <code>!</code>).
                        </p>

                        <h3 class="h5 fw-bold text-prog mb-2">¿Qué es? (Introducción Teórica)</h3>
                        <p class="text-secondary leading-relaxed mb-3">
                            Un programa inteligente no siempre ejecuta todas sus líneas en orden lineal: necesita tomar decisiones según los datos disponibles. Las <strong>estructuras condicionales</strong> evalúan una expresión booleana (que resulta en <code>true</code> o <code>false</code>) para decidir qué bloque de instrucciones ejecutar:
                        </p>

                        <div class="d-flex flex-column gap-3 mb-4">
                            <div class="p-3 rounded-4 border bg-light">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-1">
                                    <h4 class="h6 fw-bold text-prog mb-0">1. Condicional Simple (<code>if</code>)</h4>
                                    <code class="small bg-dark text-info px-2 py-1 rounded">if (condicion) { accion(); }</code>
                                </div>
                                <p class="small text-secondary mb-0">
                                    Ejecuta el bloque interno únicamente si la condición se cumple (<code>true</code>). Si es falsa, continúa con el resto del programa sin hacer nada.
                                </p>
                            </div>

                            <div class="p-3 rounded-4 border bg-light">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-1">
                                    <h4 class="h6 fw-bold text-prog mb-0">2. Condicional Doble (<code>if / else</code>)</h4>
                                    <code class="small bg-dark text-emerald px-2 py-1 rounded">if (cond) { rutaA(); } else { rutaB(); }</code>
                                </div>
                                <p class="small text-secondary mb-0">
                                    Garantiza que exactamente uno de dos caminos mutuamente excluyentes se ejecute. Nunca se ejecutan ambos bloques a la vez.
                                </p>
                            </div>

                            <div class="p-3 rounded-4 border bg-light">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-1">
                                    <h4 class="h6 fw-bold text-prog mb-0">3. Condicional Encadenada (<code>else if</code>)</h4>
                                    <code class="small bg-dark text-warning px-2 py-1 rounded">if (c1) { ... } else if (c2) { ... } else { ... }</code>
                                </div>
                                <p class="small text-secondary mb-0">
                                    Permite evaluar múltiples rangos o casos en cascada, deteniéndose en la primera condición verdadera que encuentre.
                                </p>
                            </div>
                        </div>

                        <h3 class="h5 fw-bold text-prog mb-3">Operadores Lógicos Compuestos</h3>

                        <div class="prog-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Conjunción Lógica (AND):</div>
                                <div class="small text-muted">Devuelve <code>true</code> solo si <strong>ambas</strong> condiciones son verdaderas</div>
                            </div>
                            <div class="prog-formula-math"><code>condicion1 &amp;&amp; condicion2</code></div>
                        </div>

                        <div class="prog-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                            <div>
                                <div class="fw-bold text-dark">Disyunción Lógica (OR):</div>
                                <div class="small text-muted">Devuelve <code>true</code> si <strong>al menos una</strong> de las condiciones es verdadera</div>
                            </div>
                            <div class="prog-formula-math"><code>condicion1 || condicion2</code></div>
                        </div>

                        <div class="prog-tips-accordion mb-4">
                            <button class="prog-tips-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#progTipsL3" aria-expanded="true">
                                <span>✦ Tips para el Depurador de Rutas Condicionales</span>
                                <span>[ ˅ ]</span>
                            </button>
                            <div id="progTipsL3" class="collapse show">
                                <div class="prog-tips-body">
                                    En una cadena <code>if / else if / else</code>, el orden importa: siempre coloca primero las condiciones más restrictivas (por ejemplo, <code>promedio &gt;= 90</code> antes de <code>promedio &gt;= 70</code>), de lo contrario la condición general capturará el flujo antes de llegar a la específica.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                            <div class="d-flex align-items-center gap-2 prog-feedback-group">
                                <span class="small text-muted">¿Útil?</span>
                                <button type="button" class="prog-feedback-btn" data-prog-feedback="up">👍</button>
                                <button type="button" class="prog-feedback-btn" data-prog-feedback="down">👎</button>
                                <span class="small text-prog fw-semibold prog-feedback-msg"></span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-prog btn-sm fw-bold" data-prog-goto="l3-video">
                                    ▶ Vídeo Condicionales
                                </button>
                                <button type="button" class="btn btn-prog fw-bold px-4 shadow-prog" data-prog-goto="l3-simulador">
                                    Ir al Simulador: Depurador Condicional ➔
                                </button>
                            </div>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 3 · ITEM 2: VÍDEO (Pop-up YouTube)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l3-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="prog-type-badge prog-badge-reading">▶ Lección 3 · Recursos Audiovisuales (Pop-up)</span>
                            <span class="text-muted small">Video: "Estructuras Condicionales if / else"</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Toma de Decisiones con <code>if</code>, <code>else if</code> y <code>else</code></h2>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-primary text-white mb-2">Video Recomendado</span>
                                        <h3 class="h6 fw-bold mb-1">Estructuras Condicionales if, else if y else</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>Fazt</strong></p>
                                        <p class="small text-secondary mb-3">Explica cómo controlar el flujo de ejecución mediante condiciones simples, dobles y múltiples.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-prog btn-sm fw-bold px-3" data-open-prog-video="l3-video" data-video-mode="main">
                                            ▶ Reproducir en Modal
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=2yC7-9_vK7k" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-secondary mb-2">Video Alternativo</span>
                                        <h3 class="h6 fw-bold mb-1">Tablas de Verdad y Operadores Lógicos AND, OR, NOT</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>MoureDev</strong></p>
                                        <p class="small text-secondary mb-3">Cómo combinar múltiples expresiones booleanas sin errores de lógica.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-prog btn-sm fw-bold px-3" data-open-prog-video="l3-video" data-video-mode="alt">
                                            ▶ Reproducir Alternativo
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=5l3i2w7t89c" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-prog-goto="l3-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-prog fw-bold px-4" data-prog-goto="l3-simulador">Siguiente: Simulador Condicional ⚙</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 3 · ITEM 3: SIMULADOR (Depurador de Rutas Condicionales)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l3-simulador">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="prog-type-badge prog-badge-sim">⚙ Lección 3 · Simulador Interactivo</span>
                            <span class="text-muted small">Evaluador de Ramas <code>if / else if / else</code></span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Simulador: Depurador de Flujo Condicional y Operadores Lógicos</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Regla de negocio:</strong> Para acreditar con excelencia se requiere <code>calificacion &gt;= 90 &amp;&amp; asistencia &gt;= 85</code>; para acreditar ordinario basta <code>calificacion &gt;= 70 &amp;&amp; asistencia &gt;= 80</code>; de lo contrario entra al bloque <code>else</code>.
                        </p>

                        <div class="prog-sim-box mb-4">
                            <div class="prog-sim-topbar">
                                <span class="fw-bold text-info small">⚙ Depurador Condicional · Evaluación Booleana</span>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-info text-dark font-monospace" id="progL3BranchBadge">Rama: else if (Ordinario)</span>
                                    <span class="badge bg-success text-white font-monospace" id="progL3BoolBadge">true</span>
                                </div>
                            </div>
                            <div class="prog-sim-body">
                                <div class="row g-4 align-items-center mb-3">
                                    <div class="col-12 col-md-6">
                                        <div class="p-3 rounded-3 bg-dark border border-secondary font-monospace small text-slate-200">
                                            <div id="progL3CodeLine1" class="p-1 rounded">if (calif &gt;= 90 &amp;&amp; asist &gt;= 85) {</div>
                                            <div class="ps-3 text-emerald">estatus = "EXCELENCIA";</div>
                                            <div id="progL3CodeLine2" class="p-1 rounded bg-primary bg-opacity-25 border-start border-3 border-info">} else if (calif &gt;= 70 &amp;&amp; asist &gt;= 80) {</div>
                                            <div class="ps-3 text-info">estatus = "ACREDITADO";</div>
                                            <div id="progL3CodeLine3" class="p-1 rounded">} else {</div>
                                            <div class="ps-3 text-danger">estatus = "RECURSAMIENTO";</div>
                                            <div>}</div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between small text-slate-300">
                                                <label for="progL3GradeSlider">Calificación Final (<code>calif</code>):</label>
                                                <span class="fw-bold text-info font-monospace" id="progL3GradeVal">82</span>
                                            </div>
                                            <input type="range" class="form-range" id="progL3GradeSlider" min="0" max="100" step="1" value="82">
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between small text-slate-300">
                                                <label for="progL3AttendSlider">Porcentaje de Asistencia (<code>asist</code>):</label>
                                                <span class="fw-bold text-warning font-monospace" id="progL3AttendVal">88%</span>
                                            </div>
                                            <input type="range" class="form-range" id="progL3AttendSlider" min="50" max="100" step="1" value="88">
                                        </div>
                                        <button type="button" class="btn btn-sm btn-prog fw-bold w-100" id="progL3TestBtn">
                                            Probar caso límite aleatorio ▷
                                        </button>
                                    </div>
                                </div>

                                <div class="prog-sim-console" id="progL3Console"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-prog-goto="l3-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-prog fw-bold px-4" data-prog-goto="l3-pdf">Siguiente: Guía Visual 03 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 3 · ITEM 4: GUÍA PDF (Guia_3_Estructuras_Condicionales.pdf)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l3-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="prog-type-badge prog-badge-pdf">📄 Lección 3 · Guía Visual Incorporada</span>
                            <div class="d-flex align-items-center gap-2">
                                <code class="small">Guia_3_Estructuras_Condicionales.pdf</code>
                                <button type="button" class="btn btn-prog btn-sm fw-bold" data-print-guide="progGuideContent-3" data-guide-title="Guía 03 - Estructuras Condicionales y Tablas de Verdad">
                                    🖨️ Imprimir / Guardar PDF
                                </button>
                            </div>
                        </div>

                        <div class="prog-guide-container mb-4" id="progGuideContent-3" style="border-top: 8px solid #f59e0b;">
                            <header class="pb-3 mb-4 border-bottom">
                                <p class="text-xs fw-bold text-warning text-uppercase tracking-wider mb-1">Módulo: Fundamentos de Programación | Lección 3</p>
                                <h2 class="h3 fw-extrabold text-dark mb-1">GUÍA 03: Estructuras Condicionales y Tablas de Verdad</h2>
                                <p class="text-secondary small mb-0">Control de flujo mediante expresiones booleanas y operadores lógicos.</p>
                            </header>

                            <section>
                                <h3 class="h5 fw-bold text-dark mb-3">Tabla de Verdad de Operadores Lógicos</h3>
                                <div class="table-responsive rounded-3 overflow-hidden shadow-sm">
                                    <table class="table table-dark table-hover align-middle small text-center mb-0">
                                        <thead>
                                            <tr class="text-uppercase text-secondary">
                                                <th class="p-3">A</th>
                                                <th class="p-3">B</th>
                                                <th class="p-3">A &amp;&amp; B (AND)</th>
                                                <th class="p-3">A || B (OR)</th>
                                                <th class="p-3">!A (NOT)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="p-3 text-success fw-bold">true</td>
                                                <td class="p-3 text-success fw-bold">true</td>
                                                <td class="p-3"><span class="badge bg-success">true</span></td>
                                                <td class="p-3"><span class="badge bg-success">true</span></td>
                                                <td class="p-3"><span class="badge bg-danger">false</span></td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 text-success fw-bold">true</td>
                                                <td class="p-3 text-danger fw-bold">false</td>
                                                <td class="p-3"><span class="badge bg-danger">false</span></td>
                                                <td class="p-3"><span class="badge bg-success">true</span></td>
                                                <td class="p-3"><span class="badge bg-danger">false</span></td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 text-danger fw-bold">false</td>
                                                <td class="p-3 text-success fw-bold">true</td>
                                                <td class="p-3"><span class="badge bg-danger">false</span></td>
                                                <td class="p-3"><span class="badge bg-success">true</span></td>
                                                <td class="p-3"><span class="badge bg-success">true</span></td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 text-danger fw-bold">false</td>
                                                <td class="p-3 text-danger fw-bold">false</td>
                                                <td class="p-3"><span class="badge bg-danger">false</span></td>
                                                <td class="p-3"><span class="badge bg-danger">false</span></td>
                                                <td class="p-3"><span class="badge bg-success">true</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-prog-goto="l3-simulador">← Anterior: Simulador</button>
                            <button type="button" class="btn btn-prog fw-bold px-4" data-prog-goto="l4-lectura">Ir a Lección 4: Estructuras Cíclicas →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 4 · ITEM 1: LECTURA (Estructuras Cíclicas while / for)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l4-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <div>
                                <span class="prog-type-badge prog-badge-reading mb-1">📖 Lección 4 · Lectura Activa</span>
                                <h2 class="h3 fw-bold mb-0">Estructuras Cíclicas (<code>while</code>, <code>do-while</code> y <code>for</code>)</h2>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-prog fw-semibold" data-prog-note-toggle="l4">
                                📝 Guardar nota
                            </button>
                        </div>

                        <div class="prog-note-drawer d-none" id="progNoteDrawer-l4">
                            <label for="progNoteInput-l4" class="form-label small fw-bold text-dark mb-1">📝 Tus apuntes de la Lección 4 (Ciclos):</label>
                            <textarea id="progNoteInput-l4" class="form-control form-control-sm mb-2" rows="2" placeholder="Anota la diferencia entre contador y acumulador..."></textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-primary fw-semibold" id="progNoteStatus-l4"></span>
                                <button type="button" class="btn btn-xs btn-prog fw-bold px-3" id="progNoteSave-l4">Guardar apunte</button>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <strong>Tema:</strong> Iteración controlada, variables de control (contador y acumulador) y prevención de ciclos infinitos.
                        </p>

                        <h3 class="h5 fw-bold text-prog mb-2">¿Qué es? (Introducción Teórica)</h3>
                        <p class="text-secondary leading-relaxed mb-3">
                            Un <strong>ciclo o bucle</strong> permite repetir automáticamente un bloque de instrucciones mientras una condición lógica permanezca verdadera. Para dominar los bucles en ingeniería se utilizan dos patrones fundamentales de variables:
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-4 border border-primary-subtle h-100" style="background: rgba(219,234,254,0.45);">
                                    <h4 class="h6 fw-bold text-prog mb-1">🔢 1. Contador (<code>i = i + 1</code>)</h4>
                                    <p class="small text-secondary mb-0">
                                        Variable entera que se incrementa o decrementa en una <strong>cantidad fija</strong> en cada vuelta del ciclo para llevar el control de las iteraciones.
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-4 border bg-light h-100">
                                    <h4 class="h6 fw-bold text-primary mb-1">📊 2. Acumulador (<code>suma = suma + x</code>)</h4>
                                    <p class="small text-secondary mb-0">
                                        Variable inicializada en <code>0</code> (para sumas) o en <code>1</code> (para productos) que va integrando valores variables en cada iteración.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <h3 class="h5 fw-bold text-prog mb-3">Sintaxis de los Bucles Principales</h3>

                        <div class="prog-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Ciclo <code>for</code> (Iteraciones definidas):</div>
                                <div class="small text-muted">Ideal cuando conoces de antemano el número exacto de repeticiones $N$</div>
                            </div>
                            <div class="prog-formula-math"><code>for (int i = 1; i &lt;= N; i++) { ... }</code></div>
                        </div>

                        <div class="prog-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                            <div>
                                <div class="fw-bold text-dark">Ciclo <code>while</code> (Condición previa):</div>
                                <div class="small text-muted">Evalúa la condición antes de entrar; puede ejecutarse 0 o más veces</div>
                            </div>
                            <div class="prog-formula-math"><code>while (condicion) { ... actualizar(); }</code></div>
                        </div>

                        <div class="prog-tips-accordion mb-4">
                            <button class="prog-tips-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#progTipsL4" aria-expanded="true">
                                <span>✦ Tips para el Reto ABP: Trazas de Bucle</span>
                                <span>[ ˅ ]</span>
                            </button>
                            <div id="progTipsL4" class="collapse show">
                                <div class="prog-tips-body">
                                    Para evitar un <strong>bucle infinito</strong> en un ciclo <code>while</code>, asegúrate siempre de que dentro del cuerpo del bucle exista una instrucción que modifique la variable evaluada en la condición de parada.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                            <div class="d-flex align-items-center gap-2 prog-feedback-group">
                                <span class="small text-muted">¿Útil?</span>
                                <button type="button" class="prog-feedback-btn" data-prog-feedback="up">👍</button>
                                <button type="button" class="prog-feedback-btn" data-prog-feedback="down">👎</button>
                                <span class="small text-prog fw-semibold prog-feedback-msg"></span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-prog btn-sm fw-bold" data-prog-goto="l4-video">
                                    ▶ Vídeo Ciclos
                                </button>
                                <button type="button" class="btn btn-prog fw-bold px-4 shadow-prog" data-prog-goto="l4-reto">
                                    Ir al Reto ABP: Simulador de Bucles ➔
                                </button>
                            </div>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 4 · ITEM 2: VÍDEO (Pop-up YouTube)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l4-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="prog-type-badge prog-badge-reading">▶ Lección 4 · Recursos Audiovisuales (Pop-up)</span>
                            <span class="text-muted small">Video: "Estructuras Cíclicas while y for"</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Bucles e Iteraciones: <code>while</code>, <code>do-while</code> y <code>for</code></h2>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-primary text-white mb-2">Video Recomendado</span>
                                        <h3 class="h6 fw-bold mb-1">Bucles e Iteraciones: while, do-while y for</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>TodoCode</strong></p>
                                        <p class="small text-secondary mb-3">Diferencias prácticas entre ciclos controlados por contador y por centinela.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-prog btn-sm fw-bold px-3" data-open-prog-video="l4-video" data-video-mode="main">
                                            ▶ Reproducir en Modal
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=6iOlB0QLy84" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-secondary mb-2">Video Alternativo</span>
                                        <h3 class="h6 fw-bold mb-1">Cómo evitar Bucles Infinitos y usar Acumuladores</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>Programación ATS</strong></p>
                                        <p class="small text-secondary mb-3">Ejemplos paso a paso de sumatorias y tablas de multiplicar con ciclos.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-prog btn-sm fw-bold px-3" data-open-prog-video="l4-video" data-video-mode="alt">
                                            ▶ Reproducir Alternativo
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=j7S9k3L4p2w" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-prog-goto="l4-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-prog fw-bold px-4" data-prog-goto="l4-reto">Siguiente: Reto ABP Trazas de Bucle ⚡</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 4 · ITEM 3: RETO ABP (Simulador de Trazas de Bucle)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l4-reto">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="prog-type-badge prog-badge-abp">⚡ Lección 4 · Reto ABP</span>
                            <span class="text-muted small">Simulación de Contador y Acumulador</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Reto ABP: Simulador de Trazas de Bucle (<code>for</code>)</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Objetivo:</strong> Ejecuta iteración por iteración el ciclo <code>for (int i = 1; i &lt;= N; i++) { suma += i; }</code> para visualizar cómo evolucionan las variables en memoria hasta cumplir la condición de salida.
                        </p>

                        <div class="prog-sim-box mb-4">
                            <div class="prog-sim-topbar">
                                <span class="fw-bold text-info small">⚡ Motor Iterativo · Bucle for (i = 1 hasta N)</span>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-info text-dark font-monospace" id="progL4IterBadge">Iteración i = 1</span>
                                    <span class="badge bg-success text-white font-monospace" id="progL4SumBadge">suma = 1</span>
                                </div>
                            </div>
                            <div class="prog-sim-body">
                                <div class="row g-3 align-items-center mb-3">
                                    <div class="col-12 col-md-5">
                                        <label for="progL4LimitInput" class="form-label small text-slate-300 mb-1">Límite de Iteraciones ($N$): $3$ a $10$</label>
                                        <input type="range" class="form-range" id="progL4LimitInput" min="3" max="10" step="1" value="5">
                                    </div>
                                    <div class="col-6 col-md-3 text-center">
                                        <div class="p-2 rounded-3 bg-dark border border-info">
                                            <div class="text-xs text-info">Contador (<code>i</code>)</div>
                                            <div class="fw-bold fs-5 font-monospace text-white" id="progL4CounterVal">1</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4 text-center">
                                        <div class="p-2 rounded-3 bg-dark border border-success">
                                            <div class="text-xs text-emerald">Acumulador (<code>suma</code>)</div>
                                            <div class="fw-bold fs-5 font-monospace text-white" id="progL4AccumVal">1</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mb-3">
                                    <button type="button" class="btn btn-sm btn-outline-light" id="progL4ResetBtn">Reiniciar ciclo (i = 1)</button>
                                    <button type="button" class="btn btn-sm btn-prog fw-bold flex-grow-1" id="progL4NextIterBtn">
                                        Avanzar siguiente iteración (i++) ▷
                                    </button>
                                </div>

                                <div class="prog-sim-console" id="progL4Console"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-prog-goto="l4-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-prog fw-bold px-4" data-prog-goto="l4-pdf">Siguiente: Guía Visual 04 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 4 · ITEM 4: GUÍA PDF (Guia_4_Patrones_Iterativos.pdf)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l4-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="prog-type-badge prog-badge-pdf">📄 Lección 4 · Guía Visual Incorporada</span>
                            <div class="d-flex align-items-center gap-2">
                                <code class="small">Guia_4_Patrones_Iterativos.pdf</code>
                                <button type="button" class="btn btn-prog btn-sm fw-bold" data-print-guide="progGuideContent-4" data-guide-title="Guía 04 - Patrones Iterativos: while, do-while y for">
                                    🖨️ Imprimir / Guardar PDF
                                </button>
                            </div>
                        </div>

                        <div class="prog-guide-container mb-4" id="progGuideContent-4" style="border-top: 8px solid #2563eb;">
                            <header class="pb-3 mb-4 border-bottom">
                                <p class="text-xs fw-bold text-prog text-uppercase tracking-wider mb-1">Módulo: Fundamentos de Programación | Lección 4</p>
                                <h2 class="h3 fw-extrabold text-dark mb-1">GUÍA 04: Patrones Iterativos (<code>while</code>, <code>do-while</code> y <code>for</code>)</h2>
                                <p class="text-secondary small mb-0">Cuándo elegir cada estructura repetitiva según la naturaleza del problema.</p>
                            </header>

                            <section class="row g-3">
                                <div class="col-12 col-md-4">
                                    <div class="p-3 rounded-4 border bg-light h-100">
                                        <span class="badge bg-primary mb-2 code-font">for</span>
                                        <h4 class="h6 fw-bold text-dark mb-1">Iteraciones Conocidas</h4>
                                        <p class="small text-secondary mb-0">Úsalo cuando sabes exactamente cuántas veces se repetirá el ciclo (ej. recorrer los 10 elementos de un arreglo).</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="p-3 rounded-4 border bg-light h-100">
                                        <span class="badge bg-info text-dark mb-2 code-font">while</span>
                                        <h4 class="h6 fw-bold text-dark mb-1">Condición Previa</h4>
                                        <p class="small text-secondary mb-0">Úsalo cuando el ciclo depende de un evento o valor centinela (ej. leer datos hasta que el usuario ingrese <code>0</code>).</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="p-3 rounded-4 border bg-light h-100">
                                        <span class="badge bg-success mb-2 code-font">do-while</span>
                                        <h4 class="h6 fw-bold text-dark mb-1">Al Menos Una Vez</h4>
                                        <p class="small text-secondary mb-0">Evalúa la condición al final; ideal para menús interactivos y validación de contraseñas.</p>
                                    </div>
                                </div>
                            </section>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-prog-goto="l4-reto">← Anterior: Reto ABP</button>
                            <button type="button" class="btn btn-prog fw-bold px-4" data-prog-goto="l5-lectura">Ir a Lección 5: Funciones y Arreglos →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 5 · ITEM 1: LECTURA (Funciones y Arreglos)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l5-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <div>
                                <span class="prog-type-badge prog-badge-reading mb-1">📖 Lección 5 · Lectura Activa</span>
                                <h2 class="h3 fw-bold mb-0">Funciones y Arreglos (Modularización y Estructuras de Datos)</h2>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-prog fw-semibold" data-prog-note-toggle="l5">
                                📝 Guardar nota
                            </button>
                        </div>

                        <div class="prog-note-drawer d-none" id="progNoteDrawer-l5">
                            <label for="progNoteInput-l5" class="form-label small fw-bold text-dark mb-1">📝 Tus apuntes de la Lección 5 (Funciones y Arreglos):</label>
                            <textarea id="progNoteInput-l5" class="form-control form-control-sm mb-2" rows="2" placeholder="Anota cómo funcionan los índices base 0 y return..."></textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-primary fw-semibold" id="progNoteStatus-l5"></span>
                                <button type="button" class="btn btn-xs btn-prog fw-bold px-3" id="progNoteSave-l5">Guardar apunte</button>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <strong>Tema:</strong> Subprogramas reutilizables, paso de parámetros, sentencia <code>return</code> y arreglos unidimensionales con índice base 0.
                        </p>

                        <h3 class="h5 fw-bold text-prog mb-2">¿Qué es? (Introducción Teórica)</h3>
                        <p class="text-secondary leading-relaxed mb-3">
                            A medida que los programas crecen, dividir el código en bloques independientes y manejar colecciones de datos se vuelve indispensable:
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-4 border border-primary-subtle bg-light h-100">
                                    <h4 class="h6 fw-bold text-primary mb-1">🧩 1. Funciones (Divide y Vencerás)</h4>
                                    <p class="small text-secondary mb-0">
                                        Una función es un bloque de código con nombre propio que recibe <strong>parámetros</strong> de entrada, realiza una tarea específica y devuelve un resultado mediante <code>return</code>, evitando repetir código.
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-4 border border-indigo-subtle bg-light h-100">
                                    <h4 class="h6 fw-bold mb-1" style="color:#4338ca;">📦 2. Arreglos / Listas (Índices Base 0)</h4>
                                    <p class="small text-secondary mb-0">
                                        Un arreglo almacena múltiples valores del mismo tipo bajo un único nombre. En casi todos los lenguajes modernos, el <strong>primer elemento ocupa la posición <code>0</code></strong> y el último ocupa <code>N - 1</code>.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <h3 class="h5 fw-bold text-prog mb-3">Sintaxis Clave (Para el Inspector de Memoria)</h3>

                        <div class="prog-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Declaración de Función con Retorno:</div>
                                <div class="small text-muted">Recibe parámetros y devuelve el resultado calculado</div>
                            </div>
                            <div class="prog-formula-math"><code>function calcularArea(base, altura) { return base * altura; }</code></div>
                        </div>

                        <div class="prog-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                            <div>
                                <div class="fw-bold text-dark">Acceso y Recorrido de un Arreglo de tamaño $N$:</div>
                                <div class="small text-muted">El índice válido va desde <code>0</code> hasta <code>N - 1</code></div>
                            </div>
                            <div class="prog-formula-math"><code>for (int i = 0; i &lt; N; i++) { procesar(datos[i]); }</code></div>
                        </div>

                        <div class="prog-tips-accordion mb-4">
                            <button class="prog-tips-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#progTipsL5" aria-expanded="true">
                                <span>✦ Tips para el Simulador de Arreglos y Funciones</span>
                                <span>[ ˅ ]</span>
                            </button>
                            <div id="progTipsL5" class="collapse show">
                                <div class="prog-tips-body">
                                    Si un arreglo tiene <strong>5 elementos</strong>, sus índices válidos son <code>0, 1, 2, 3, 4</code>. Intentar acceder a <code>datos[5]</code> produce un error de desbordamiento de índice (<em>IndexOutOfBounds</em>).
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                            <div class="d-flex align-items-center gap-2 prog-feedback-group">
                                <span class="small text-muted">¿Útil?</span>
                                <button type="button" class="prog-feedback-btn" data-prog-feedback="up">👍</button>
                                <button type="button" class="prog-feedback-btn" data-prog-feedback="down">👎</button>
                                <span class="small text-prog fw-semibold prog-feedback-msg"></span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-prog btn-sm fw-bold" data-prog-goto="l5-video">
                                    ▶ Vídeo Funciones
                                </button>
                                <button type="button" class="btn btn-prog fw-bold px-4 shadow-prog" data-prog-goto="l5-simulador">
                                    Ir al Simulador: Inspector de Arreglos ➔
                                </button>
                            </div>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 5 · ITEM 2: VÍDEO (Pop-up YouTube)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l5-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="prog-type-badge prog-badge-reading">▶ Lección 5 · Recursos Audiovisuales (Pop-up)</span>
                            <span class="text-muted small">Video: "Funciones y Arreglos en Programación"</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Funciones y Arreglos Unidimensionales</h2>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-primary text-white mb-2">Video Recomendado</span>
                                        <h3 class="h6 fw-bold mb-1">Funciones y Arreglos (Vectores/Listas) en Programación</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>TodoCode</strong></p>
                                        <p class="small text-secondary mb-3">Cómo almacenar conjuntos de datos en índices base 0 y recorrerlos con ciclos.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-prog btn-sm fw-bold px-3" data-open-prog-video="l5-video" data-video-mode="main">
                                            ▶ Reproducir en Modal
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=9J2h7m4p3wQ" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-secondary mb-2">Video Alternativo</span>
                                        <h3 class="h6 fw-bold mb-1">Paso de Parámetros y Retorno de Valores en Funciones</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>HolaMundo</strong></p>
                                        <p class="small text-secondary mb-3">Modularización limpia y reutilización de código mediante funciones puras.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-prog btn-sm fw-bold px-3" data-open-prog-video="l5-video" data-video-mode="alt">
                                            ▶ Reproducir Alternativo
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=tR8k2M5n9xL" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-prog-goto="l5-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-prog fw-bold px-4" data-prog-goto="l5-simulador">Siguiente: Simulador de Arreglos ⚙</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 5 · ITEM 3: SIMULADOR (Inspector de Memoria: Arreglos y Funciones)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l5-simulador">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="prog-type-badge prog-badge-sim">⚙ Lección 5 · Simulador Interactivo</span>
                            <span class="text-muted small">Arreglos Base 0 y Llamadas a Función</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Simulador: Inspector de Arreglos y Llamadas a Función</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Ejercicio:</strong> Modifica los valores de cada celda del arreglo <code>notas[0..4]</code> y ejecuta las funciones <code>sumarArreglo(notas)</code> o <code>obtenerMaximo(notas)</code> para inspeccionar el valor retornado.
                        </p>

                        <div class="prog-sim-box mb-4">
                            <div class="prog-sim-topbar">
                                <span class="fw-bold text-info small">📦 Memoria RAM · int notas[5] (Índices 0 al 4)</span>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-info text-dark font-monospace" id="progL5LenBadge">notas.length = 5</span>
                                    <span class="badge bg-success text-white font-monospace" id="progL5ReturnBadge">return: 95</span>
                                </div>
                            </div>
                            <div class="prog-sim-body">
                                <div class="row g-2 text-center mb-3">
                                    <?php
                                    $valoresIniciales = [78, 95, 84, 62, 91];
                                    foreach ($valoresIniciales as $idx => $valIni):
                                    ?>
                                        <div class="col">
                                            <div class="p-2 rounded-3 bg-dark border border-primary">
                                                <div class="text-xs text-info font-monospace mb-1">notas[<?= $idx ?>]</div>
                                                <input type="number" class="form-control form-control-sm bg-black text-white text-center border-secondary font-monospace prog-l5-cell" data-index="<?= $idx ?>" value="<?= $valIni ?>" min="0" max="100">
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <button type="button" class="btn btn-sm btn-prog fw-bold" id="progL5MaxBtn">
                                        ▶ Ejecutar obtenerMaximo(notas)
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-info fw-bold" id="progL5SumBtn">
                                        ▶ Ejecutar sumarArreglo(notas)
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-light" id="progL5AvgBtn">
                                        ▶ Ejecutar calcularPromedio(notas)
                                    </button>
                                </div>

                                <div class="prog-sim-console" id="progL5Console"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-prog-goto="l5-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-prog fw-bold px-4" data-prog-goto="l5-pdf">Siguiente: Guía Visual 05 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 5 · ITEM 4: GUÍA PDF (Guia_5_Funciones_y_Arreglos.pdf)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="l5-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="prog-type-badge prog-badge-pdf">📄 Lección 5 · Guía Visual Incorporada</span>
                            <div class="d-flex align-items-center gap-2">
                                <code class="small">Guia_5_Funciones_y_Arreglos.pdf</code>
                                <button type="button" class="btn btn-prog btn-sm fw-bold" data-print-guide="progGuideContent-5" data-guide-title="Guía 05 - Funciones Modulares y Arreglos Base 0">
                                    🖨️ Imprimir / Guardar PDF
                                </button>
                            </div>
                        </div>

                        <div class="prog-guide-container mb-4" id="progGuideContent-5" style="border-top: 8px solid #0284c7;">
                            <header class="pb-3 mb-4 border-bottom">
                                <p class="text-xs fw-bold text-primary text-uppercase tracking-wider mb-1">Módulo: Fundamentos de Programación | Lección 5</p>
                                <h2 class="h3 fw-extrabold text-dark mb-1">GUÍA 05: Funciones Modulares y Arreglos Base 0</h2>
                                <p class="text-secondary small mb-0">Anatomía de una función y mapa de memoria de un arreglo unidimensional.</p>
                            </header>

                            <section class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="border border-2 border-primary-subtle rounded-4 p-4 h-100">
                                        <span class="badge bg-primary text-uppercase mb-2">Anatomía de Función</span>
                                        <p class="small text-secondary mb-2">Toda función se compone de firma (nombre + parámetros), cuerpo lógico y valor de retorno:</p>
                                        <div class="bg-dark text-info p-3 rounded-3 font-monospace small">
                                            function sumar(a, b) {<br>
                                            &nbsp;&nbsp;let total = a + b;<br>
                                            &nbsp;&nbsp;return total;<br>
                                            }
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="border border-2 border-info-subtle rounded-4 p-4 h-100">
                                        <span class="badge bg-info text-dark text-uppercase mb-2">Regla de Índices</span>
                                        <p class="small text-secondary mb-2">Un arreglo de longitud <code>N</code> siempre inicia en el índice <code>0</code> y finaliza en <code>N - 1</code>:</p>
                                        <div class="bg-dark text-white p-3 rounded-3 font-monospace small">
                                            Índice: &nbsp;[0] &nbsp;[1] &nbsp;[2] &nbsp;[3]<br>
                                            Valor: &nbsp;&nbsp;10 &nbsp;&nbsp;20 &nbsp;&nbsp;30 &nbsp;&nbsp;40
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-prog-goto="l5-simulador">← Anterior: Simulador</button>
                            <button type="button" class="btn btn-prog fw-bold px-4 shadow-prog" data-prog-goto="cierre-encuesta">Ir al Módulo de Cierre y Certificación 🎓 →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         MÓDULO DE CIERRE · ITEM 1: ENCUESTA ESCALA LIKERT (1 AL 5)
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="cierre-encuesta">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="prog-type-badge prog-badge-closing">📊 Módulo de Cierre · Instrumento de Evaluación</span>
                            <button type="button" class="btn btn-xs btn-outline-prog fw-semibold" id="progSurveyQuickFillBtn">
                                ⚡ Autocompletar demostración
                            </button>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Instrumento de Evaluación: Encuesta de Satisfacción e Impacto del Módulo Interactivo</h2>
                        <p class="small text-secondary leading-relaxed mb-3">
                            <strong>Instrucciones:</strong> La presente encuesta tiene como propósito conocer tu percepción, nivel de satisfacción y el impacto académico derivado del uso del simulador visual y la metodología de Aprendizaje Basado en Problemas (ABP) durante este módulo. Tus respuestas son confidenciales y serán utilizadas exclusivamente con fines de investigación para evaluar y mejorar la estrategia didáctica en el <strong>Instituto Tecnológico de Ciudad Victoria</strong>.
                        </p>
                        <p class="small text-secondary mb-2">
                            Lee detenidamente cada afirmación y selecciona la opción que mejor refleje tu grado de acuerdo o desacuerdo, utilizando la siguiente escala:
                        </p>

                        <div class="d-flex flex-wrap gap-2 p-2 rounded-3 bg-light border small mb-4">
                            <span class="badge bg-primary">5: Totalmente de acuerdo (TA)</span>
                            <span class="badge bg-info text-dark">4: De acuerdo (DA)</span>
                            <span class="badge bg-secondary">3: Neutral / Indeciso (N)</span>
                            <span class="badge bg-warning text-dark">2: En desacuerdo (ED)</span>
                            <span class="badge bg-danger">1: Totalmente en desacuerdo (TED)</span>
                        </div>

                        <form id="progLikertSurveyForm">
                            <!-- Apartado I: Datos Generales (Opcional / Estadístico) -->
                            <div class="p-3 rounded-3 border bg-light mb-4">
                                <h3 class="h6 fw-bold mb-3">Apartado I: Datos Generales (Opcional / Estadístico)</h3>
                                <div class="row g-3 small">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label fw-semibold mb-1">Carrera:</label>
                                        <input type="text" class="form-control form-control-sm" value="Ingeniería en Sistemas Computacionales">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label class="form-label fw-semibold mb-1">Semestre:</label>
                                        <input type="text" class="form-control form-control-sm" value="1">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label class="form-label fw-semibold mb-1">Grupo:</label>
                                        <input type="text" class="form-control form-control-sm" placeholder="A">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label class="form-label fw-semibold mb-1">Edad:</label>
                                        <input type="number" class="form-control form-control-sm" placeholder="18" min="15" max="99">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label class="form-label fw-semibold mb-1 d-block">Sexo:</label>
                                        <div class="d-flex gap-3 pt-1">
                                            <label class="form-check-label"><input type="radio" name="prog_sexo" value="M" class="form-check-input me-1">M</label>
                                            <label class="form-check-label"><input type="radio" name="prog_sexo" value="F" class="form-check-input me-1">F</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold mb-1">Módulo Evaluado:</label>
                                        <input type="text" class="form-control form-control-sm fw-bold text-prog" value="Fundamentos de Programación (Lógica, Estructuras y Simulación)" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Apartado II: Valoración de la Experiencia y el Impacto (Escala Likert) -->
                            <h3 class="h6 fw-bold mb-2">Apartado II: Valoración de la Experiencia y el Impacto (Escala Likert)</h3>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered prog-likert-table align-middle small mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="width: 45px;">N°</th>
                                            <th class="text-start">Criterio de Evaluación / Dimensión</th>
                                            <th style="width: 68px;">TA<br>(5)</th>
                                            <th style="width: 68px;">DA<br>(4)</th>
                                            <th style="width: 68px;">N<br>(3)</th>
                                            <th style="width: 68px;">ED<br>(2)</th>
                                            <th style="width: 68px;">TED<br>(1)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $criteriosProgramacion = [
                                            1  => ['Utilidad visual:', 'El simulador interactivo facilitó la visualización de los conceptos abstractos (trazas de algoritmos, variables, condicionales y bucles) sin necesidad de recurrir únicamente a la memorización de sintaxis.'],
                                            2  => ['Reducción de carga cognitiva:', 'El uso de las herramientas visuales disminuyó la frustración y el esfuerzo mental excesivo al momento de enfrentar problemas de lógica de programación.'],
                                            3  => ['Aprendizaje Basado en Problemas (ABP):', 'Los retos prácticos basados en situaciones reales le dieron un sentido lógico y aplicable a los temas estudiados.'],
                                            4  => ['Razonamiento vs. Memorización:', 'Las actividades interactivas me obligaron a razonar el procedimiento lógico y la toma de decisiones en lugar de solo copiar o memorizar código.'],
                                            5  => ['Puente cognitivo hacia las ciencias exactas:', 'La estructura lógica de las lecciones me ayudó a comprender mejor la conexión entre la programación y la resolución de problemas de cálculo y física.'],
                                            6  => ['Autonomía en el aprendizaje:', 'Los recursos didácticos y guías descargables me permitieron avanzar a mi propio ritmo y resolver ejercicios de forma más independiente.'],
                                            7  => ['Motivación académica:', 'La dinámica interactiva de la plataforma incrementó mi interés y motivación por superar los retos planteados en la materia.'],
                                            8  => ['Reducción del riesgo de reprobación:', 'Considero que el uso constante de este curso de reforzamiento mejora significativamente mis probabilidades de aprobar con éxito las evaluaciones de la asignatura.'],
                                            9  => ['Satisfacción general con la plataforma:', 'La interfaz, el diseño y la distribución de las 5 lecciones del módulo resultaron intuitivas y agradables de utilizar.'],
                                            10 => ['Pertinencia institucional:', 'Recomendaría la implementación oficial de esta herramienta interactiva como apoyo obligatorio para los estudiantes de nuevo ingreso.'],
                                        ];
                                        foreach ($criteriosProgramacion as $num => [$tituloCrit, $descCrit]):
                                        ?>
                                            <tr>
                                                <td class="text-center fw-bold"><?= $num ?></td>
                                                <td><strong><?= e($tituloCrit) ?></strong> <?= e($descCrit) ?></td>
                                                <?php for ($val = 5; $val >= 1; $val--): ?>
                                                    <td class="text-center">
                                                        <input type="radio" class="prog-likert-radio" name="prog_likert_<?= $num ?>" value="<?= $val ?>" aria-label="Pregunta <?= $num ?> opción <?= $val ?>">
                                                    </td>
                                                <?php endfor; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Apartado III: Retroalimentación Cualitativa (Comentarios Abiertos) -->
                            <div class="p-3 rounded-3 border bg-light mb-3">
                                <h3 class="h6 fw-bold mb-3">Apartado III: Retroalimentación Cualitativa (Comentarios Abiertos)</h3>
                                <div class="mb-2">
                                    <label for="progComment1" class="form-label small fw-semibold mb-1">1. ¿Qué fue lo que más te gustó de los simuladores visuales y los retos de este módulo para comprender la materia?</label>
                                    <textarea id="progComment1" class="form-control form-control-sm" rows="2"></textarea>
                                </div>
                                <div class="mb-2">
                                    <label for="progComment2" class="form-label small fw-semibold mb-1">2. ¿En qué aspectos consideras que la plataforma o las lecciones podrían mejorar para facilitar aún más tu aprendizaje?</label>
                                    <textarea id="progComment2" class="form-control form-control-sm" rows="2"></textarea>
                                </div>
                                <div>
                                    <label for="progComment3" class="form-label small fw-semibold mb-1">3. Comentarios, observaciones o sugerencias adicionales:</label>
                                    <textarea id="progComment3" class="form-control form-control-sm" rows="2"></textarea>
                                </div>
                                <p class="text-xs text-muted fst-italic mt-2 mb-0">
                                    ¡Agradecemos tu valiosa participación para el desarrollo y mejora continua de este proyecto de investigación!
                                </p>
                            </div>

                            <div id="progSurveyStatusAlert" class="d-none"></div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                                <button type="submit" class="btn btn-prog fw-bold px-4">
                                    Enviar Encuesta y Validar Progreso ✓
                                </button>

                                <button type="button" id="progBtnDownloadCertSurvey" class="btn btn-secondary fw-bold px-4 opacity-75" disabled>
                                    🔒 Descargar Certificado
                                </button>
                            </div>
                        </form>
                    </article>

                    <!-- =====================================================
                         MÓDULO DE CIERRE · ITEM 2: CERTIFICADO DE ACREDITACIÓN
                         ===================================================== -->
                    <article class="prog-content-pane" data-prog-pane="cierre-certificado">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="prog-type-badge prog-badge-closing">🎓 Módulo de Cierre · Acreditación de Programación</span>
                            <span class="text-muted small font-monospace">Folio: <?= e($folioCertificado) ?></span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Certificado de Acreditación: Fundamentos de Programación</h2>

                        <div id="progCertLockNotice" class="alert alert-warning d-flex align-items-center gap-2 py-2 px-3 small mb-3">
                            <span>🔒</span>
                            <div>
                                <strong>Requisito previo:</strong> Para desbloquear el botón <strong>"Descargar Certificado"</strong>, primero envía la <button type="button" class="btn btn-link btn-sm p-0 align-baseline fw-bold text-prog" data-prog-goto="cierre-encuesta">Encuesta de Satisfacción e Impacto</button>.
                            </div>
                        </div>

                        <div class="p-4 rounded-4 bg-white text-center shadow-sm mb-4" style="border: 6px double #1d4ed8;">
                            <div class="text-uppercase text-xs tracking-wider text-prog fw-bold mb-1">
                                Instituto Tecnológico de Ciudad Victoria · Lógica Visual ABP
                            </div>
                            <h3 class="h5 fw-bold text-dark mb-3">CERTIFICADO DE ACREDITACIÓN</h3>
                            <p class="small text-secondary mb-1">Se otorga el presente reconocimiento a:</p>
                            <div class="h3 fw-bold text-prog my-2"><?= e($nombreAlumno) ?></div>
                            <p class="small text-secondary max-w-2xl mx-auto mb-3">
                                Por haber acreditado satisfactoriamente las 5 lecciones interactivas, simuladores de lógica y retos de Aprendizaje Basado en Problemas del módulo:
                            </p>
                            <div class="d-inline-block px-4 py-2 rounded-3 fw-bold h5 mb-3" style="background:#eff6ff; border:2px solid #2563eb; color:#1e40af;">
                                <?= e($moduloCompletado) ?>
                            </div>
                            <div class="d-flex flex-wrap justify-content-around align-items-center small text-muted pt-3 border-top mt-2">
                                <div><strong>Fecha de finalización:</strong> <?= e($fechaActual) ?></div>
                                <div><strong>Folio de verificación:</strong> <span class="font-monospace"><?= e($folioCertificado) ?></span></div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-prog-goto="cierre-encuesta">← Volver a la Encuesta</button>
                            <button type="button" id="progBtnDownloadCertPane" class="btn btn-secondary fw-bold px-4 opacity-75" disabled>
                                🔒 Descargar Certificado
                            </button>
                        </div>
                    </article>

                </div>
            </div>

        </div>
    </div>

    <!-- =================================================================
         3. COMPONENTE MODAL (VENTANA EMERGENTE CON BACKDROP BLUR) PARA VIDEO
         ================================================================= -->
    <div class="modal fade" id="progYoutubeModal" tabindex="-1" aria-labelledby="progYoutubeModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
                <div class="modal-header prog-modal-header py-3 px-4">
                    <div>
                        <span class="badge bg-primary text-white fw-bold mb-1" id="progYoutubeModalChannel">Canal Oficial</span>
                        <h3 class="modal-title h6 fw-bold mb-0 text-white" id="progYoutubeModalTitle">Reproductor de Vídeo</h3>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0 bg-black">
                    <div class="ratio ratio-16x9">
                        <iframe id="progYoutubeIframe"
                                src=""
                                title="Reproductor de video de Fundamentos de Programación en YouTube"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen></iframe>
                    </div>
                </div>
                <div class="modal-footer bg-light d-flex flex-wrap justify-content-between align-items-center px-4 py-3">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="https://www.youtube.com/watch?v=chPhlsydl30" id="progYoutubeDirectLink" target="_blank" rel="noopener noreferrer" class="btn btn-danger btn-sm fw-bold px-3">
                            ▶ Abrir directamente en YouTube ↗
                        </a>
                        <button type="button" id="progYoutubeAltBtn" class="btn btn-outline-prog btn-sm fw-semibold d-none">
                            🎬 Ver Video Alternativo
                        </button>
                        <span id="progYoutubePlaylistWrap" class="d-none">
                            <a href="#" id="progYoutubePlaylistLink" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm fw-semibold">
                                Playlist en YouTube ↗
                            </a>
                        </span>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm fw-bold px-3" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('js/programacion_fundamentos.js') ?>"></script>
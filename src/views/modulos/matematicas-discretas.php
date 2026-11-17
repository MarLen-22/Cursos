<?php

/**
 * Vista Principal del Curso: Matemáticas Discretas (Lógica, Estructuras y Simulación)
 * Diseño Split-Screen E-Learning (Sidebar Izquierdo 30% / Panel Principal 70%)
 * Paleta Clean UI: Púrpura (#7e22ce) y Violeta (#8b5cf6)
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
$nombreAlumno     = $usuarioActual !== null ? $usuarioActual->nombre : 'Johan Gael Castillo Gonzalez';
$moduloCompletado = 'Matemáticas Discretas (Lógica, Estructuras y Simulación)';
$fechaActual      = ($inscripcion !== null && $inscripcion->finalizadoEn !== null)
    ? fecha($inscripcion->finalizadoEn, 'd/m/Y')
    : date('d/m/Y');
$folioCertificado = sprintf(
    'LV-DISC-%05d-%04d',
    $inscripcion !== null ? $inscripcion->id : 5,
    $usuarioActual !== null ? $usuarioActual->id : 1
);
?>

<link rel="stylesheet" href="<?= asset('css/matematicas_discretas.css') ?>">

<div class="disc-elearning-wrapper" id="discretasCursoApp">
    <!-- Metadatos dinámicos de sesión para el generador del Certificado PDF -->
    <div id="discCertificateData"
        class="d-none"
        data-nombre-alumno="<?= e($nombreAlumno) ?>"
        data-modulo-completado="<?= e($moduloCompletado) ?>"
        data-fecha-emision="<?= e($fechaActual) ?>"
        data-folio="<?= e($folioCertificado) ?>"></div>

    <!-- Encabezado Superior del Curso (Paleta Púrpura / Violeta) -->
    <section class="disc-hero-banner text-white py-4 position-relative">
        <div class="bg-grid position-absolute top-0 start-0 w-100 h-100 pointer-events-none"></div>
        <div class="container-fluid max-w-7xl mx-auto px-3 px-md-4 position-relative z-1">
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?= url('/modulos') ?>" class="text-slate-300 text-decoration-none">Módulos Interactivos</a></li>
                    <li class="breadcrumb-item active fw-semibold" style="color: #d8b4fe;" aria-current="page">Matemáticas Discretas: Lógica, Estructuras y Simulación</li>
                </ol>
            </nav>

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="module-icon-box fs-3" style="background: rgba(168,85,247,0.22); color: #d8b4fe;">⛖</div>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <h1 class="h3 fw-bold text-white mb-0">Matemáticas Discretas (Lógica, Estructuras y Simulación)</h1>
                            <span class="badge rounded-pill fw-bold" style="background:#a855f7; color:#1e1035;">Nivel Inicial · ABP</span>
                        </div>
                        <p class="small text-slate-200 mb-0 max-w-4xl">
                            En Matemáticas Discretas se construye el lenguaje formal de la computación. Las proposiciones, conjuntos, relaciones, grafos y compuertas booleanas (<em>"Si p y q son verdaderos, entonces p ∧ q = 1"</em>) cobran vida en estas 5 lecciones interactivas.
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <?php if (Auth::check() && $inscripcion === null): ?>
                        <form action="<?= url('/modulos/' . e($modulo->slug ?? 'matematicas-discretas') . '/inscripcion') ?>" method="POST" class="m-0">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-purple btn-sm fw-bold px-3 shadow-purple">
                                Inscribirme al curso ▷
                            </button>
                        </form>
                    <?php elseif (Auth::check() && $inscripcion !== null && !$inscripcion->finalizado()): ?>
                        <form action="<?= url('/modulos/' . e($modulo->slug ?? 'matematicas-discretas') . '/avance') ?>" method="POST" class="m-0">
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
        <div class="disc-split-layout">

            <!-- =============================================================
                 1. PANEL LATERAL IZQUIERDO (30%): RUTA DE APRENDIZAJE Y NAVEGACIÓN
                 ============================================================= -->
            <aside class="disc-sidebar-panel" aria-label="Ruta de Aprendizaje y Navegación de Matemáticas Discretas">
                <div class="disc-sidebar-card">
                    <div class="disc-sidebar-header">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-uppercase fw-bold text-xs tracking-wider text-purple">Ruta de Aprendizaje</span>
                            <a href="<?= url('/modulos') ?>" class="disc-sidebar-close-btn" title="Volver al catálogo de módulos" aria-label="Cerrar curso">✕</a>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h2 class="h6 fw-bold mb-0">Matemáticas Discretas</h2>
                            <span class="badge rounded-pill" style="background:#f3e8ff; color:#6b21a8;" id="discGlobalProgressText">1/22 elementos (5%)</span>
                        </div>
                        <div class="progress" style="height: 7px;">
                            <div class="progress-bar" id="discGlobalProgressBar" role="progressbar" style="width: 5%; background-color: #7e22ce;"></div>
                        </div>
                    </div>

                    <div class="accordion disc-lesson-accordion" id="discSidebarAccordion">
                        <!-- LECCIÓN 1 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="discHeadL1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#discCollapseL1" aria-expanded="true" aria-controls="discCollapseL1">
                                    <div>
                                        Lección 1: Lógica Proposicional y Conectivos
                                        <span class="disc-lesson-meta" id="discLessonCount-1">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="discCollapseL1" class="accordion-collapse collapse show" aria-labelledby="discHeadL1">
                                <ul class="disc-nav-list">
                                    <li>
                                        <button type="button" class="disc-nav-item is-active" data-disc-item="l1-lectura" data-disc-lesson="1" data-disc-type="lectura">
                                            <span class="disc-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Teoría: Proposiciones, Conectivos y De Morgan</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l1-video" data-disc-lesson="1" data-disc-type="video"
                                            data-video-title="Proposiciones Lógicas y Conectivos | Tablas de Verdad"
                                            data-video-channel="Canal: Matemáticas profe Alex"
                                            data-video-embed="https://www.youtube-nocookie.com/embed/pU6QhR54fcs?rel=0"
                                            data-video-url="https://www.youtube.com/watch?v=pU6QhR54fcs"
                                            data-video-alt-title="Leyes de De Morgan y Equivalencias Lógicas"
                                            data-video-alt-channel="Canal: MateFacil"
                                            data-video-alt-embed="https://www.youtube-nocookie.com/embed/g_kjocGDVKc?rel=0"
                                            data-video-alt-url="https://www.youtube.com/watch?v=g_kjocGDVKc"
                                            data-video-alt-short="Leyes de De Morgan">
                                            <span class="disc-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • Conectivos y Formalización (Pop-up)</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l1-simulador" data-disc-lesson="1" data-disc-type="simulador">
                                            <span class="disc-nav-icon">⚙</span>
                                            <span><strong>Simulador</strong> • Evaluador Interactivo de Tablas de Verdad</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l1-pdf" data-disc-lesson="1" data-disc-type="pdf">
                                            <span class="disc-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • Guia_1_Logica_Proposicional.pdf</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- LECCIÓN 2 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="discHeadL2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#discCollapseL2" aria-expanded="false" aria-controls="discCollapseL2">
                                    <div>
                                        Lección 2: Teoría de Conjuntos y Diagramas de Venn
                                        <span class="disc-lesson-meta" id="discLessonCount-2">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="discCollapseL2" class="accordion-collapse collapse" aria-labelledby="discHeadL2">
                                <ul class="disc-nav-list">
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l2-lectura" data-disc-lesson="2" data-disc-type="lectura">
                                            <span class="disc-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Teoría: Unión, Intersección, Diferencia y Potencia</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l2-video" data-disc-lesson="2" data-disc-type="video"
                                            data-video-title="Operaciones con Conjuntos y Diagramas de Venn"
                                            data-video-channel="Canal: Matemáticas profe Alex"
                                            data-video-embed="https://www.youtube-nocookie.com/embed/7R780U8oNkc?rel=0"
                                            data-video-url="https://www.youtube.com/watch?v=7R780U8oNkc"
                                            data-video-alt-title="Conjunto Potencia y Cardinalidad | Inclusión-Exclusión"
                                            data-video-alt-channel="Canal: MateFacil"
                                            data-video-alt-embed="https://www.youtube-nocookie.com/embed/KmcRMlv9_T4?rel=0"
                                            data-video-alt-url="https://www.youtube.com/watch?v=KmcRMlv9_T4"
                                            data-video-alt-short="Conjunto Potencia">
                                            <span class="disc-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • Operaciones con Conjuntos (Pop-up)</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l2-reto" data-disc-lesson="2" data-disc-type="reto">
                                            <span class="disc-nav-icon">⚡</span>
                                            <span><strong>Reto ABP</strong> • Analizador Visual de Conjuntos y Venn</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l2-pdf" data-disc-lesson="2" data-disc-type="pdf">
                                            <span class="disc-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • Guia_2_Algebra_Conjuntos.pdf</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- LECCIÓN 3 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="discHeadL3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#discCollapseL3" aria-expanded="false" aria-controls="discCollapseL3">
                                    <div>
                                        Lección 3: Relaciones Binarias y Propiedades
                                        <span class="disc-lesson-meta" id="discLessonCount-3">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="discCollapseL3" class="accordion-collapse collapse" aria-labelledby="discHeadL3">
                                <ul class="disc-nav-list">
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l3-lectura" data-disc-lesson="3" data-disc-type="lectura">
                                            <span class="disc-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Teoría: Reflexiva, Simétrica, Transitiva y Posets</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l3-video" data-disc-lesson="3" data-disc-type="video"
                                            data-video-title="Relaciones de Equivalencia y Propiedades Binarias"
                                            data-video-channel="Canal: Ciencias Básicas ITCV"
                                            data-video-embed="https://www.youtube-nocookie.com/embed/4C5-eF_9w7s?rel=0"
                                            data-video-url="https://www.youtube.com/watch?v=4C5-eF_9w7s"
                                            data-video-alt-title="Relaciones de Orden Parcial y Diagramas de Hasse"
                                            data-video-alt-channel="Canal: MateFacil"
                                            data-video-alt-embed="https://www.youtube-nocookie.com/embed/R_18fA2R0fE?rel=0"
                                            data-video-alt-url="https://www.youtube.com/watch?v=R_18fA2R0fE"
                                            data-video-alt-short="Orden Parcial">
                                            <span class="disc-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • Relaciones de Equivalencia (Pop-up)</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l3-simulador" data-disc-lesson="3" data-disc-type="simulador">
                                            <span class="disc-nav-icon">⚙</span>
                                            <span><strong>Simulador</strong> • Matriz de Relación $M_R$ ($3 \times 3$)</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l3-pdf" data-disc-lesson="3" data-disc-type="pdf">
                                            <span class="disc-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • Guia_3_Matrices_Relacion.pdf</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- LECCIÓN 4 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="discHeadL4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#discCollapseL4" aria-expanded="false" aria-controls="discCollapseL4">
                                    <div>
                                        Lección 4: Teoría de Grafos y Árboles
                                        <span class="disc-lesson-meta" id="discLessonCount-4">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="discCollapseL4" class="accordion-collapse collapse" aria-labelledby="discHeadL4">
                                <ul class="disc-nav-list">
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l4-lectura" data-disc-lesson="4" data-disc-type="lectura">
                                            <span class="disc-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Teoría: Vértices, Aristas, Grados y Árboles</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l4-video" data-disc-lesson="4" data-disc-type="video"
                                            data-video-title="Introducción a la Teoría de Grafos y Conectividad"
                                            data-video-channel="Canal: Matemáticas Discretas"
                                            data-video-embed="https://www.youtube-nocookie.com/embed/qZ8i_51f0qE?rel=0"
                                            data-video-url="https://www.youtube.com/watch?v=qZ8i_51f0qE"
                                            data-video-alt-title="Árboles Generadores y Teorema del Apretón de Manos"
                                            data-video-alt-channel="Canal: El Traductor de Ingeniería"
                                            data-video-alt-embed="https://www.youtube-nocookie.com/embed/vnNFiNVy9jg?rel=0"
                                            data-video-alt-url="https://www.youtube.com/watch?v=vnNFiNVy9jg"
                                            data-video-alt-short="Teoría de Grafos">
                                            <span class="disc-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • Vértices, Aristas y Conectividad (Pop-up)</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l4-reto" data-disc-lesson="4" data-disc-type="reto">
                                            <span class="disc-nav-icon">⚡</span>
                                            <span><strong>Reto ABP</strong> • Red de Nodos: Verificador de Árboles</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l4-pdf" data-disc-lesson="4" data-disc-type="pdf">
                                            <span class="disc-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • Guia_4_Grafos_y_Arboles.pdf</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- LECCIÓN 5 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="discHeadL5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#discCollapseL5" aria-expanded="false" aria-controls="discCollapseL5">
                                    <div>
                                        Lección 5: Álgebra Booleana y Circuitos
                                        <span class="disc-lesson-meta" id="discLessonCount-5">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="discCollapseL5" class="accordion-collapse collapse" aria-labelledby="discHeadL5">
                                <ul class="disc-nav-list">
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l5-lectura" data-disc-lesson="5" data-disc-type="lectura">
                                            <span class="disc-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Teoría: Compuertas Lógicas y Mapas de Karnaugh</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l5-video" data-disc-lesson="5" data-disc-type="video"
                                            data-video-title="Compuertas Lógicas AND, OR, NOT, XOR y Álgebra Booleana"
                                            data-video-channel="Canal: Electrónica Digital"
                                            data-video-embed="https://www.youtube-nocookie.com/embed/8eWl_j8yDkQ?rel=0"
                                            data-video-url="https://www.youtube.com/watch?v=8eWl_j8yDkQ"
                                            data-video-alt-title="Simplificación con Mapas de Karnaugh paso a paso"
                                            data-video-alt-channel="Canal: Pasos por Ingeniería"
                                            data-video-alt-embed="https://www.youtube-nocookie.com/embed/1r4pA0s_q2g?rel=0"
                                            data-video-alt-url="https://www.youtube.com/watch?v=1r4pA0s_q2g"
                                            data-video-alt-short="Mapas de Karnaugh">
                                            <span class="disc-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • Compuertas Lógicas y Boole (Pop-up)</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l5-simulador" data-disc-lesson="5" data-disc-type="simulador">
                                            <span class="disc-nav-icon">⚙</span>
                                            <span><strong>Simulador</strong> • Banco de Compuertas Digitales</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="l5-pdf" data-disc-lesson="5" data-disc-type="pdf">
                                            <span class="disc-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • Guia_5_Mapas_Karnaugh.pdf</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- MÓDULO DE CIERRE Y CERTIFICACIÓN -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="discHeadL6">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#discCollapseL6" aria-expanded="false" aria-controls="discCollapseL6">
                                    <div>
                                        Módulo de Cierre y Certificación
                                        <span class="disc-lesson-meta" id="discLessonCount-6">0/2 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="discCollapseL6" class="accordion-collapse collapse" aria-labelledby="discHeadL6">
                                <ul class="disc-nav-list">
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="cierre-encuesta" data-disc-lesson="6" data-disc-type="cierre">
                                            <span class="disc-nav-icon">📊</span>
                                            <span><strong>Encuesta</strong> • Satisfacción e Impacto del Módulo (Escala Likert)</span>
                                            <span class="disc-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="disc-nav-item" data-disc-item="cierre-certificado" data-disc-lesson="6" data-disc-type="cierre">
                                            <span class="disc-nav-icon">🎓</span>
                                            <span><strong>Certificado</strong> • Descargar acreditación de Discretas</span>
                                            <span class="disc-nav-check">✓</span>
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
            <div class="disc-main-panel" id="discMainWorkspace">
                <div class="disc-workspace-card">

                    <!-- =====================================================
                         LECCIÓN 1 · ITEM 1: LECTURA (Lógica Proposicional)
                         ===================================================== -->
                    <article class="disc-content-pane is-active" data-disc-pane="l1-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <div>
                                <span class="disc-type-badge disc-badge-reading mb-1">📖 Lección 1 · Lectura Activa</span>
                                <h2 class="h3 fw-bold mb-0">Lógica Proposicional y Conectivos Lógicos</h2>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-purple fw-semibold" data-disc-note-toggle="l1">
                                📝 Guardar nota
                            </button>
                        </div>

                        <div class="disc-note-drawer d-none" id="discNoteDrawer-l1">
                            <label for="discNoteInput-l1" class="form-label small fw-bold text-dark mb-1">📝 Tus apuntes de la Lección 1 (Lógica Proposicional):</label>
                            <textarea id="discNoteInput-l1" class="form-control form-control-sm mb-2" rows="2" placeholder="Anota tus observaciones sobre tablas de verdad y Leyes de De Morgan..."></textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-success fw-semibold" id="discNoteStatus-l1"></span>
                                <button type="button" class="btn btn-xs btn-purple fw-bold px-3" id="discNoteSave-l1">Guardar apunte</button>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <strong>Tema:</strong> Proposiciones atómicas y compuestas, operadores lógicos y equivalencias.
                        </p>

                        <h3 class="h5 fw-bold text-purple mb-2">¿Qué es? (Introducción Teórica)</h3>
                        <p class="text-secondary leading-relaxed mb-3">
                            Una <strong>proposición lógica</strong> es todo enunciado declarativo al cual se le puede asignar sin ambigüedad uno y solo uno de dos valores de verdad: <strong>Verdadero ($V$ o $1$)</strong> o <strong>Falso ($F$ o $0$)</strong>. Las preguntas, órdenes o exclamaciones no son proposiciones.
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-4 border bg-light h-100">
                                    <h4 class="h6 fw-bold text-secondary mb-2">🔗 Conjunción ($p \land q$) y Disyunción ($p \lor q$)</h4>
                                    <p class="small text-secondary mb-0">
                                        La conjunción ($p \land q$) equivale al operador <code>&amp;&amp;</code> en programación: solo es verdadera si <strong>ambas</strong> son verdaderas. La disyunción ($p \lor q$) equivale a <code>||</code>: solo es falsa cuando ambas son falsas.
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-4 border h-100" style="background: rgba(243,232,255,0.45); border-color:#d8b4fe !important;">
                                    <h4 class="h6 fw-bold text-purple mb-2">➡ Condicional ($p \rightarrow q$) y Bicondicional ($p \leftrightarrow q$)</h4>
                                    <p class="small text-secondary mb-0">
                                        La condicional (<em>"Si $p$, entonces $q$"</em>) únicamente es <strong>falsa cuando $p = V$ y $q = F$</strong>. El bicondicional ($p \leftrightarrow q$) es verdadero cuando $p$ y $q$ tienen exactamente el mismo valor lógico.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <h3 class="h5 fw-bold text-purple mb-3">Equivalencias y Leyes Principales</h3>

                        <div class="disc-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Primera Ley de De Morgan:</div>
                                <div class="small text-muted">Negación de una conjunción</div>
                            </div>
                            <div class="disc-formula-math">$$\neg(p \land q) \equiv \neg p \lor \neg q$$</div>
                        </div>

                        <div class="disc-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Segunda Ley de De Morgan:</div>
                                <div class="small text-muted">Negación de una disyunción</div>
                            </div>
                            <div class="disc-formula-math">$$\neg(p \lor q) \equiv \neg p \land \neg q$$</div>
                        </div>

                        <div class="disc-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                            <div>
                                <div class="fw-bold text-dark">Equivalencia de la Implicación:</div>
                                <div class="small text-muted">Reescritura de condicional usando disyunción</div>
                            </div>
                            <div class="disc-formula-math">$$(p \rightarrow q) \equiv (\neg p \lor q)$$</div>
                        </div>

                        <div class="disc-tips-accordion mb-4">
                            <button class="disc-tips-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#discTipsL1" aria-expanded="true">
                                <span>✦ Tips para el Simulador de Tablas de Verdad</span>
                                <span>[ ˅ ]</span>
                            </button>
                            <div id="discTipsL1" class="collapse show">
                                <div class="disc-tips-body">
                                    Si una fórmula compuesta da <strong>Verdadero ($V$)</strong> en todas las filas de su tabla se llama <strong>Tautología</strong>; si da <strong>Falso ($F$)</strong> en todas se llama <strong>Contradicción</strong>; y si combina valores se denomina <strong>Contingencia</strong>.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                            <div class="d-flex align-items-center gap-2 disc-feedback-group">
                                <span class="small text-muted">¿Te resultó clara esta lectura?</span>
                                <button type="button" class="disc-feedback-btn" data-disc-feedback="up" aria-label="Me gustó">👍</button>
                                <button type="button" class="disc-feedback-btn" data-disc-feedback="down" aria-label="No me gustó">👎</button>
                                <span class="small text-purple fw-semibold disc-feedback-msg"></span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-purple btn-sm fw-bold" data-disc-goto="l1-video">
                                    ▶ Ver Vídeo Pop-up
                                </button>
                                <button type="button" class="btn btn-purple fw-bold px-4 shadow-purple" data-disc-goto="l1-simulador">
                                    Ir al Simulador: Tablas de Verdad ➔
                                </button>
                            </div>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 1 · ITEM 2: VÍDEO (Pop-up YouTube)
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l1-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="disc-type-badge disc-badge-reading">▶ Lección 1 · Recursos Audiovisuales (Pop-up)</span>
                            <span class="text-muted small">Video: "Conectivos Lógicos y Formalización"</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Recursos Audiovisuales: Lógica Proposicional</h2>
                        <p class="text-secondary small mb-4">
                            Para mantener enfocado tu espacio de trabajo, los vídeos se reproducen en una <strong>ventana emergente (Modal con fondo desenfocado)</strong>:
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge text-white mb-2" style="background:#7e22ce;">Video Recomendado Principal</span>
                                        <h3 class="h6 fw-bold mb-1">Proposiciones Lógicas y Conectivos | Tablas de Verdad</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>Matemáticas profe Alex</strong></p>
                                        <p class="small text-secondary mb-3">Explicación paso a paso de conjunción, disyunción, condicional, bicondicional y negación.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-purple btn-sm fw-bold px-3" data-open-disc-video="l1-video" data-video-mode="main">
                                            ▶ Reproducir en Modal
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=pU6QhR54fcs" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-secondary mb-2">Video Alternativo</span>
                                        <h3 class="h6 fw-bold mb-1">Leyes de De Morgan y Equivalencias Lógicas</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>MateFacil</strong></p>
                                        <p class="small text-secondary mb-3">Demostración visual de equivalencias lógicas aplicadas a sentencias condicionales.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-purple btn-sm fw-bold px-3" data-open-disc-video="l1-video" data-video-mode="alt">
                                            ▶ Reproducir Alternativo
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=g_kjocGDVKc" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-disc-goto="l1-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-purple fw-bold px-4" data-disc-goto="l1-simulador">Siguiente: Simulador Tablas de Verdad ⚙</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 1 · ITEM 3: SIMULADOR (Tablas de Verdad)
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l1-simulador">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="disc-type-badge disc-badge-sim">⚙ Lección 1 · Simulador Interactivo</span>
                            <span class="text-muted small">Evaluador Booleano en Tiempo Real</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Simulador: Evaluador Interactivo de Tablas de Verdad</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Ejercicio:</strong> Selecciona los valores de verdad de $p$ y $q$ y elige el operador lógico para visualizar la fila activa en la tabla completa y comprobar si la expresión es tautología, contradicción o contingencia.
                        </p>

                        <div class="disc-sim-box mb-4">
                            <div class="disc-sim-topbar">
                                <span class="fw-bold small" style="color:#d8b4fe;">⚙ Motor Proposicional · Cálculo de Conectivos</span>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-info text-dark font-monospace" id="discL1ExprBadge">p → q</span>
                                    <span class="badge bg-success text-white font-monospace" id="discL1ResBadge">Resultado: V (1)</span>
                                </div>
                            </div>
                            <div class="disc-sim-body">
                                <div class="row g-4 align-items-center mb-3">
                                    <div class="col-12 col-md-6">
                                        <div class="table-responsive rounded-3 overflow-hidden border border-secondary">
                                            <table class="table table-dark table-sm text-center font-monospace mb-0 small" id="discL1TruthTable">
                                                <thead>
                                                    <tr>
                                                        <th>p</th>
                                                        <th>q</th>
                                                        <th id="discL1ColHeader">p → q</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="discL1TableBody"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-2">
                                            <label for="discL1OpSelect" class="form-label small text-slate-300 mb-1">Operador / Fórmula Lógica:</label>
                                            <select id="discL1OpSelect" class="form-select form-select-sm bg-dark text-white border-secondary">
                                                <option value="and">Conjunción (p ∧ q)</option>
                                                <option value="or">Disyunción Inclusiva (p ∨ q)</option>
                                                <option value="xor">Disyunción Exclusiva (p ⊕ q)</option>
                                                <option value="imp" selected>Condicional (p → q)</option>
                                                <option value="bic">Bicondicional (p ↔ q)</option>
                                                <option value="taut">Ley del Tercero Excluido (p ∨ ¬p)</option>
                                            </select>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label for="discL1PSelect" class="form-label small text-slate-300 mb-1">Proposición $p$:</label>
                                                <select id="discL1PSelect" class="form-select form-select-sm bg-dark text-white border-info font-monospace">
                                                    <option value="1" selected>V (Verdadero / 1)</option>
                                                    <option value="0">F (Falso / 0)</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label for="discL1QSelect" class="form-label small text-slate-300 mb-1">Proposición $q$:</label>
                                                <select id="discL1QSelect" class="form-select form-select-sm bg-dark text-white border-info font-monospace">
                                                    <option value="1">V (Verdadero / 1)</option>
                                                    <option value="0" selected>F (Falso / 0)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-purple fw-bold w-100" id="discL1CycleBtn">
                                            Recorrer siguiente combinación (V/F) ▷
                                        </button>
                                    </div>
                                </div>
                                <div class="disc-sim-console" id="discL1Console"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-disc-goto="l1-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-purple fw-bold px-4" data-disc-goto="l1-pdf">Siguiente: Guía Visual 01 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 1 · ITEM 4: GUÍA PDF
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l1-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="disc-type-badge disc-badge-pdf">📄 Lección 1 · Guía Visual Incorporada</span>
                            <div class="d-flex align-items-center gap-2">
                                <code class="small">Guia_1_Logica_Proposicional.pdf</code>
                                <button type="button" class="btn btn-purple btn-sm fw-bold" data-print-guide="discGuideContent-1" data-guide-title="Guía 01 - Lógica Proposicional">
                                    🖨️ Imprimir / Guardar PDF
                                </button>
                            </div>
                        </div>

                        <div class="disc-guide-container mb-4" id="discGuideContent-1" style="border-top: 8px solid #7e22ce;">
                            <header class="border-bottom pb-3 mb-4">
                                <p class="text-xs fw-bold text-purple text-uppercase tracking-wider mb-1">Módulo: Matemáticas Discretas | Lección 1</p>
                                <h2 class="h3 fw-extrabold text-dark mb-1">GUÍA 01: Lógica Proposicional y Tablas de Verdad</h2>
                                <p class="text-secondary small mb-0">Traduciendo enunciados del lenguaje natural a cálculo proposicional.</p>
                            </header>

                            <section class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="disc-guide-step-num me-3" style="background:#f3e8ff; color:#7e22ce;">1</div>
                                    <h3 class="h5 fw-bold mb-0">Conectivos Fundamentales</h3>
                                </div>
                                <div class="table-responsive rounded-3 overflow-hidden shadow-sm">
                                    <table class="table table-dark table-hover align-middle small mb-0">
                                        <thead>
                                            <tr class="text-uppercase text-secondary">
                                                <th class="p-3">Operador</th>
                                                <th class="p-3">Símbolo</th>
                                                <th class="p-3">Regla de Evaluación Lógica</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="p-3 fw-bold text-info">Conjunción</td>
                                                <td class="p-3 font-monospace">$p \land q$</td>
                                                <td class="p-3">Verdadera únicamente cuando $p$ y $q$ son ambas verdaderas.</td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 fw-bold text-warning">Disyunción</td>
                                                <td class="p-3 font-monospace">$p \lor q$</td>
                                                <td class="p-3">Falsa únicamente cuando ambas proposiciones son falsas.</td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 fw-bold text-danger">Condicional</td>
                                                <td class="p-3 font-monospace">$p \rightarrow q$</td>
                                                <td class="p-3">Falsa únicamente si el antecedente $p=V$ y el consecuente $q=F$.</td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 fw-bold text-success">Bicondicional</td>
                                                <td class="p-3 font-monospace">$p \leftrightarrow q$</td>
                                                <td class="p-3">Verdadera cuando $p$ y $q$ comparten exactamente el mismo valor de verdad.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </section>

                            <section>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="disc-guide-step-num me-3" style="background:#f3e8ff; color:#7e22ce;">2</div>
                                    <h3 class="h5 fw-bold mb-0">Leyes de De Morgan</h3>
                                </div>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="math-box" style="background:#2e1065;">$$\neg(p \land q) \equiv \neg p \lor \neg q$$</div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="math-box" style="background:#2e1065;">$$\neg(p \lor q) \equiv \neg p \land \neg q$$</div>
                                    </div>
                                </div>
                            </section>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-disc-goto="l1-simulador">← Anterior: Simulador</button>
                            <button type="button" class="btn btn-purple fw-bold px-4" data-disc-goto="l2-lectura">Ir a Lección 2: Teoría de Conjuntos →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 2 · ITEM 1: LECTURA (Teoría de Conjuntos)
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l2-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <div>
                                <span class="disc-type-badge disc-badge-reading mb-1">📖 Lección 2 · Lectura Activa</span>
                                <h2 class="h3 fw-bold mb-0">Teoría de Conjuntos y Diagramas de Venn</h2>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-purple fw-semibold" data-disc-note-toggle="l2">
                                📝 Guardar nota
                            </button>
                        </div>

                        <div class="disc-note-drawer d-none" id="discNoteDrawer-l2">
                            <label for="discNoteInput-l2" class="form-label small fw-bold text-dark mb-1">📝 Tus apuntes de la Lección 2 (Conjuntos):</label>
                            <textarea id="discNoteInput-l2" class="form-control form-control-sm mb-2" rows="2" placeholder="Anota tus fórmulas de cardinalidad y conjunto potencia..."></textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-success fw-semibold" id="discNoteStatus-l2"></span>
                                <button type="button" class="btn btn-xs btn-purple fw-bold px-3" id="discNoteSave-l2">Guardar apunte</button>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <strong>Tema:</strong> Pertenencia, subconjuntos, conjunto potencia $P(A)$ y álgebra de conjuntos.
                        </p>

                        <h3 class="h5 fw-bold text-purple mb-2">¿Qué es? (Introducción Teórica)</h3>
                        <p class="text-secondary leading-relaxed mb-3">
                            Un <strong>conjunto</strong> es una colección bien definida de objetos distintos llamados <em>elementos</em> ($x \in A$). En ciencias de la computación, las consultas SQL (<code>INNER JOIN</code>, <code>UNION</code>, <code>EXCEPT</code>) son implementaciones directas de operaciones entre conjuntos.
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-4 border bg-light h-100">
                                    <span class="badge bg-primary mb-2 code-font">A ∪ B</span>
                                    <h4 class="h6 fw-bold mb-1">Unión de Conjuntos</h4>
                                    <p class="small text-secondary mb-0">Elementos que pertenecen a $A$, a $B$, o a ambos simultáneamente.</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-4 border bg-light h-100">
                                    <span class="badge bg-danger mb-2 code-font">A ∩ B</span>
                                    <h4 class="h6 fw-bold mb-1">Intersección</h4>
                                    <p class="small text-secondary mb-0">Elementos compartidos en común. Si $A \cap B = \emptyset$, son conjuntos <strong>disjuntos</strong>.</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-4 border h-100" style="background: rgba(243,232,255,0.45); border-color:#d8b4fe !important;">
                                    <span class="badge text-white mb-2 code-font" style="background:#7e22ce;">A - B</span>
                                    <h4 class="h6 fw-bold mb-1">Diferencia</h4>
                                    <p class="small text-secondary mb-0">Elementos que pertenecen exclusivamente a $A$ y no están en $B$.</p>
                                </div>
                            </div>
                        </div>

                        <h3 class="h5 fw-bold text-purple mb-3">Fórmulas Principales</h3>

                        <div class="disc-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Cardinalidad del Conjunto Potencia $P(A)$:</div>
                                <div class="small text-muted">Número total de subconjuntos de un conjunto con $n$ elementos</div>
                            </div>
                            <div class="disc-formula-math">$$|P(A)| = 2^n$$</div>
                        </div>

                        <div class="disc-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                            <div>
                                <div class="fw-bold text-dark">Principio de Inclusión-Exclusión:</div>
                                <div class="small text-muted">Evita contar dos veces los elementos de la intersección</div>
                            </div>
                            <div class="disc-formula-math">$$|A \cup B| = |A| + |B| - |A \cap B|$$</div>
                        </div>

                        <div class="disc-tips-accordion mb-4">
                            <button class="disc-tips-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#discTipsL2" aria-expanded="true">
                                <span>✦ Tips para el Reto ABP de Conjuntos</span>
                                <span>[ ˅ ]</span>
                            </button>
                            <div id="discTipsL2" class="collapse show">
                                <div class="disc-tips-body">
                                    Recuerda que el conjunto vacío $\emptyset$ tiene cardinalidad $|\emptyset| = 0$, pero su conjunto potencia $P(\emptyset) = \{\emptyset\}$ tiene $2^0 = 1$ elemento.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                            <div class="d-flex align-items-center gap-2 disc-feedback-group">
                                <span class="small text-muted">¿Útil?</span>
                                <button type="button" class="disc-feedback-btn" data-disc-feedback="up">👍</button>
                                <button type="button" class="disc-feedback-btn" data-disc-feedback="down">👎</button>
                                <span class="small text-purple fw-semibold disc-feedback-msg"></span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-purple btn-sm fw-bold" data-disc-goto="l2-video">
                                    ▶ Vídeo Conjuntos
                                </button>
                                <button type="button" class="btn btn-purple fw-bold px-4 shadow-purple" data-disc-goto="l2-reto">
                                    Ir al Reto ABP: Diagrama de Venn ➔
                                </button>
                            </div>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 2 · ITEM 2: VÍDEO
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l2-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="disc-type-badge disc-badge-reading">▶ Lección 2 · Recursos Audiovisuales (Pop-up)</span>
                            <span class="text-muted small">Video: "Operaciones con Conjuntos"</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Teoría de Conjuntos y Diagramas de Venn</h2>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge text-white mb-2" style="background:#7e22ce;">Video Recomendado</span>
                                        <h3 class="h6 fw-bold mb-1">Operaciones con Conjuntos y Diagramas de Venn</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>Matemáticas profe Alex</strong></p>
                                        <p class="small text-secondary mb-3">Notación de pertenencia, unión, intersección, diferencia y complemento visual.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-purple btn-sm fw-bold px-3" data-open-disc-video="l2-video" data-video-mode="main">
                                            ▶ Reproducir en Modal
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=7R780U8oNkc" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-secondary mb-2">Video Alternativo</span>
                                        <h3 class="h6 fw-bold mb-1">Conjunto Potencia y Cardinalidad</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>MateFacil</strong></p>
                                        <p class="small text-secondary mb-3">Cálculo de subconjuntos $2^n$ y principio de inclusión-exclusión.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-purple btn-sm fw-bold px-3" data-open-disc-video="l2-video" data-video-mode="alt">
                                            ▶ Reproducir Alternativo
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=KmcRMlv9_T4" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-disc-goto="l2-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-purple fw-bold px-4" data-disc-goto="l2-reto">Siguiente: Reto ABP Diagrama de Venn ⚡</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 2 · ITEM 3: RETO ABP (Diagrama de Venn)
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l2-reto">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="disc-type-badge disc-badge-abp">⚡ Lección 2 · Reto ABP</span>
                            <span class="text-muted small">Simulador de Álgebra de Conjuntos</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Reto ABP: Analizador Visual de Conjuntos y Diagrama de Venn</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Misión:</strong> Modifica los elementos enteros de los conjuntos $A$ y $B$ y selecciona la operación para resaltar las regiones en el Diagrama de Venn y verificar $|A \cup B| = |A| + |B| - |A \cap B|$.
                        </p>

                        <div class="disc-sim-box mb-4">
                            <div class="disc-sim-topbar">
                                <span class="fw-bold small" style="color:#d8b4fe;">⚡ Motor de Conjuntos · Diagrama de Venn Interactivo</span>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-info text-dark font-monospace" id="discL2CardBadge">|A ∪ B| = 6</span>
                                    <span class="badge bg-warning text-dark font-monospace" id="discL2PotBadge">|P(A)| = 16</span>
                                </div>
                            </div>
                            <div class="disc-sim-body">
                                <div class="row g-4 align-items-center mb-3">
                                    <div class="col-12 col-md-6">
                                        <div class="disc-sim-canvas-wrap">
                                            <svg viewBox="0 0 320 200" class="w-100" aria-label="Diagrama de Venn Interactivo">
                                                <rect x="10" y="10" width="300" height="180" rx="12" fill="#0f0b1e" stroke="#4c1d95" stroke-width="1.5" />
                                                <text x="22" y="28" font-size="11" fill="#a855f7" font-weight="bold">Universo U</text>
                                                <circle id="discL2CircleA" cx="125" cy="105" r="62" fill="rgba(168,85,247,0.35)" stroke="#c084fc" stroke-width="2.5" />
                                                <circle id="discL2CircleB" cx="195" cy="105" r="62" fill="rgba(56,189,248,0.30)" stroke="#38bdf8" stroke-width="2.5" />
                                                <text x="90" y="55" font-size="12" fill="#e9d5ff" font-weight="bold">A</text>
                                                <text x="225" y="55" font-size="12" fill="#bae6fd" font-weight="bold">B</text>
                                                <text id="discL2VennOnlyA" x="95" y="108" font-size="11" fill="#ffffff" text-anchor="middle" font-weight="bold">1, 2</text>
                                                <text id="discL2VennInter" x="160" y="108" font-size="11" fill="#fde047" text-anchor="middle" font-weight="bold">3, 4</text>
                                                <text id="discL2VennOnlyB" x="225" y="108" font-size="11" fill="#ffffff" text-anchor="middle" font-weight="bold">5, 6</text>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-2">
                                            <label for="discL2SetAInput" class="form-label small text-slate-300 mb-1">Elementos de A (separados por coma):</label>
                                            <input type="text" id="discL2SetAInput" class="form-control form-control-sm bg-dark text-white border-secondary font-monospace" value="1, 2, 3, 4">
                                        </div>
                                        <div class="mb-2">
                                            <label for="discL2SetBInput" class="form-label small text-slate-300 mb-1">Elementos de B (separados por coma):</label>
                                            <input type="text" id="discL2SetBInput" class="form-control form-control-sm bg-dark text-white border-secondary font-monospace" value="3, 4, 5, 6">
                                        </div>
                                        <div class="mb-3">
                                            <label for="discL2OpSelect" class="form-label small text-slate-300 mb-1">Operación a Evaluar:</label>
                                            <select id="discL2OpSelect" class="form-select form-select-sm bg-dark text-white border-secondary">
                                                <option value="union" selected>Unión (A ∪ B)</option>
                                                <option value="inter">Intersección (A ∩ B)</option>
                                                <option value="diffAB">Diferencia (A - B)</option>
                                                <option value="diffBA">Diferencia (B - A)</option>
                                                <option value="sym">Diferencia Simétrica (A △ B)</option>
                                            </select>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-purple fw-bold w-100" id="discL2EvalBtn">
                                            Calcular Operación y Cardinalidad ▷
                                        </button>
                                    </div>
                                </div>
                                <div class="disc-sim-console" id="discL2Console"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-disc-goto="l2-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-purple fw-bold px-4" data-disc-goto="l2-pdf">Siguiente: Guía Visual 02 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 2 · ITEM 4: GUÍA PDF
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l2-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="disc-type-badge disc-badge-pdf">📄 Lección 2 · Guía Visual Incorporada</span>
                            <div class="d-flex align-items-center gap-2">
                                <code class="small">Guia_2_Algebra_Conjuntos.pdf</code>
                                <button type="button" class="btn btn-purple btn-sm fw-bold" data-print-guide="discGuideContent-2" data-guide-title="Guía 02 - Álgebra de Conjuntos">
                                    🖨️ Imprimir / Guardar PDF
                                </button>
                            </div>
                        </div>

                        <div class="disc-guide-container mb-4" id="discGuideContent-2" style="border-top: 8px solid #8b5cf6;">
                            <header class="border-bottom pb-3 mb-4">
                                <p class="text-xs fw-bold text-purple text-uppercase tracking-wider mb-1">Módulo: Matemáticas Discretas | Lección 2</p>
                                <h2 class="h3 fw-extrabold text-dark mb-1">GUÍA 02: Álgebra de Conjuntos y Cardinalidad</h2>
                                <p class="text-secondary small mb-0">Operaciones fundamentales y conteo mediante inclusión-exclusión.</p>
                            </header>
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <div class="p-3 rounded-3 bg-light border h-100">
                                        <h4 class="h6 fw-bold text-purple">Subconjuntos y Conjunto Potencia</h4>
                                        <p class="small text-secondary mb-2">$A \subseteq B$ si todo elemento de $A$ pertenece a $B$. El conjunto potencia $P(A)$ agrupa todos los subconjuntos posibles:</p>
                                        <div class="math-box" style="background:#3b0764;">$$|P(A)| = 2^{|A|}$$</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="p-3 rounded-3 bg-light border h-100">
                                        <h4 class="h6 fw-bold text-purple">Inclusión-Exclusión (2 Conjuntos)</h4>
                                        <p class="small text-secondary mb-2">Para calcular el tamaño total de elementos sin duplicar la intersección:</p>
                                        <div class="math-box" style="background:#3b0764;">$$|A \cup B| = |A| + |B| - |A \cap B|$$</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-disc-goto="l2-reto">← Anterior: Reto ABP</button>
                            <button type="button" class="btn btn-purple fw-bold px-4" data-disc-goto="l3-lectura">Ir a Lección 3: Relaciones Binarias →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 3 · ITEM 1: LECTURA (Relaciones Binarias)
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l3-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <div>
                                <span class="disc-type-badge disc-badge-reading mb-1">📖 Lección 3 · Lectura Activa</span>
                                <h2 class="h3 fw-bold mb-0">Relaciones Binarias y Propiedades</h2>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-purple fw-semibold" data-disc-note-toggle="l3">
                                📝 Guardar nota
                            </button>
                        </div>

                        <div class="disc-note-drawer d-none" id="discNoteDrawer-l3">
                            <label for="discNoteInput-l3" class="form-label small fw-bold text-dark mb-1">📝 Tus apuntes de la Lección 3 (Relaciones Binarias):</label>
                            <textarea id="discNoteInput-l3" class="form-control form-control-sm mb-2" rows="2" placeholder="Anota la diferencia entre Relación de Equivalencia y Orden Parcial..."></textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-success fw-semibold" id="discNoteStatus-l3"></span>
                                <button type="button" class="btn btn-xs btn-purple fw-bold px-3" id="discNoteSave-l3">Guardar apunte</button>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <strong>Tema:</strong> Producto cartesiano $A \times B$, reflexividad, simetría, antisimetría y transitividad.
                        </p>

                        <h3 class="h5 fw-bold text-purple mb-2">¿Qué es? (Introducción Teórica)</h3>
                        <p class="text-secondary leading-relaxed mb-3">
                            Una <strong>relación binaria</strong> $R$ de $A$ en $B$ es cualquier subconjunto del producto cartesiano $A \times B$ ($R \subseteq A \times B$). Cuando $R$ se define sobre un mismo conjunto $A$, se representa mediante una <strong>matriz booleana $M_R$</strong> y se clasifica según cuatro propiedades clave:
                        </p>

                        <div class="d-flex flex-column gap-2 mb-4">
                            <div class="p-3 rounded-4 border bg-light">
                                <h4 class="h6 fw-bold text-purple mb-1">1. Reflexiva</h4>
                                <p class="small text-secondary mb-0">Todo elemento está relacionado consigo mismo: $\forall a \in A, (a,a) \in R$ (diagonal principal de $M_R$ llena de unos).</p>
                            </div>
                            <div class="p-3 rounded-4 border bg-light">
                                <h4 class="h6 fw-bold text-purple mb-1">2. Simétrica vs. Antisimétrica</h4>
                                <p class="small text-secondary mb-0"><strong>Simétrica:</strong> si $(a,b) \in R \Rightarrow (b,a) \in R$. <strong>Antisimétrica:</strong> si $(a,b) \in R$ y $(b,a) \in R \Rightarrow a = b$.</p>
                            </div>
                            <div class="p-3 rounded-4 border bg-light">
                                <h4 class="h6 fw-bold text-purple mb-1">3. Transitiva</h4>
                                <p class="small text-secondary mb-0">Si $(a,b) \in R$ y $(b,c) \in R$, entonces necesariamente $(a,c) \in R$.</p>
                            </div>
                        </div>

                        <div class="disc-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                            <div>
                                <div class="fw-bold text-dark">Clasificación Formal:</div>
                                <div class="small text-muted">Equivalencia vs. Orden Parcial (Poset)</div>
                            </div>
                            <div class="disc-formula-math">$$\text{Equivalencia} = \text{Ref} \land \text{Sim} \land \text{Trans}$$</div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                            <div class="d-flex align-items-center gap-2 disc-feedback-group">
                                <span class="small text-muted">¿Útil?</span>
                                <button type="button" class="disc-feedback-btn" data-disc-feedback="up">👍</button>
                                <button type="button" class="disc-feedback-btn" data-disc-feedback="down">👎</button>
                                <span class="small text-purple fw-semibold disc-feedback-msg"></span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-purple btn-sm fw-bold" data-disc-goto="l3-video">
                                    ▶ Vídeo Relaciones
                                </button>
                                <button type="button" class="btn btn-purple fw-bold px-4 shadow-purple" data-disc-goto="l3-simulador">
                                    Ir al Simulador: Matriz $M_R$ ➔
                                </button>
                            </div>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 3 · ITEM 2: VÍDEO
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l3-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="disc-type-badge disc-badge-reading">▶ Lección 3 · Recursos Audiovisuales (Pop-up)</span>
                            <span class="text-muted small">Video: "Relaciones de Equivalencia y Orden"</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Relaciones Binarias y Propiedades</h2>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge text-white mb-2" style="background:#7e22ce;">Video Recomendado</span>
                                        <h3 class="h6 fw-bold mb-1">Relaciones de Equivalencia y Propiedades Binarias</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>Ciencias Básicas ITCV</strong></p>
                                        <p class="small text-secondary mb-3">Propiedades reflexiva, simétrica, transitiva y partición en clases de equivalencia.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-purple btn-sm fw-bold px-3" data-open-disc-video="l3-video" data-video-mode="main">
                                            ▶ Reproducir en Modal
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=4C5-eF_9w7s" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-secondary mb-2">Video Alternativo</span>
                                        <h3 class="h6 fw-bold mb-1">Relaciones de Orden Parcial (Poset)</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>MateFacil</strong></p>
                                        <p class="small text-secondary mb-3">Cómo identificar relaciones reflexivas, antisimétricas y transitivas.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-purple btn-sm fw-bold px-3" data-open-disc-video="l3-video" data-video-mode="alt">
                                            ▶ Reproducir Alternativo
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=R_18fA2R0fE" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-disc-goto="l3-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-purple fw-bold px-4" data-disc-goto="l3-simulador">Siguiente: Simulador Matriz $M_R$ ⚙</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 3 · ITEM 3: SIMULADOR (Matriz de Relación)
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l3-simulador">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="disc-type-badge disc-badge-sim">⚙ Lección 3 · Simulador Interactivo</span>
                            <span class="text-muted small">Analizador de Matriz Booleana $3 \times 3$</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Simulador: Analizador de Matriz de Relación $M_R$ sobre $A=\{1,2,3\}$</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Ejercicio:</strong> Haz clic en las celdas de la matriz $M_R$ para alternar entre $0$ y $1$ (o carga una relación predefinida) y observa el diagnóstico automático de propiedades.
                        </p>

                        <div class="disc-sim-box mb-4">
                            <div class="disc-sim-topbar">
                                <span class="fw-bold small" style="color:#d8b4fe;">⚙ Evaluador Matricial · Propiedades Binarias</span>
                                <span class="badge bg-info text-dark font-monospace" id="discL3ClassBadge">Relación de Equivalencia</span>
                            </div>
                            <div class="disc-sim-body">
                                <div class="row g-4 align-items-center mb-3">
                                    <div class="col-12 col-md-5 text-center">
                                        <div class="d-inline-block p-3 rounded-3 bg-dark border border-secondary">
                                            <div class="small text-slate-400 mb-2 font-monospace">Matriz $M_R$ ($A = \{1, 2, 3\}$)</div>
                                            <div id="discL3MatrixGrid" class="d-grid gap-2" style="grid-template-columns: repeat(3, 52px);"></div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <div class="row g-2 mb-3 text-center small">
                                            <div class="col-6"><span class="badge w-100 py-2" id="discL3PropRef">Reflexiva: SÍ</span></div>
                                            <div class="col-6"><span class="badge w-100 py-2" id="discL3PropSym">Simétrica: SÍ</span></div>
                                            <div class="col-6"><span class="badge w-100 py-2" id="discL3PropAnti">Antisimétrica: NO</span></div>
                                            <div class="col-6"><span class="badge w-100 py-2" id="discL3PropTrans">Transitiva: SÍ</span></div>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-light" data-disc-mat-preset="equiv">Cargar Equivalencia</button>
                                            <button type="button" class="btn btn-sm btn-outline-light" data-disc-mat-preset="poset">Cargar Orden Parcial (≤)</button>
                                            <button type="button" class="btn btn-sm btn-purple fw-bold" data-disc-mat-preset="identity">Identidad $I_3$</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="disc-sim-console" id="discL3Console"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-disc-goto="l3-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-purple fw-bold px-4" data-disc-goto="l3-pdf">Siguiente: Guía Visual 03 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 3 · ITEM 4: GUÍA PDF
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l3-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="disc-type-badge disc-badge-pdf">📄 Lección 3 · Guía Visual Incorporada</span>
                            <div class="d-flex align-items-center gap-2">
                                <code class="small">Guia_3_Matrices_Relacion.pdf</code>
                                <button type="button" class="btn btn-purple btn-sm fw-bold" data-print-guide="discGuideContent-3" data-guide-title="Guía 03 - Matrices de Relación">
                                    🖨️ Imprimir / Guardar PDF
                                </button>
                            </div>
                        </div>

                        <div class="disc-guide-container mb-4" id="discGuideContent-3" style="border-top: 8px solid #f59e0b;">
                            <header class="pb-3 mb-4 border-bottom">
                                <p class="text-xs fw-bold text-warning text-uppercase tracking-wider mb-1">Módulo: Matemáticas Discretas | Lección 3</p>
                                <h2 class="h3 fw-extrabold text-dark mb-1">GUÍA 03: Clasificación de Relaciones Binarias</h2>
                                <p class="text-secondary small mb-0">Criterios matriciales para equivalencia y orden parcial.</p>
                            </header>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="bg-dark text-white p-4 rounded-3 h-100">
                                        <p class="text-warning fw-bold code-font small mb-1">Relación de Equivalencia</p>
                                        <p class="small opacity-75 mb-2">Genera una partición del conjunto $A$ en clases de equivalencia disjuntas.</p>
                                        <div class="math-box">$$\text{Reflexiva} + \text{Simétrica} + \text{Transitiva}$$</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="bg-dark text-white p-4 rounded-3 h-100">
                                        <p class="text-info fw-bold code-font small mb-1">Orden Parcial (Poset)</p>
                                        <p class="small opacity-75 mb-2">Define jerarquías como la divisibilidad ($a \mid b$) o menor o igual ($\le$).</p>
                                        <div class="math-box">$$\text{Reflexiva} + \text{Antisimétrica} + \text{Transitiva}$$</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-disc-goto="l3-simulador">← Anterior: Simulador</button>
                            <button type="button" class="btn btn-purple fw-bold px-4" data-disc-goto="l4-lectura">Ir a Lección 4: Teoría de Grafos →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 4 · ITEM 1: LECTURA (Teoría de Grafos y Árboles)
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l4-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <div>
                                <span class="disc-type-badge disc-badge-reading mb-1">📖 Lección 4 · Lectura Activa</span>
                                <h2 class="h3 fw-bold mb-0">Teoría de Grafos y Árboles</h2>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-purple fw-semibold" data-disc-note-toggle="l4">
                                📝 Guardar nota
                            </button>
                        </div>

                        <div class="disc-note-drawer d-none" id="discNoteDrawer-l4">
                            <label for="discNoteInput-l4" class="form-label small fw-bold text-dark mb-1">📝 Tus apuntes de la Lección 4 (Grafos y Árboles):</label>
                            <textarea id="discNoteInput-l4" class="form-control form-control-sm mb-2" rows="2" placeholder="Anota el Teorema del Apretón de Manos y las condiciones de un árbol..."></textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-success fw-semibold" id="discNoteStatus-l4"></span>
                                <button type="button" class="btn btn-xs btn-purple fw-bold px-3" id="discNoteSave-l4">Guardar apunte</button>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <strong>Tema:</strong> Vértices, aristas, grados, conectividad y árboles generadores.
                        </p>

                        <h3 class="h5 fw-bold text-purple mb-2">¿Qué es? (Introducción Teórica)</h3>
                        <p class="text-secondary leading-relaxed mb-3">
                            Un <strong>grafo</strong> $G = (V, E)$ modela redes mediante un conjunto de vértices o nodos $V$ y un conjunto de aristas $E$. Un <strong>árbol</strong> es un caso especial de grafo no dirigido que es <strong>conexo y acíclico</strong> (sin ciclos).
                        </p>

                        <div class="disc-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Teorema del Apretón de Manos (Handshaking Lemma):</div>
                                <div class="small text-muted">Cada arista aporta 2 unidades a la suma total de grados</div>
                            </div>
                            <div class="disc-formula-math">$$\sum_{v \in V} \deg(v) = 2|E|$$</div>
                        </div>

                        <div class="disc-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                            <div>
                                <div class="fw-bold text-dark">Propiedad Fundamental de los Árboles:</div>
                                <div class="small text-muted">Todo árbol conexo con $n$ vértices tiene exactamente $n-1$ aristas</div>
                            </div>
                            <div class="disc-formula-math">$$|E| = |V| - 1$$</div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                            <div class="d-flex align-items-center gap-2 disc-feedback-group">
                                <span class="small text-muted">¿Útil?</span>
                                <button type="button" class="disc-feedback-btn" data-disc-feedback="up">👍</button>
                                <button type="button" class="disc-feedback-btn" data-disc-feedback="down">👎</button>
                                <span class="small text-purple fw-semibold disc-feedback-msg"></span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-purple btn-sm fw-bold" data-disc-goto="l4-video">
                                    ▶ Vídeo Grafos
                                </button>
                                <button type="button" class="btn btn-purple fw-bold px-4 shadow-purple" data-disc-goto="l4-reto">
                                    Ir al Reto ABP: Red de Nodos ➔
                                </button>
                            </div>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 4 · ITEM 2: VÍDEO
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l4-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="disc-type-badge disc-badge-reading">▶ Lección 4 · Recursos Audiovisuales (Pop-up)</span>
                            <span class="text-muted small">Video: "Vértices, Aristas y Conectividad"</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Teoría de Grafos y Árboles</h2>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge text-white mb-2" style="background:#7e22ce;">Video Recomendado</span>
                                        <h3 class="h6 fw-bold mb-1">Introducción a la Teoría de Grafos y Conectividad</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>Matemáticas Discretas</strong></p>
                                        <p class="small text-secondary mb-3">Definición de grado de vértices, caminos simples y grafos conexos.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-purple btn-sm fw-bold px-3" data-open-disc-video="l4-video" data-video-mode="main">
                                            ▶ Reproducir en Modal
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=qZ8i_51f0qE" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-secondary mb-2">Video Alternativo</span>
                                        <h3 class="h6 fw-bold mb-1">Árboles Generadores y Teorema del Apretón de Manos</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>El Traductor de Ingeniería</strong></p>
                                        <p class="small text-secondary mb-3">Visualización intuitiva de redes sin ciclos y árboles generadores mínimos.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-purple btn-sm fw-bold px-3" data-open-disc-video="l4-video" data-video-mode="alt">
                                            ▶ Reproducir Alternativo
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=vnNFiNVy9jg" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-disc-goto="l4-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-purple fw-bold px-4" data-disc-goto="l4-reto">Siguiente: Reto ABP Red de Nodos ⚡</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 4 · ITEM 3: RETO ABP (Verificador de Grafos/Árboles)
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l4-reto">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="disc-type-badge disc-badge-abp">⚡ Lección 4 · Reto ABP</span>
                            <span class="text-muted small">Constructor de Grafos y Árboles ($|V|=5$)</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Reto ABP: Red de Nodos — Verificador de Árboles</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Misión:</strong> Activa o desactiva aristas entre los 5 nodos ($V_1 \dots V_5$) para lograr una topología de <strong>Árbol Conexo</strong> ($|E| = n - 1 = 4$ aristas sin ciclos) comprobando $\sum \deg(v) = 2|E|$.
                        </p>

                        <div class="disc-sim-box mb-4">
                            <div class="disc-sim-topbar">
                                <span class="fw-bold small" style="color:#d8b4fe;">⚡ Analizador de Topología · $|V| = 5$</span>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-info text-dark font-monospace" id="discL4EdgesBadge">|E| = 4 aristas</span>
                                    <span class="badge bg-warning text-dark font-monospace" id="discL4DegBadge">∑deg(v) = 8</span>
                                    <span class="badge bg-emerald text-white font-monospace" id="discL4TreeBadge">✓ ES UN ÁRBOL</span>
                                </div>
                            </div>
                            <div class="disc-sim-body">
                                <div class="row g-4 align-items-center mb-3">
                                    <div class="col-12 col-md-6">
                                        <div class="disc-sim-canvas-wrap">
                                            <svg viewBox="0 0 300 200" class="w-100" aria-label="Grafo Interactivo de 5 Vértices">
                                                <g id="discL4SvgEdges"></g>
                                                <g id="discL4SvgNodes"></g>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="small text-slate-300 fw-bold mb-2">Interruptores de Aristas ($E$):</div>
                                        <div id="discL4EdgeToggles" class="d-flex flex-wrap gap-2 mb-3"></div>
                                        <button type="button" class="btn btn-sm btn-purple fw-bold w-100" id="discL4SpanningBtn">
                                            Generar Árbol Generador Válido ($|E|=4$) ▷
                                        </button>
                                    </div>
                                </div>
                                <div class="disc-sim-console" id="discL4Console"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-disc-goto="l4-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-purple fw-bold px-4" data-disc-goto="l4-pdf">Siguiente: Guía Visual 04 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 4 · ITEM 4: GUÍA PDF
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l4-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="disc-type-badge disc-badge-pdf">📄 Lección 4 · Guía Visual Incorporada</span>
                            <div class="d-flex align-items-center gap-2">
                                <code class="small">Guia_4_Grafos_y_Arboles.pdf</code>
                                <button type="button" class="btn btn-purple btn-sm fw-bold" data-print-guide="discGuideContent-4" data-guide-title="Guía 04 - Grafos y Árboles">
                                    🖨️ Imprimir / Guardar PDF
                                </button>
                            </div>
                        </div>

                        <div class="disc-guide-container mb-4" id="discGuideContent-4" style="border-top: 8px solid #7e22ce;">
                            <header class="pb-3 mb-4 border-bottom">
                                <p class="text-xs fw-bold text-purple text-uppercase tracking-wider mb-1">Módulo: Matemáticas Discretas | Lección 4</p>
                                <h2 class="h3 fw-extrabold text-dark mb-1">GUÍA 04: Grafos, Conectividad y Árboles</h2>
                                <p class="text-secondary small mb-0">Leyes estructurales de redes discretas.</p>
                            </header>
                            <div class="bg-dark text-white rounded-4 p-4">
                                <div class="math-box mb-3">$$\sum_{v \in V} \deg(v) = 2|E| \qquad \text{y en todo árbol:} \qquad |E| = |V| - 1$$</div>
                                <p class="small text-light opacity-90 mb-0 text-center">
                                    Todo árbol es conexo y acíclico. Los vértices de grado 1 se denominan <strong>hojas</strong> (nodos terminales).
                                </p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-disc-goto="l4-reto">← Anterior: Reto ABP</button>
                            <button type="button" class="btn btn-purple fw-bold px-4" data-disc-goto="l5-lectura">Ir a Lección 5: Álgebra Booleana →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 5 · ITEM 1: LECTURA (Álgebra Booleana y Circuitos)
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l5-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <div>
                                <span class="disc-type-badge disc-badge-reading mb-1">📖 Lección 5 · Lectura Activa</span>
                                <h2 class="h3 fw-bold mb-0">Álgebra Booleana y Circuitos Lógicos</h2>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-purple fw-semibold" data-disc-note-toggle="l5">
                                📝 Guardar nota
                            </button>
                        </div>

                        <div class="disc-note-drawer d-none" id="discNoteDrawer-l5">
                            <label for="discNoteInput-l5" class="form-label small fw-bold text-dark mb-1">📝 Tus apuntes de la Lección 5 (Álgebra Booleana):</label>
                            <textarea id="discNoteInput-l5" class="form-control form-control-sm mb-2" rows="2" placeholder="Anota los postulados de Boole y compuertas universales NAND/NOR..."></textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-success fw-semibold" id="discNoteStatus-l5"></span>
                                <button type="button" class="btn btn-xs btn-purple fw-bold px-3" id="discNoteSave-l5">Guardar apunte</button>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <strong>Tema:</strong> Compuertas digitales (`AND`, `OR`, `NOT`, `XOR`, `NAND`, `NOR`) y Mapas de Karnaugh.
                        </p>

                        <h3 class="h5 fw-bold text-purple mb-2">¿Qué es? (Introducción Teórica)</h3>
                        <p class="text-secondary leading-relaxed mb-3">
                            El <strong>Álgebra de Boole</strong> opera sobre el conjunto binario $\{0, 1\}$. Sus operaciones se materializan físicamente en <strong>compuertas lógicas</strong> dentro de los procesadores, y se simplifican mediante <strong>Mapas de Karnaugh</strong> para reducir el número de transistores.
                        </p>

                        <div class="disc-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Ley de Absorción e Idempotencia:</div>
                                <div class="small text-muted">Simplificación directa de términos redundantes</div>
                            </div>
                            <div class="disc-formula-math">$$A + (A \cdot B) = A \qquad | \qquad A + A = A$$</div>
                        </div>

                        <div class="disc-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                            <div>
                                <div class="fw-bold text-dark">Compuerta Universal NAND (De Morgan):</div>
                                <div class="small text-muted">Cualquier circuito lógico puede construirse solo con NAND o NOR</div>
                            </div>
                            <div class="disc-formula-math">$$\overline{A \cdot B} = \overline{A} + \overline{B}$$</div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                            <div class="d-flex align-items-center gap-2 disc-feedback-group">
                                <span class="small text-muted">¿Útil?</span>
                                <button type="button" class="disc-feedback-btn" data-disc-feedback="up">👍</button>
                                <button type="button" class="disc-feedback-btn" data-disc-feedback="down">👎</button>
                                <span class="small text-purple fw-semibold disc-feedback-msg"></span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-purple btn-sm fw-bold" data-disc-goto="l5-video">
                                    ▶ Vídeo Compuertas
                                </button>
                                <button type="button" class="btn btn-purple fw-bold px-4 shadow-purple" data-disc-goto="l5-simulador">
                                    Ir al Simulador: Compuertas Digitales ➔
                                </button>
                            </div>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 5 · ITEM 2: VÍDEO
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l5-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="disc-type-badge disc-badge-reading">▶ Lección 5 · Recursos Audiovisuales (Pop-up)</span>
                            <span class="text-muted small">Video: "Compuertas Lógicas y Mapas de Karnaugh"</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Álgebra Booleana y Circuitos Digitales</h2>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge text-white mb-2" style="background:#7e22ce;">Video Recomendado</span>
                                        <h3 class="h6 fw-bold mb-1">Compuertas Lógicas AND, OR, NOT, XOR y Álgebra Booleana</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>Electrónica Digital</strong></p>
                                        <p class="small text-secondary mb-3">Funcionamiento binario de compuertas básicas y universales.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-purple btn-sm fw-bold px-3" data-open-disc-video="l5-video" data-video-mode="main">
                                            ▶ Reproducir en Modal
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=8eWl_j8yDkQ" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-secondary mb-2">Video Alternativo</span>
                                        <h3 class="h6 fw-bold mb-1">Simplificación con Mapas de Karnaugh paso a paso</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>Pasos por Ingeniería</strong></p>
                                        <p class="small text-secondary mb-3">Agrupación de mintérminos para minimizar circuitos booleanos.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-purple btn-sm fw-bold px-3" data-open-disc-video="l5-video" data-video-mode="alt">
                                            ▶ Reproducir Alternativo
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=1r4pA0s_q2g" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-disc-goto="l5-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-purple fw-bold px-4" data-disc-goto="l5-simulador">Siguiente: Simulador Compuertas ⚙</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 5 · ITEM 3: SIMULADOR (Compuertas Digitales)
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l5-simulador">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="disc-type-badge disc-badge-sim">⚙ Lección 5 · Simulador Interactivo</span>
                            <span class="text-muted small">Banco de Pruebas de Lógica Digital</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Simulador: Banco de Compuertas Lógicas Digitales</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Ejercicio:</strong> Cambia el estado binario de las entradas $A$ y $B$ ($0$ o $1$) y selecciona la compuerta lógica para verificar el encendido del LED de salida ($Y$).
                        </p>

                        <div class="disc-sim-box mb-4">
                            <div class="disc-sim-topbar">
                                <span class="fw-bold small" style="color:#d8b4fe;">🔌 Circuito Lógico Virtual · Entradas {0, 1}</span>
                                <span class="badge bg-emerald text-white font-monospace" id="discL5OutBadge">SALIDA Y = 1 (ENCENDIDO)</span>
                            </div>
                            <div class="disc-sim-body">
                                <div class="row g-4 align-items-center mb-3">
                                    <div class="col-12 col-md-6">
                                        <div class="disc-sim-canvas-wrap">
                                            <svg viewBox="0 0 320 150" class="w-100" aria-label="Circuito de Compuerta Lógica">
                                                <line id="discL5WireA" x1="35" y1="50" x2="120" y2="50" stroke="#10b981" stroke-width="4" />
                                                <line id="discL5WireB" x1="35" y1="100" x2="120" y2="100" stroke="#10b981" stroke-width="4" />
                                                <line id="discL5WireY" x1="205" y1="75" x2="275" y2="75" stroke="#10b981" stroke-width="4" />
                                                <rect x="120" y="32" width="85" height="86" rx="14" fill="#3b0764" stroke="#c084fc" stroke-width="2.5" />
                                                <text id="discL5GateLabel" x="162" y="80" font-size="14" fill="#ffffff" text-anchor="middle" font-weight="bold">AND</text>
                                                <text id="discL5TxtA" x="22" y="54" font-size="12" fill="#34d399" font-weight="bold">A=1</text>
                                                <text id="discL5TxtB" x="22" y="104" font-size="12" fill="#34d399" font-weight="bold">B=1</text>
                                                <circle id="discL5Led" cx="285" cy="75" r="14" fill="#10b981" stroke="#ffffff" stroke-width="2" />
                                                <text id="discL5TxtY" x="285" y="106" font-size="11" fill="#fde047" text-anchor="middle" font-weight="bold">Y=1</text>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-2">
                                            <label for="discL5GateSelect" class="form-label small text-slate-300 mb-1">Tipo de Compuerta Lógica:</label>
                                            <select id="discL5GateSelect" class="form-select form-select-sm bg-dark text-white border-secondary">
                                                <option value="AND" selected>AND (Y = A · B)</option>
                                                <option value="OR">OR (Y = A + B)</option>
                                                <option value="XOR">XOR (Y = A ⊕ B)</option>
                                                <option value="NAND">NAND Universal (Y = (A · B)')</option>
                                                <option value="NOR">NOR Universal (Y = (A + B)')</option>
                                            </select>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <button type="button" class="btn btn-sm btn-outline-info w-100 fw-bold font-monospace" id="discL5ToggleA">Entrada A: 1</button>
                                            </div>
                                            <div class="col-6">
                                                <button type="button" class="btn btn-sm btn-outline-info w-100 fw-bold font-monospace" id="discL5ToggleB">Entrada B: 1</button>
                                            </div>
                                        </div>
                                        <div class="text-xs text-slate-400">Haz clic en los botones A y B para conmutar bits (0 / 1).</div>
                                    </div>
                                </div>
                                <div class="disc-sim-console" id="discL5Console"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-disc-goto="l5-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-purple fw-bold px-4" data-disc-goto="l5-pdf">Siguiente: Guía Visual 05 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 5 · ITEM 4: GUÍA PDF
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="l5-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="disc-type-badge disc-badge-pdf">📄 Lección 5 · Guía Visual Incorporada</span>
                            <div class="d-flex align-items-center gap-2">
                                <code class="small">Guia_5_Mapas_Karnaugh.pdf</code>
                                <button type="button" class="btn btn-purple btn-sm fw-bold" data-print-guide="discGuideContent-5" data-guide-title="Guía 05 - Álgebra Booleana y Mapas de Karnaugh">
                                    🖨️ Imprimir / Guardar PDF
                                </button>
                            </div>
                        </div>

                        <div class="disc-guide-container mb-4" id="discGuideContent-5" style="border-top: 8px solid #e11d48;">
                            <header class="pb-3 mb-4 border-bottom">
                                <p class="text-xs fw-bold text-danger text-uppercase tracking-wider mb-1">Módulo: Matemáticas Discretas | Lección 5</p>
                                <h2 class="h3 fw-extrabold text-dark mb-1">GUÍA 05: Postulados de Boole y Mapas de Karnaugh</h2>
                                <p class="text-secondary small mb-0">Minimización de expresiones booleanas para circuitos digitales.</p>
                            </header>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="p-3 rounded-3 border bg-light h-100">
                                        <h4 class="h6 fw-bold text-purple">Postulados Clave</h4>
                                        <ul class="small text-secondary mb-0">
                                            <li><strong>Identidad:</strong> $A + 0 = A$, $A \cdot 1 = A$</li>
                                            <li><strong>Complemento:</strong> $A + \overline{A} = 1$, $A \cdot \overline{A} = 0$</li>
                                            <li><strong>Absorción:</strong> $A + (A \cdot B) = A$</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="p-3 rounded-3 border bg-light h-100">
                                        <h4 class="h6 fw-bold text-purple">Regla de Agrupación Karnaugh</h4>
                                        <p class="small text-secondary mb-0">Agrupa celdas adyacentes con valor $1$ en potencias de 2 ($1, 2, 4, 8$) para eliminar variables que cambian de estado.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-disc-goto="l5-simulador">← Anterior: Simulador</button>
                            <button type="button" class="btn btn-purple fw-bold px-4 shadow-purple" data-disc-goto="cierre-encuesta">Ir al Módulo de Cierre y Certificación 🎓 →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         MÓDULO DE CIERRE · ITEM 1: ENCUESTA ESCALA LIKERT (1 AL 5)
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="cierre-encuesta">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="disc-type-badge disc-badge-closing">📊 Módulo de Cierre · Instrumento de Evaluación</span>
                            <button type="button" class="btn btn-xs btn-outline-purple fw-semibold" id="discSurveyQuickFillBtn">
                                ⚡ Autocompletar demostración
                            </button>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Instrumento de Evaluación: Encuesta de Satisfacción e Impacto del Módulo Interactivo</h2>
                        <p class="small text-secondary leading-relaxed mb-3">
                            <strong>Instrucciones:</strong> La presente encuesta tiene como propósito conocer tu percepción, nivel de satisfacción y el impacto académico derivado del uso de los simuladores lógicos y la metodología de Aprendizaje Basado en Problemas (ABP) durante este módulo en el <strong>Instituto Tecnológico de Ciudad Victoria</strong>.
                        </p>

                        <div class="d-flex flex-wrap gap-2 p-2 rounded-3 bg-light border small mb-4">
                            <span class="badge" style="background:#7e22ce;">5: Totalmente de acuerdo (TA)</span>
                            <span class="badge bg-success">4: De acuerdo (DA)</span>
                            <span class="badge bg-secondary">3: Neutral / Indeciso (N)</span>
                            <span class="badge bg-warning text-dark">2: En desacuerdo (ED)</span>
                            <span class="badge bg-danger">1: Totalmente en desacuerdo (TED)</span>
                        </div>

                        <form id="discLikertSurveyForm">
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
                                            <label class="form-check-label"><input type="radio" name="disc_sexo" value="M" class="form-check-input me-1">M</label>
                                            <label class="form-check-label"><input type="radio" name="disc_sexo" value="F" class="form-check-input me-1">F</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold mb-1">Módulo Evaluado:</label>
                                        <input type="text" class="form-control form-control-sm fw-bold text-purple" value="Matemáticas Discretas (Lógica, Estructuras y Simulación)" readonly>
                                    </div>
                                </div>
                            </div>

                            <h3 class="h6 fw-bold mb-2">Apartado II: Valoración de la Experiencia y el Impacto (Escala Likert)</h3>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered disc-likert-table align-middle small mb-0">
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
                                        $criteriosDiscretas = [
                                            1  => ['Utilidad visual:', 'Los simuladores interactivos (tablas de verdad, Venn, matrices, grafos y compuertas) facilitaron la visualización de los conceptos discretos sin depender solo de la memorización.'],
                                            2  => ['Reducción de carga cognitiva:', 'El uso de las herramientas visuales disminuyó la frustración al analizar demostraciones lógicas, relaciones y circuitos booleanos.'],
                                            3  => ['Aprendizaje Basado en Problemas (ABP):', 'Los retos prácticos basados en redes y circuitos le dieron un sentido lógico y aplicable a la ingeniería en sistemas.'],
                                            4  => ['Razonamiento vs. Memorización:', 'Las actividades interactivas me ayudaron a razonar el procedimiento lógico en lugar de solo memorizar tablas.'],
                                            5  => ['Puente cognitivo hacia la programación:', 'La estructura de las lecciones me permitió conectar la lógica proposicional y booleana con las estructuras condicionales del código.'],
                                            6  => ['Autonomía en el aprendizaje:', 'Los recursos didácticos y guías imprimibles me permitieron avanzar a mi propio ritmo de forma independiente.'],
                                            7  => ['Motivación académica:', 'La dinámica interactiva de la plataforma incrementó mi interés y motivación por superar los retos de Matemáticas Discretas.'],
                                            8  => ['Reducción del riesgo de reprobación:', 'Considero que este curso de reforzamiento mejora significativamente mis probabilidades de aprobar con éxito la asignatura.'],
                                            9  => ['Satisfacción general con la plataforma:', 'La interfaz, el diseño y la distribución de las 5 lecciones del módulo resultaron intuitivas y agradables de utilizar.'],
                                            10 => ['Pertinencia institucional:', 'Recomendaría la implementación oficial de esta herramienta interactiva como apoyo para los estudiantes de nuevo ingreso.'],
                                        ];
                                        foreach ($criteriosDiscretas as $num => [$tituloCrit, $descCrit]):
                                        ?>
                                            <tr>
                                                <td class="text-center fw-bold"><?= $num ?></td>
                                                <td><strong><?= e($tituloCrit) ?></strong> <?= e($descCrit) ?></td>
                                                <?php for ($val = 5; $val >= 1; $val--): ?>
                                                    <td class="text-center">
                                                        <input type="radio" class="disc-likert-radio" name="disc_likert_<?= $num ?>" value="<?= $val ?>" aria-label="Pregunta <?= $num ?> opción <?= $val ?>">
                                                    </td>
                                                <?php endfor; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="p-3 rounded-3 border bg-light mb-3">
                                <h3 class="h6 fw-bold mb-3">Apartado III: Retroalimentación Cualitativa (Comentarios Abiertos)</h3>
                                <div class="mb-2">
                                    <label for="discComment1" class="form-label small fw-semibold mb-1">1. ¿Qué fue lo que más te gustó de los simuladores visuales y los retos de este módulo?</label>
                                    <textarea id="discComment1" class="form-control form-control-sm" rows="2"></textarea>
                                </div>
                                <div class="mb-2">
                                    <label for="discComment2" class="form-label small fw-semibold mb-1">2. ¿En qué aspectos consideras que las lecciones podrían mejorar?</label>
                                    <textarea id="discComment2" class="form-control form-control-sm" rows="2"></textarea>
                                </div>
                                <div>
                                    <label for="discComment3" class="form-label small fw-semibold mb-1">3. Comentarios o sugerencias adicionales:</label>
                                    <textarea id="discComment3" class="form-control form-control-sm" rows="2"></textarea>
                                </div>
                            </div>

                            <div id="discSurveyStatusAlert" class="d-none"></div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                                <button type="submit" class="btn btn-purple fw-bold px-4">
                                    Enviar Encuesta y Validar Progreso ✓
                                </button>
                                <button type="button" id="discBtnDownloadCertSurvey" class="btn btn-secondary fw-bold px-4 opacity-75" disabled>
                                    🔒 Descargar Certificado
                                </button>
                            </div>
                        </form>
                    </article>

                    <!-- =====================================================
                         MÓDULO DE CIERRE · ITEM 2: CERTIFICADO DE ACREDITACIÓN
                         ===================================================== -->
                    <article class="disc-content-pane" data-disc-pane="cierre-certificado">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="disc-type-badge disc-badge-closing">🎓 Módulo de Cierre · Acreditación de Matemáticas Discretas</span>
                            <span class="text-muted small font-monospace">Folio: <?= e($folioCertificado) ?></span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Certificado de Acreditación: Matemáticas Discretas</h2>

                        <div id="discCertLockNotice" class="alert alert-warning d-flex align-items-center gap-2 py-2 px-3 small mb-3">
                            <span>🔒</span>
                            <div>
                                <strong>Requisito previo:</strong> Para desbloquear el botón <strong>"Descargar Certificado"</strong>, primero envía la <button type="button" class="btn btn-link btn-sm p-0 align-baseline fw-bold text-purple" data-disc-goto="cierre-encuesta">Encuesta de Satisfacción e Impacto</button>.
                            </div>
                        </div>

                        <div id="discCertPrintableArea" class="p-4 rounded-4 bg-white text-center shadow-sm mb-4" style="border: 6px double #6b21a8;">
                            <div class="text-uppercase text-xs tracking-wider text-purple fw-bold mb-1">
                                Instituto Tecnológico de Ciudad Victoria · Lógica Visual ABP
                            </div>
                            <h3 class="h5 fw-bold text-dark mb-3">CERTIFICADO DE ACREDITACIÓN</h3>
                            <p class="small text-secondary mb-1">Se otorga el presente reconocimiento a:</p>
                            <div class="h3 fw-bold text-purple my-2"><?= e($nombreAlumno) ?></div>
                            <p class="small text-secondary max-w-2xl mx-auto mb-3">
                                Por haber acreditado satisfactoriamente las 5 lecciones interactivas, simuladores lógicos y retos de Aprendizaje Basado en Problemas del módulo:
                            </p>
                            <div class="d-inline-block px-4 py-2 rounded-3 fw-bold h5 mb-3" style="background:#faf5ff; border:2px solid #7e22ce; color:#6b21a8;">
                                <?= e($moduloCompletado) ?>
                            </div>
                            <div class="d-flex flex-wrap justify-content-around align-items-center small text-muted pt-3 border-top mt-2">
                                <div><strong>Fecha de finalización:</strong> <?= e($fechaActual) ?></div>
                                <div><strong>Folio de verificación:</strong> <span class="font-monospace"><?= e($folioCertificado) ?></span></div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-disc-goto="cierre-encuesta">← Volver a la Encuesta</button>
                            <button type="button" id="discBtnDownloadCertPane" class="btn btn-secondary fw-bold px-4 opacity-75" disabled>
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
    <div class="modal fade" id="discYoutubeModal" tabindex="-1" aria-labelledby="discYoutubeModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
                <div class="modal-header disc-modal-header py-3 px-4">
                    <div>
                        <span class="badge text-white fw-bold mb-1" id="discYoutubeModalChannel" style="background:#8b5cf6;">Canal Oficial</span>
                        <h3 class="modal-title h6 fw-bold mb-0 text-white" id="discYoutubeModalTitle">Reproductor de Vídeo</h3>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0 bg-black">
                    <div class="ratio ratio-16x9">
                        <iframe id="discYoutubeIframe"
                            src=""
                            title="Reproductor de video de Matemáticas Discretas en YouTube"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                    </div>
                </div>
                <div class="modal-footer bg-light d-flex flex-wrap justify-content-between align-items-center px-4 py-3">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="https://www.youtube.com/watch?v=pU6QhR54fcs" id="discYoutubeDirectLink" target="_blank" rel="noopener noreferrer" class="btn btn-danger btn-sm fw-bold px-3">
                            ▶ Abrir directamente en YouTube ↗
                        </a>
                        <button type="button" id="discYoutubeAltBtn" class="btn btn-outline-purple btn-sm fw-semibold d-none">
                            🎬 Ver Video Alternativo
                        </button>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm fw-bold px-3" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('js/matematicas_discretas.js') ?>"></script>
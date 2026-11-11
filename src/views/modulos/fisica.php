<?php
/**
 * Vista Principal del Curso: Física (Causa, Efecto y Simulación)
 * Diseño Split-Screen E-Learning (Sidebar Izquierdo 30% / Panel Principal 70%)
 * Paleta Clean UI: Verde Azulado (Teal) y Esmeralda
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
$moduloCompletado = 'Física (Causa, Efecto y Simulación)';
$fechaActual      = ($inscripcion !== null && $inscripcion->finalizadoEn !== null)
    ? fecha($inscripcion->finalizadoEn, 'd/m/Y')
    : date('d/m/Y');
$folioCertificado = sprintf(
    'LV-FIS-%05d-%04d',
    $inscripcion !== null ? $inscripcion->id : 4,
    $usuarioActual !== null ? $usuarioActual->id : 1
);
?>

<div class="fis-elearning-wrapper" id="fisicaCursoApp">
    <!-- Metadatos dinámicos de sesión para el generador del Certificado PDF -->
    <div id="fisCertificateData"
         class="d-none"
         data-nombre-alumno="<?= e($nombreAlumno) ?>"
         data-modulo-completado="<?= e($moduloCompletado) ?>"
         data-fecha-emision="<?= e($fechaActual) ?>"
         data-folio="<?= e($folioCertificado) ?>"></div>

    <!-- Encabezado Superior del Curso (Paleta Teal / Esmeralda) -->
    <section class="fis-hero-banner text-white py-4 position-relative">
        <div class="bg-grid position-absolute top-0 start-0 w-100 h-100 pointer-events-none"></div>
        <div class="container-fluid max-w-7xl mx-auto px-3 px-md-4 position-relative z-1">
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?= url('/modulos') ?>" class="text-slate-300 text-decoration-none">Módulos Interactivos</a></li>
                    <li class="breadcrumb-item active text-emerald fw-semibold" aria-current="page">Física: Causa, Efecto y Simulación</li>
                </ol>
            </nav>

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="module-icon-box bg-emerald-subtle text-emerald fs-3" style="background: rgba(16,185,129,0.2); color: #34d399;">⚛</div>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <h1 class="h3 fw-bold text-white mb-0">Física (Enfoque de Causa, Efecto y Simulación)</h1>
                            <span class="badge rounded-pill fw-bold" style="background:#10b981; color:#042f2e;">Nivel Inicial · ABP</span>
                        </div>
                        <p class="small text-slate-200 mb-0 max-w-4xl">
                            En Física, la lógica algorítmica se utiliza para predecir estados. Las condicionales lógicas (<em>"Si se aplica esta fuerza, entonces el objeto acelera"</em>) son la base de estas 5 lecciones interactivas.
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <?php if (Auth::check() && $inscripcion === null): ?>
                        <form action="<?= url('/modulos/' . e($modulo->slug) . '/inscripcion') ?>" method="POST" class="m-0">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-teal btn-sm fw-bold px-3 shadow-teal">
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
        <div class="fis-split-layout">

            <!-- =============================================================
                 1. PANEL LATERAL IZQUIERDO (30%): RUTA DE APRENDIZAJE Y NAVEGACIÓN
                 ============================================================= -->
            <aside class="fis-sidebar-panel" aria-label="Ruta de Aprendizaje y Navegación de Física">
                <div class="fis-sidebar-card">
                    <div class="fis-sidebar-header">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-uppercase fw-bold text-xs tracking-wider text-teal">Ruta de Aprendizaje</span>
                            <a href="<?= url('/modulos') ?>" class="fis-sidebar-close-btn" title="Volver al catálogo de módulos" aria-label="Cerrar curso">✕</a>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h2 class="h6 fw-bold mb-0">Física: Causa, Efecto y Simulación</h2>
                            <span class="badge bg-success-subtle text-success rounded-pill" id="fisGlobalProgressText">1/22 elementos (5%)</span>
                        </div>
                        <div class="progress" style="height: 7px;">
                            <div class="progress-bar" id="fisGlobalProgressBar" role="progressbar" style="width: 5%; background-color: #0d9488;"></div>
                        </div>
                    </div>

                    <div class="accordion fis-lesson-accordion" id="fisSidebarAccordion">
                        <!-- LECCIÓN 1 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="fisHeadL1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#fisCollapseL1" aria-expanded="true" aria-controls="fisCollapseL1">
                                    <div>
                                        Lección 1: Vectores y Sistemas de Referencia
                                        <span class="fis-lesson-meta" id="fisLessonCount-1">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="fisCollapseL1" class="accordion-collapse collapse show" aria-labelledby="fisHeadL1">
                                <ul class="fis-nav-list">
                                    <li>
                                        <button type="button" class="fis-nav-item is-active" data-fis-item="l1-lectura" data-fis-lesson="1" data-fis-type="lectura">
                                            <span class="fis-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Teoría: Magnitudes y Componentes $x, y$</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l1-video" data-fis-lesson="1" data-fis-type="video"
                                                data-video-title="Vectores Introducción | Qué es un vector y sus características"
                                                data-video-channel="Canal: Matemáticas profe Alex"
                                                data-video-embed="https://www.youtube.com/embed/IrTeyyzerjI"
                                                data-video-url="https://www.youtube.com/watch?v=IrTeyyzerjI"
                                                data-video-alt-title="Magnitudes Escalares y Magnitudes Vectoriales (Repaso rápido 2 min)"
                                                data-video-alt-channel="Canal: A Cierta Ciencia"
                                                data-video-alt-embed="https://www.youtube.com/embed/C7cORnM76yI"
                                                data-video-alt-url="https://www.youtube.com/watch?v=C7cORnM76yI"
                                                data-video-alt-short="Magnitudes Escalares vs Vectoriales">
                                            <span class="fis-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • Vectores Introducción (Pop-up YouTube)</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l1-simulador" data-fis-lesson="1" data-fis-type="simulador">
                                            <span class="fis-nav-icon">⚙</span>
                                            <span><strong>Simulador</strong> • Plano Cartesiano Interactivo (Modificar ángulos y magnitudes)</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l1-pdf" data-fis-lesson="1" data-fis-type="pdf">
                                            <span class="fis-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • Guia_1_Vectores_y_Trigonometria.pdf</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- LECCIÓN 2 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="fisHeadL2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fisCollapseL2" aria-expanded="false" aria-controls="fisCollapseL2">
                                    <div>
                                        Lección 2: Cinemática en 1 y 2 Dimensiones
                                        <span class="fis-lesson-meta" id="fisLessonCount-2">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="fisCollapseL2" class="accordion-collapse collapse" aria-labelledby="fisHeadL2">
                                <ul class="fis-nav-list">
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l2-lectura" data-fis-lesson="2" data-fis-type="lectura">
                                            <span class="fis-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Teoría: MRU, MRUA y Tiro Parabólico</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l2-video" data-fis-lesson="2" data-fis-type="video"
                                                data-video-title="Introducción a la cinemática y clasificación del movimiento"
                                                data-video-channel="Canal: PROFE JAPIÑON ALVAREZ"
                                                data-video-embed="https://www.youtube.com/embed/p18S7349xPU"
                                                data-video-url="https://www.youtube.com/watch?v=p18S7349xPU"
                                                data-video-alt-title="TIRO PARABÓLICO (CINEMÁTICA) — Conecta visualmente los ejes x e y"
                                                data-video-alt-channel="Canal: Ciencia Vlog"
                                                data-video-alt-embed="https://www.youtube.com/embed/3Y1ZZNLylJc"
                                                data-video-alt-url="https://www.youtube.com/watch?v=3Y1ZZNLylJc"
                                                data-video-alt-short="Tiro Parabólico (Ciencia Vlog)"
                                                data-video-playlist="https://youtube.com/playlist?list=PL67radScKEvhRI0Xx7v6j5IzjfAAKe4nm&si=Ws_ZlDueAQCbX0OQ">
                                            <span class="fis-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • Cinemática y Clasificación del movimiento (Pop-up)</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l2-reto" data-fis-lesson="2" data-fis-type="reto">
                                            <span class="fis-nav-icon">⚡</span>
                                            <span><strong>Reto ABP</strong> • El Cañón: Ecuaciones de movimiento (Ref. PhET)</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l2-pdf" data-fis-lesson="2" data-fis-type="pdf">
                                            <span class="fis-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • Guia_2_Formulas_Cinematicas.pdf</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- LECCIÓN 3 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="fisHeadL3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fisCollapseL3" aria-expanded="false" aria-controls="fisCollapseL3">
                                    <div>
                                        Lección 3: Las Leyes de Newton (Condicionales)
                                        <span class="fis-lesson-meta" id="fisLessonCount-3">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="fisCollapseL3" class="accordion-collapse collapse" aria-labelledby="fisHeadL3">
                                <ul class="fis-nav-list">
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l3-lectura" data-fis-lesson="3" data-fis-type="lectura">
                                            <span class="fis-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Teoría: Inercia, $F=ma$ y Acción/Reacción</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l3-video" data-fis-lesson="3" data-fis-type="video"
                                                data-video-title="Leyes de Newton - EXPLICACIÓN COMPLETA con ejemplos"
                                                data-video-channel="Canal: MateFacil - Física"
                                                data-video-embed="https://www.youtube.com/embed/iLt6C1oxmlc"
                                                data-video-url="https://www.youtube.com/watch?v=iLt6C1oxmlc"
                                                data-video-alt-title="Tercera ley de Newton o Principio de Acción y Reacción"
                                                data-video-alt-channel="Canal: FÍSICA CON JIMMY SALCEDO"
                                                data-video-alt-embed="https://www.youtube.com/embed/CVsaou5_9oM"
                                                data-video-alt-url="https://www.youtube.com/watch?v=CVsaou5_9oM"
                                                data-video-alt-short="3ra Ley Acción y Reacción">
                                            <span class="fis-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • Leyes de Newton explicadas (Pop-up YouTube)</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l3-simulador" data-fis-lesson="3" data-fis-type="simulador">
                                            <span class="fis-nav-icon">⚙</span>
                                            <span><strong>Simulador</strong> • Entorno de fricción ajustable y Diagramas de Cuerpo Libre</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l3-pdf" data-fis-lesson="3" data-fis-type="pdf">
                                            <span class="fis-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • Guia_3_Diagramas_Cuerpo_Libre.pdf</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- LECCIÓN 4 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="fisHeadL4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fisCollapseL4" aria-expanded="false" aria-controls="fisCollapseL4">
                                    <div>
                                        Lección 4: Trabajo y Energía (Ciclos)
                                        <span class="fis-lesson-meta" id="fisLessonCount-4">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="fisCollapseL4" class="accordion-collapse collapse" aria-labelledby="fisHeadL4">
                                <ul class="fis-nav-list">
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l4-lectura" data-fis-lesson="4" data-fis-type="lectura">
                                            <span class="fis-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Teoría: Balance y Conservación de Energía</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l4-video" data-fis-lesson="4" data-fis-type="video"
                                                data-video-title="Energía Cinética, Potencial y Mecánica (El bucle infinito de la Energía)"
                                                data-video-channel="Canal: La Profe Joha"
                                                data-video-embed="https://www.youtube.com/embed/hYuNxhL111E"
                                                data-video-url="https://www.youtube.com/watch?v=hYuNxhL111E"
                                                data-video-alt-title="Energía MECÁNICA | Principio de Conservación de la Energía"
                                                data-video-alt-channel="Canal: Susi Profe"
                                                data-video-alt-embed="https://www.youtube.com/embed/f4IWnif1wjs"
                                                data-video-alt-url="https://www.youtube.com/watch?v=f4IWnif1wjs"
                                                data-video-alt-short="Conservación de Energía (Susi Profe)">
                                            <span class="fis-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • Energía Cinética, Potencial y Mecánica (Pop-up)</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l4-reto" data-fis-lesson="4" data-fis-type="reto">
                                            <span class="fis-nav-icon">⚡</span>
                                            <span><strong>Reto ABP</strong> • Montaña Rusa: Cálculo de Joules</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l4-pdf" data-fis-lesson="4" data-fis-type="pdf">
                                            <span class="fis-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • Guia_4_Balance_Energia.pdf</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- LECCIÓN 5 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="fisHeadL5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fisCollapseL5" aria-expanded="false" aria-controls="fisCollapseL5">
                                    <div>
                                        Lección 5: Colisiones y Cantidad de Movimiento
                                        <span class="fis-lesson-meta" id="fisLessonCount-5">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="fisCollapseL5" class="accordion-collapse collapse" aria-labelledby="fisHeadL5">
                                <ul class="fis-nav-list">
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l5-lectura" data-fis-lesson="5" data-fis-type="lectura">
                                            <span class="fis-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Teoría: Impulso, Choques Elásticos e Inelásticos</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l5-video" data-fis-lesson="5" data-fis-type="video"
                                                data-video-title="COLISIONES - ELÁSTICAS e INELÁSTICAS - Choques | Explicación y Ejercicios"
                                                data-video-channel="Canal: Profe Edi"
                                                data-video-embed="https://www.youtube.com/embed/_zu67RXVuUM"
                                                data-video-url="https://www.youtube.com/watch?v=_zu67RXVuUM"
                                                data-video-alt-title="Tipos de Colisiones o Choques en Física"
                                                data-video-alt-channel="Canal: Es Ciencia"
                                                data-video-alt-embed="https://www.youtube.com/embed/f2ABNZVMIC4"
                                                data-video-alt-url="https://www.youtube.com/watch?v=f2ABNZVMIC4"
                                                data-video-alt-short="Tipos de Colisiones (Es Ciencia)">
                                            <span class="fis-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • Colisiones y Choques en Física (Pop-up)</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l5-simulador" data-fis-lesson="5" data-fis-type="simulador">
                                            <span class="fis-nav-icon">⚙</span>
                                            <span><strong>Simulador</strong> • Mesa de billar virtual interactiva</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="l5-pdf" data-fis-lesson="5" data-fis-type="pdf">
                                            <span class="fis-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • Guia_5_Resolucion_Choques.pdf</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- MÓDULO DE CIERRE Y CERTIFICACIÓN -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="fisHeadL6">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fisCollapseL6" aria-expanded="false" aria-controls="fisCollapseL6">
                                    <div>
                                        Módulo de Cierre y Certificación
                                        <span class="fis-lesson-meta" id="fisLessonCount-6">0/2 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="fisCollapseL6" class="accordion-collapse collapse" aria-labelledby="fisHeadL6">
                                <ul class="fis-nav-list">
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="cierre-encuesta" data-fis-lesson="6" data-fis-type="cierre">
                                            <span class="fis-nav-icon">📊</span>
                                            <span><strong>Encuesta</strong> • Satisfacción e Impacto del Módulo (Escala Likert)</span>
                                            <span class="fis-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="fis-nav-item" data-fis-item="cierre-certificado" data-fis-lesson="6" data-fis-type="cierre">
                                            <span class="fis-nav-icon">🎓</span>
                                            <span><strong>Certificado</strong> • Descargar acreditación de Física</span>
                                            <span class="fis-nav-check">✓</span>
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
            <div class="fis-main-panel" id="fisMainWorkspace">
                <div class="fis-workspace-card">

                    <!-- =====================================================
                         LECCIÓN 1 · ITEM 1: LECTURA (Vectores y Sistemas de Referencia)
                         ===================================================== -->
                    <article class="fis-content-pane is-active" data-fis-pane="l1-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <div>
                                <span class="fis-type-badge fis-badge-reading mb-1">📖 Lección 1 · Lectura Activa</span>
                                <h2 class="h3 fw-bold mb-0">Vectores y Sistemas de Referencia (Estructuras Espaciales)</h2>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-teal fw-semibold" data-fis-note-toggle="l1">
                                📝 Guardar nota
                            </button>
                        </div>

                        <!-- Cajón de Notas Personales -->
                        <div class="fis-note-drawer d-none" id="fisNoteDrawer-l1">
                            <label for="fisNoteInput-l1" class="form-label small fw-bold text-dark mb-1">📝 Tus apuntes de la Lección 1 (Vectores):</label>
                            <textarea id="fisNoteInput-l1" class="form-control form-control-sm mb-2" rows="2" placeholder="Escribe aquí tus notas sobre componentes rectangulares Vx y Vy..."></textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-success fw-semibold" id="fisNoteStatus-l1"></span>
                                <button type="button" class="btn btn-xs btn-teal fw-bold px-3" id="fisNoteSave-l1">Guardar apunte</button>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <strong>Tema:</strong> Magnitudes escalares vs. vectoriales, componentes $x$ e $y$.
                        </p>

                        <h3 class="h5 fw-bold text-teal mb-2">¿Qué es? (Introducción Teórica)</h3>
                        <p class="text-secondary leading-relaxed mb-3">
                            En física, para describir el mundo que nos rodea, utilizamos <strong>"magnitudes"</strong> (todo aquello que se puede medir). Sin embargo, no todas las medidas son iguales. Existen dos tipos fundamentales:
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-4 border bg-light h-100">
                                    <h4 class="h6 fw-bold text-secondary mb-2">📏 Magnitudes Escalares</h4>
                                    <p class="small text-secondary mb-0">
                                        Solo necesitan un <strong>número y una unidad</strong> para quedar perfectamente definidas. No tienen dirección. Ejemplo: El tiempo ($10\text{ segundos}$), la masa ($5\text{ kg}$) o la temperatura ($25\text{ }^\circ\text{C}$).
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-4 border border-success-subtle h-100" style="background: rgba(204,251,241,0.35);">
                                    <h4 class="h6 fw-bold text-teal mb-2">🧭 Magnitudes Vectoriales</h4>
                                    <p class="small text-secondary mb-0">
                                        Un número no es suficiente; necesitan indicar <strong>hacia dónde se dirigen (dirección y sentido)</strong>. Se representan con flechas (vectores). Ejemplo: La velocidad ($100\text{ km/h}$ hacia el norte), la fuerza (empujar una caja hacia la derecha) o el desplazamiento.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <h4 class="h6 fw-bold mb-2">Un vector se compone de tres elementos lógicos:</h4>
                        <ol class="small text-secondary leading-relaxed mb-3">
                            <li class="mb-1"><strong>Módulo (Magnitud):</strong> El tamaño o longitud de la flecha (ej. $50\text{ Newtons}$ de fuerza).</li>
                            <li class="mb-1"><strong>Dirección:</strong> La línea de acción (ej. un ángulo de $45^\circ$).</li>
                            <li class="mb-1"><strong>Sentido:</strong> Hacia dónde apunta la flecha (indicado por la punta).</li>
                        </ol>
                        <p class="small text-secondary leading-relaxed mb-4">
                            Para que un simulador o programa de computadora pueda "entender" un vector, este se debe <strong>descomponer en el plano cartesiano mediante sus componentes rectangulares ($x$ e $y$)</strong>, formando un triángulo rectángulo.
                        </p>

                        <h3 class="h5 fw-bold text-teal mb-3">Fórmulas Principales (Para el Simulador)</h3>
                        <p class="small text-secondary mb-3">Para calcular las componentes de un vector a partir de su magnitud ($V$) y su ángulo ($\theta$):</p>

                        <div class="fis-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Componente horizontal en $x$:</div>
                                <div class="small text-muted">Proyección adyacente sobre el eje horizontal</div>
                            </div>
                            <div class="fis-formula-math">$$V_x = V \cdot \cos(\theta)$$</div>
                        </div>

                        <div class="fis-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Componente vertical en $y$:</div>
                                <div class="small text-muted">Proyección opuesta sobre el eje vertical</div>
                            </div>
                            <div class="fis-formula-math">$$V_y = V \cdot \sin(\theta)$$</div>
                        </div>

                        <div class="fis-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                            <div>
                                <div class="fw-bold text-dark">Magnitud original (Teorema de Pitágoras):</div>
                                <div class="small text-muted">Reconstrucción de la resultante a partir de $V_x$ y $V_y$</div>
                            </div>
                            <div class="fis-formula-math">$$V = \sqrt{V_x^2 + V_y^2}$$</div>
                        </div>

                        <!-- Acordeón Expandible: Tips para el Simulador -->
                        <div class="fis-tips-accordion mb-4">
                            <button class="fis-tips-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#fisTipsL1" aria-expanded="true">
                                <span>✦ Tips para el Simulador del Plano Cartesiano</span>
                                <span>[ ˅ ]</span>
                            </button>
                            <div id="fisTipsL1" class="collapse show">
                                <div class="fis-tips-body">
                                    Recuerda verificar en qué <strong>cuadrante</strong> se encuentra el ángulo $\theta$: en el Cuadrante II ($90^\circ &lt; \theta &lt; 180^\circ$) la componente $V_x$ se vuelve negativa porque apunta hacia la izquierda, mientras que $V_y$ sigue siendo positiva. En el simulador puedes arrastrar directamente la punta del vector con el cursor.
                                </div>
                            </div>
                        </div>

                        <!-- Barra Inferior: Feedback y Acción Directa -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                            <div class="d-flex align-items-center gap-2 fis-feedback-group">
                                <span class="small text-muted">¿Te resultó clara esta lectura?</span>
                                <button type="button" class="fis-feedback-btn" data-fis-feedback="up" aria-label="Me gustó">👍</button>
                                <button type="button" class="fis-feedback-btn" data-fis-feedback="down" aria-label="No me gustó">👎</button>
                                <span class="small text-teal fw-semibold fis-feedback-msg"></span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-teal btn-sm fw-bold" data-fis-goto="l1-video">
                                    ▶ Ver Vídeo Pop-up
                                </button>
                                <button type="button" class="btn btn-teal fw-bold px-4 shadow-teal" data-fis-goto="l1-simulador">
                                    Ir al Simulador: Plano Cartesiano ➔
                                </button>
                            </div>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 1 · ITEM 2: VÍDEO (Pop-up YouTube)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l1-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="fis-type-badge fis-badge-reading">▶ Lección 1 · Recursos Audiovisuales (Pop-up)</span>
                            <span class="text-muted small">Video: "Descomponiendo el mundo en Vectores"</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Recursos Audiovisuales: Vectores Introducción</h2>
                        <p class="text-secondary small mb-4">
                            Para mantener enfocado tu espacio de trabajo, los vídeos se reproducen en una <strong>ventana emergente (Modal con fondo desenfocado)</strong>. Elige cualquiera de los recursos recomendados o ábrelos directamente en YouTube:
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-teal text-white mb-2" style="background:#0d9488;">Video Recomendado Principal</span>
                                        <h3 class="h6 fw-bold mb-1">Vectores Introducción | Qué es un vector y sus características</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>Matemáticas profe Alex</strong></p>
                                        <p class="small text-secondary mb-3">Una explicación visual y muy clara sobre la diferencia espacial de los vectores, su magnitud, dirección y sentido.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-teal btn-sm fw-bold px-3" data-open-fis-video="l1-video" data-video-mode="main">
                                            ▶ Reproducir en Modal
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=IrTeyyzerjI" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-secondary mb-2">Video Alternativo (Corto 2 min)</span>
                                        <h3 class="h6 fw-bold mb-1">Magnitudes Escalares y Magnitudes Vectoriales</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>A Cierta Ciencia</strong></p>
                                        <p class="small text-secondary mb-3">Ideal para un repaso rápido de 2 minutos sobre la diferencia entre escalares y vectores.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-teal btn-sm fw-bold px-3" data-open-fis-video="l1-video" data-video-mode="alt">
                                            ▶ Reproducir Alternativo
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=C7cORnM76yI" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            YouTube (Opción 1) ↗
                                        </a>
                                        <a href="https://youtu.be/MijuxS_SkiU" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            YouTube (Opción 2) ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 border bg-white small mb-4">
                            <strong>🌐 Página Web Recomendada (Para Guías y Ejercicios):</strong>
                            <a href="https://www.fisicalab.com" target="_blank" rel="noopener noreferrer" class="fw-bold text-teal">Fisicalab (www.fisicalab.com)</a> — Excelente página con rigor científico pero muy accesible en su sección de "Vectores" con problemas resueltos paso a paso.
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-fis-goto="l1-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-teal fw-bold px-4" data-fis-goto="l1-simulador">Siguiente: Simulador Plano Cartesiano ⚙</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 1 · ITEM 3: SIMULADOR (Plano Cartesiano Interactivo)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l1-simulador">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="fis-type-badge fis-badge-sim">⚙ Lección 1 · Simulador Interactivo</span>
                            <span class="text-muted small">Descomposición de Vectores en Tiempo Real</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Simulador: Plano Cartesiano Interactivo ($V_x, V_y$)</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Ejercicio:</strong> Modifica la magnitud ($V$) y el ángulo ($\theta$) del vector arrastrando directamente la punta de la flecha en el plano cartesiano o usando los deslizadores, observando cómo cambian automáticamente sus componentes rectangulares en tiempo real.
                        </p>

                        <div class="fis-sim-box mb-4">
                            <div class="fis-sim-topbar">
                                <span class="fw-bold text-emerald small">⚙ Motor Vectorial 2D · Descomposición Polar → Rectangular</span>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-info text-dark font-monospace" id="fisL1MagBadge">|V| = 60 N</span>
                                    <span class="badge bg-warning text-dark font-monospace" id="fisL1AngleBadge">θ = 45°</span>
                                    <span class="badge bg-success text-white font-monospace" id="fisL1QuadBadge">Cuadrante I (+X, +Y)</span>
                                </div>
                            </div>
                            <div class="fis-sim-body">
                                <div class="row g-4 align-items-center mb-3">
                                    <div class="col-12 col-md-6">
                                        <div class="fis-sim-canvas-wrap">
                                            <svg id="fisL1CartesianSvg" viewBox="0 0 320 280" class="w-100" style="max-width:320px; cursor:crosshair; user-select:none;" aria-label="Plano Cartesiano Interactivo">
                                                <!-- Cuadrícula -->
                                                <g stroke="#1e293b" stroke-width="1">
                                                    <line x1="40" y1="20" x2="40" y2="260"/><line x1="80" y1="20" x2="80" y2="260"/>
                                                    <line x1="120" y1="20" x2="120" y2="260"/><line x1="200" y1="20" x2="200" y2="260"/>
                                                    <line x1="240" y1="20" x2="240" y2="260"/><line x1="280" y1="20" x2="280" y2="260"/>
                                                    <line x1="20" y1="60" x2="300" y2="60"/><line x1="20" y1="100" x2="300" y2="100"/>
                                                    <line x1="20" y1="180" x2="300" y2="180"/><line x1="20" y1="220" x2="300" y2="220"/>
                                                </g>
                                                <!-- Ejes X e Y -->
                                                <line x1="20" y1="140" x2="300" y2="140" stroke="#64748b" stroke-width="1.8"/>
                                                <line x1="160" y1="15" x2="160" y2="265" stroke="#64748b" stroke-width="1.8"/>
                                                <text x="292" y="133" font-size="11" fill="#94a3b8" font-weight="bold">+X</text>
                                                <text x="24" y="133" font-size="11" fill="#94a3b8" font-weight="bold">-X</text>
                                                <text x="166" y="25" font-size="11" fill="#94a3b8" font-weight="bold">+Y</text>
                                                <text x="166" y="262" font-size="11" fill="#94a3b8" font-weight="bold">-Y</text>

                                                <!-- Arco del ángulo θ -->
                                                <path id="fisL1ArcPath" d="M 186 140 A 26 26 0 0 0 178.4 121.6" fill="none" stroke="#f59e0b" stroke-width="2"/>

                                                <!-- Componente Vx (Azul) -->
                                                <line id="fisL1CompXLine" x1="160" y1="140" x2="208.8" y2="140" stroke="#38bdf8" stroke-width="3" stroke-dasharray="5,3"/>
                                                <!-- Componente Vy (Rosa/Esmeralda) -->
                                                <line id="fisL1CompYLine" x1="208.8" y1="140" x2="208.8" y2="91.2" stroke="#f472b6" stroke-width="3" stroke-dasharray="5,3"/>

                                                <!-- Vector Resultante V -->
                                                <line id="fisL1VecLine" x1="160" y1="140" x2="208.8" y2="91.2" stroke="#10b981" stroke-width="3.5"/>
                                                <circle id="fisL1VecHead" cx="208.8" cy="91.2" r="7" fill="#10b981" stroke="#ffffff" stroke-width="2"/>
                                            </svg>
                                            <div class="text-xs text-slate-400 mt-1">💡 Tip: Arrastra el círculo verde con el mouse o usa los controles</div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="fisL1MagSlider" class="form-label small text-slate-300 mb-1">1. Módulo / Magnitud ($V$ en Newtons):</label>
                                            <input type="range" class="form-range" id="fisL1MagSlider" min="10" max="100" step="1" value="60">
                                        </div>
                                        <div class="mb-3">
                                            <label for="fisL1AngleSlider" class="form-label small text-slate-300 mb-1">2. Dirección / Ángulo ($\theta$ de $0^\circ$ a $360^\circ$):</label>
                                            <input type="range" class="form-range" id="fisL1AngleSlider" min="0" max="360" step="1" value="45">
                                        </div>

                                        <div class="row g-2 text-center mb-3">
                                            <div class="col-4">
                                                <div class="p-2 rounded-3 border border-info bg-dark">
                                                    <div class="text-xs text-info">Comp. $V_x$</div>
                                                    <div class="fw-bold font-monospace small text-white" id="fisL1VxDisplay">+42.43 N</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="p-2 rounded-3 border border-danger bg-dark">
                                                    <div class="text-xs text-pink" style="color:#f472b6;">Comp. $V_y$</div>
                                                    <div class="fw-bold font-monospace small text-white" id="fisL1VyDisplay">+42.43 N</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="p-2 rounded-3 border border-success bg-dark">
                                                    <div class="text-xs text-emerald">Pitágoras $V$</div>
                                                    <div class="fw-bold font-monospace small text-white" id="fisL1PitDisplay">60.00 N</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-light" id="fisL1ResetBtn">Reiniciar</button>
                                            <button type="button" class="btn btn-sm btn-teal fw-bold flex-grow-1" id="fisL1StepBtn">Probar siguiente cuadrante ▷</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="fis-sim-console" id="fisL1Console"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-fis-goto="l1-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-teal fw-bold px-4" data-fis-goto="l1-pdf">Siguiente: Guía Visual 01 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 1 · ITEM 4: GUÍA PDF (Guia_1_Vectores_y_Trigonometria.pdf)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l1-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="fis-type-badge fis-badge-pdf">📄 Lección 1 · Guía Visual Incorporada</span>
                            <div class="d-flex align-items-center gap-2">
                                <code class="small">Guia_1_Vectores_y_Trigonometria.pdf</code>
                                <button type="button" class="btn btn-teal btn-sm fw-bold" data-print-guide="fisGuideContent-1" data-guide-title="Guía 01 - Vectores y Estructuras Espaciales">
                                    🖨️ Imprimir / Guardar PDF
                                </button>
                            </div>
                        </div>

                        <!-- Código HTML/CSS de la Guía 01 incorporado directamente -->
                        <div class="fis-guide-container mb-4" id="fisGuideContent-1">
                            <header class="border-bottom border-4 border-primary pb-3 mb-4">
                                <p class="text-xs fw-bold text-primary text-uppercase tracking-wider mb-1">Módulo: Física (Causa y Efecto) | Nivel: Inicial</p>
                                <h2 class="h3 fw-extrabold text-dark mb-1">GUÍA 01: Vectores y Estructuras Espaciales</h2>
                                <p class="text-secondary small mb-0">Traduciendo magnitudes físicas a coordenadas lógicas (Ejes X e Y).</p>
                            </header>

                            <!-- Sección 1 -->
                            <section class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="fis-guide-step-num bg-primary-subtle text-primary me-3">1</div>
                                    <h3 class="h5 fw-bold mb-0">Anatomía de un Vector (El "Objeto" Físico)</h3>
                                </div>
                                <div class="p-4 rounded-3 bg-light border">
                                    <p class="small mb-3">A diferencia de los escalares (que solo necesitan un número), un vector funciona en programación como un <strong>Objeto</strong> que contiene múltiples atributos que debes definir:</p>
                                    <div class="row g-3">
                                        <div class="col-12 col-md-4">
                                            <div class="bg-white p-3 rounded shadow-xs border-top border-4 border-primary h-100">
                                                <h4 class="h6 fw-bold text-primary mb-1">Magnitud ($V$)</h4>
                                                <p class="small text-secondary mb-0">El tamaño, longitud o fuerza. (Ej. $50\text{ km/h}$, $100\text{ Newtons}$).</p>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="bg-white p-3 rounded shadow-xs border-top border-4 border-danger h-100">
                                                <h4 class="h6 fw-bold text-danger mb-1">Dirección ($\theta$)</h4>
                                                <p class="small text-secondary mb-0">El ángulo de inclinación respecto al eje $x$ positivo.</p>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="bg-white p-3 rounded shadow-xs border-top border-4 border-success h-100">
                                                <h4 class="h6 fw-bold text-teal mb-1">Sentido</h4>
                                                <p class="small text-secondary mb-0">Hacia dónde apunta la punta de la flecha en el plano.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- Sección 2 -->
                            <section class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="fis-guide-step-num bg-primary-subtle text-primary me-3">2</div>
                                    <h3 class="h5 fw-bold mb-0">Descomposición Vectorial (Trigonometría)</h3>
                                </div>
                                <p class="small text-secondary mb-3">Para que el simulador pueda calcular movimientos diagonales, necesita transformar la flecha en coordenadas separadas para los ejes X e Y, creando un triángulo rectángulo.</p>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="border-logic-if ps-3 bg-primary-subtle bg-opacity-25 p-3 rounded-end h-100">
                                            <h4 class="fw-bold text-primary mb-2 code-font small text-uppercase">Algoritmo: De Polar a Rectangular</h4>
                                            <p class="small text-secondary mb-2">Si conoces la Magnitud ($V$) y el Ángulo ($\theta$):</p>
                                            <div class="math-box mb-2">$$V_x = V \cdot \cos(\theta)$$</div>
                                            <div class="math-box">$$V_y = V \cdot \sin(\theta)$$</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="border-logic-success ps-3 bg-success-subtle bg-opacity-25 p-3 rounded-end h-100">
                                            <h4 class="fw-bold text-success mb-2 code-font small text-uppercase">Algoritmo Inverso: Resultante</h4>
                                            <p class="small text-secondary mb-2">Si conoces las componentes ($V_x$, $V_y$) y buscas la flecha original:</p>
                                            <div class="math-box mb-2">$$V = \sqrt{V_x^2 + V_y^2}$$</div>
                                            <div class="math-box">$$\theta = \tan^{-1}\left(\frac{V_y}{V_x}\right)$$</div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- Sección 3 -->
                            <section>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="fis-guide-step-num bg-primary-subtle text-primary me-3">3</div>
                                    <h3 class="h5 fw-bold mb-0">Condiciones Lógicas (Cuadrantes y Signos)</h3>
                                </div>
                                <div class="table-responsive rounded-3 overflow-hidden shadow-sm">
                                    <table class="table table-dark table-hover align-middle small mb-0">
                                        <thead>
                                            <tr class="text-uppercase text-secondary">
                                                <th class="p-3">Cuadrante</th>
                                                <th class="p-3">Rango del Ángulo</th>
                                                <th class="p-3 text-center">Eje X ($V_x$)</th>
                                                <th class="p-3 text-center">Eje Y ($V_y$)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="p-3 fw-bold text-info">Cuadrante I</td>
                                                <td class="p-3">$0^\circ &lt; \theta &lt; 90^\circ$</td>
                                                <td class="p-3 text-center"><span class="badge bg-success">+ Positivo</span></td>
                                                <td class="p-3 text-center"><span class="badge bg-success">+ Positivo</span></td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 fw-bold text-danger">Cuadrante II</td>
                                                <td class="p-3">$90^\circ &lt; \theta &lt; 180^\circ$</td>
                                                <td class="p-3 text-center"><span class="badge bg-danger">- Negativo</span></td>
                                                <td class="p-3 text-center"><span class="badge bg-success">+ Positivo</span></td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 fw-bold text-warning">Cuadrante III</td>
                                                <td class="p-3">$180^\circ &lt; \theta &lt; 270^\circ$</td>
                                                <td class="p-3 text-center"><span class="badge bg-danger">- Negativo</span></td>
                                                <td class="p-3 text-center"><span class="badge bg-danger">- Negativo</span></td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 fw-bold text-primary">Cuadrante IV</td>
                                                <td class="p-3">$270^\circ &lt; \theta &lt; 360^\circ$</td>
                                                <td class="p-3 text-center"><span class="badge bg-success">+ Positivo</span></td>
                                                <td class="p-3 text-center"><span class="badge bg-danger">- Negativo</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-fis-goto="l1-simulador">← Anterior: Simulador</button>
                            <button type="button" class="btn btn-teal fw-bold px-4" data-fis-goto="l2-lectura">Ir a Lección 2: Cinemática 1D y 2D →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 2 · ITEM 1: LECTURA (Cinemática en 1 y 2 Dimensiones)
                         (Replica exactamente el Bosquejo del Panel Principal)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l2-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <div>
                                <span class="fis-type-badge fis-badge-reading mb-1">📖 Lección 2 · Lectura Activa</span>
                                <h2 class="h3 fw-bold mb-0">Cinemática en 1 y 2 Dimensiones (Modelando el Movimiento)</h2>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-teal fw-semibold" data-fis-note-toggle="l2">
                                📝 Guardar nota
                            </button>
                        </div>

                        <div class="fis-note-drawer d-none" id="fisNoteDrawer-l2">
                            <label for="fisNoteInput-l2" class="form-label small fw-bold text-dark mb-1">📝 Tus apuntes de la Lección 2 (Cinemática):</label>
                            <textarea id="fisNoteInput-l2" class="form-control form-control-sm mb-2" rows="2" placeholder="Anota tus despejes o el ángulo óptimo para el Reto ABP El Cañón..."></textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-success fw-semibold" id="fisNoteStatus-l2"></span>
                                <button type="button" class="btn btn-xs btn-teal fw-bold px-3" id="fisNoteSave-l2">Guardar apunte</button>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <strong>Tema:</strong> Velocidad, aceleración y tiro parabólico.
                        </p>

                        <h3 class="h5 fw-bold text-teal mb-2">¿Qué es? (Introducción Teórica)</h3>
                        <p class="text-secondary leading-relaxed mb-3">
                            La <strong>cinemática</strong> es la rama de la física que estudia el movimiento de los objetos sin importar las causas (fuerzas) que lo producen. Es, en esencia, <strong>modelar matemáticamente cómo cambia la posición de un objeto a medida que avanza el tiempo</strong> (el motor de todo simulador físico).
                        </p>
                        <p class="small text-secondary mb-3">Existen diferentes tipos de movimiento que se programan mediante <strong>"condicionales"</strong>:</p>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-4 border bg-light h-100">
                                    <span class="badge bg-primary mb-2 code-font">if (a == 0)</span>
                                    <h4 class="h6 fw-bold mb-1">Movimiento Rectilíneo Uniforme (MRU)</h4>
                                    <p class="small text-secondary mb-0">
                                        El objeto se mueve en línea recta a una velocidad constante (la aceleración es cero). Si el objeto avanza en el eje $x$, siempre avanzará la misma distancia cada segundo.
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-4 border bg-light h-100">
                                    <span class="badge bg-danger mb-2 code-font">else // (a != 0)</span>
                                    <h4 class="h6 fw-bold mb-1">Movimiento Rectilíneo Uniformemente Acelerado (MRUA)</h4>
                                    <p class="small text-secondary mb-0">
                                        La velocidad cambia (aumenta o disminuye) de manera constante porque existe una aceleración (ej. pisar el acelerador de un auto). La caída libre es un MRUA donde la aceleración siempre es la gravedad ($g = 9.81 \, m/s^2$).
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-4 border border-success-subtle h-100" style="background: rgba(204,251,241,0.35);">
                                    <span class="badge bg-teal text-white mb-2 code-font" style="background:#0d9488;">Simulador 2D</span>
                                    <h4 class="h6 fw-bold mb-1">Tiro Parabólico (Movimiento en 2D)</h4>
                                    <p class="small text-secondary mb-0">
                                        Es la combinación perfecta de los dos anteriores. Cuando disparas un proyectil, este tiene un <strong>MRU en el eje horizontal ($x$)</strong> porque nada lo frena, y al mismo tiempo tiene un <strong>MRUA en el eje vertical ($y$)</strong> porque la gravedad lo jala hacia abajo.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <h3 class="h5 fw-bold text-teal mb-3">Fórmulas Principales (El "Código" del Movimiento)</h3>
                        <p class="small text-secondary mb-3">Para el Reto ABP <strong>"El Cañón"</strong>, el simulador utilizará estas fórmulas lógicas:</p>

                        <div class="fis-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Velocidad constante (MRU):</div>
                                <div class="small text-muted">Desplazamiento uniforme por unidad de tiempo</div>
                            </div>
                            <div class="fis-formula-math">$$v = \frac{d}{t}$$</div>
                        </div>

                        <div class="fis-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Posición con aceleración (MRUA):</div>
                                <div class="small text-muted">Cálculo de posición cuando existe aceleración constante $a$</div>
                            </div>
                            <div class="fis-formula-math">$$d = v_i \cdot t + \frac{1}{2} a \cdot t^2$$</div>
                        </div>

                        <div class="fis-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                            <div>
                                <div class="fw-bold text-dark">Alcance máximo horizontal en Tiro Parabólico:</div>
                                <div class="small text-muted">Fórmula clave para calcular cómo darle al objetivo en el simulador</div>
                            </div>
                            <div class="fis-formula-math">$$x_{max} = \frac{v_i^2 \cdot \sin(2\theta)}{g}$$</div>
                        </div>

                        <!-- Acordeón Expandible del Bosquejo: ✦ Tips para el Reto ABP [ ˅ ] -->
                        <div class="fis-tips-accordion mb-4">
                            <button class="fis-tips-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#fisTipsL2" aria-expanded="true">
                                <span>✦ Tips para el Reto ABP</span>
                                <span>[ ˅ ]</span>
                            </button>
                            <div id="fisTipsL2" class="collapse show">
                                <div class="fis-tips-body">
                                    Recuerda que en el simulador de <strong>"El Cañón"</strong>, la gravedad $g$ siempre será una constante de aceleración hacia abajo ($9.81 \, m/s^2$). Utiliza la fórmula de alcance máximo para calcular el ángulo $\theta$ exacto antes de disparar.
                                </div>
                            </div>
                        </div>

                        <!-- Barra Inferior del Bosquejo: 👍 👎 ------ [ Ir al Reto ABP: El Cañón ➔ ] -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                            <div class="d-flex align-items-center gap-2 fis-feedback-group">
                                <span class="small text-muted">¿Útil?</span>
                                <button type="button" class="fis-feedback-btn" data-fis-feedback="up" aria-label="Me gustó">👍</button>
                                <button type="button" class="fis-feedback-btn" data-fis-feedback="down" aria-label="No me gustó">👎</button>
                                <span class="small text-teal fw-semibold fis-feedback-msg"></span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-teal btn-sm fw-bold" data-fis-goto="l2-video">
                                    ▶ Vídeo Cinemática
                                </button>
                                <button type="button" class="btn btn-teal fw-bold px-4 shadow-teal" data-fis-goto="l2-reto">
                                    Ir al Reto ABP: El Cañón ➔
                                </button>
                            </div>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 2 · ITEM 2: VÍDEO (Pop-up YouTube)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l2-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="fis-type-badge fis-badge-reading">▶ Lección 2 · Recursos Audiovisuales (Pop-up)</span>
                            <span class="text-muted small">Video: "Las matemáticas detrás del movimiento"</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Cinemática y Clasificación del Movimiento</h2>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-teal text-white mb-2" style="background:#0d9488;">Video Recomendado</span>
                                        <h3 class="h6 fw-bold mb-1">Introducción a la cinemática y clasificación del movimiento</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>PROFE JAPIÑON ALVAREZ</strong></p>
                                        <p class="small text-secondary mb-3">Fundamentos del movimiento rectilíneo uniforme y uniformemente acelerado.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-teal btn-sm fw-bold px-3" data-open-fis-video="l2-video" data-video-mode="main">
                                            ▶ Reproducir en Modal
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=p18S7349xPU" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                        <a href="https://youtube.com/playlist?list=PL67radScKEvhRI0Xx7v6j5IzjfAAKe4nm&si=Ws_ZlDueAQCbX0OQ" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Playlist ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-secondary mb-2">Video Alternativo (Aplicado 2D)</span>
                                        <h3 class="h6 fw-bold mb-1">TIRO PARABÓLICO (CINEMÁTICA)</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>Ciencia Vlog</strong></p>
                                        <p class="small text-secondary mb-3">Conecta visualmente los ejes $x$ e $y$ en el lanzamiento de proyectiles.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-teal btn-sm fw-bold px-3" data-open-fis-video="l2-video" data-video-mode="alt">
                                            ▶ Reproducir Alternativo
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=3Y1ZZNLylJc" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                        <a href="https://youtube.com/playlist?list=PLlbNDPDmKN2WvSMqCdNNo0MLyGcV5eNVv&si=CkgC1KHR_G192vMN" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Playlist ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 border bg-white small mb-4">
                            <strong>🌐 Página Web Recomendada:</strong>
                            <a href="https://phet.colorado.edu" target="_blank" rel="noopener noreferrer" class="fw-bold text-teal">PhET Interactive Simulations (phet.colorado.edu)</a> — Universidad de Colorado Boulder: Simulador de "Movimiento de un Proyectil".
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-fis-goto="l2-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-teal fw-bold px-4" data-fis-goto="l2-reto">Siguiente: Reto ABP El Cañón ⚡</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 2 · ITEM 3: RETO ABP (El Cañón · Ref. PhET)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l2-reto">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="fis-type-badge fis-badge-abp">⚡ Lección 2 · Reto ABP (Ref. PhET)</span>
                            <span class="text-muted small">Simulador 2D de Tiro Parabólico</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Reto ABP: "El Cañón" — Ecuaciones de Movimiento</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Misión:</strong> Ingresa las variables iniciales de velocidad ($v_i$) y ángulo ($\theta$) para que el proyectil impacte exactamente en el objetivo aplicando $x_{max} = \frac{v_i^2 \cdot \sin(2\theta)}{g}$ con $g = 9.81 \, m/s^2$.
                        </p>

                        <div class="fis-sim-box mb-4">
                            <div class="fis-sim-topbar">
                                <span class="fw-bold text-emerald small">⚡ Simulador Balístico 2D · MRU (Eje X) + MRUA (Eje Y)</span>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-info text-dark font-monospace" id="fisL2XmaxBadge">x_max = 89.2 m</span>
                                    <span class="badge bg-warning text-dark font-monospace" id="fisL2YmaxBadge">y_max = 17.4 m</span>
                                    <span class="badge bg-secondary text-white font-monospace" id="fisL2TimeBadge">t = 3.77 s</span>
                                </div>
                            </div>
                            <div class="fis-sim-body">
                                <div class="row g-4 align-items-center mb-3">
                                    <div class="col-12 col-md-7">
                                        <div class="fis-sim-canvas-wrap">
                                            <svg viewBox="0 0 330 220" class="w-100" aria-label="Simulador de Tiro Parabólico El Cañón">
                                                <!-- Suelo -->
                                                <line x1="15" y1="195" x2="315" y2="195" stroke="#10b981" stroke-width="2.5"/>
                                                <!-- Escala de distancia -->
                                                <text x="35" y="212" font-size="9" fill="#94a3b8" text-anchor="middle">0m</text>
                                                <text x="142" y="212" font-size="9" fill="#94a3b8" text-anchor="middle">50m</text>
                                                <text x="250" y="212" font-size="9" fill="#94a3b8" text-anchor="middle">100m</text>

                                                <!-- Cañón en (35, 195) -->
                                                <circle cx="35" cy="192" r="9" fill="#0d9488" stroke="#ffffff" stroke-width="1.5"/>
                                                <rect x="32" y="180" width="18" height="7" rx="3" transform="rotate(-35 35 190)" fill="#2dd4bf"/>

                                                <!-- Diana Objetivo -->
                                                <g id="fisL2TargetGroup" transform="translate(224.8, 195)">
                                                    <ellipse cx="0" cy="0" rx="11" ry="4" fill="#f43f5e"/>
                                                    <ellipse cx="0" cy="0" rx="6" ry="2" fill="#ffffff"/>
                                                    <text x="0" y="-10" font-size="9" fill="#fda4af" text-anchor="middle" font-weight="bold">🎯 Objetivo</text>
                                                </g>

                                                <!-- Trayectoria Parabólica -->
                                                <path id="fisL2TrajPath" d="M 35 195 Q 130 90 224 195" fill="none" stroke="#38bdf8" stroke-width="2.5" stroke-dasharray="4,3"/>
                                                <!-- Punto de Impacto -->
                                                <circle id="fisL2ImpactCircle" cx="224" cy="195" r="5.5" fill="#10b981" stroke="#ffffff" stroke-width="1.5"/>
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-5">
                                        <div class="mb-2">
                                            <label for="fisL2TargetSelect" class="form-label small text-slate-300 mb-1">Distancia del Objetivo ($x_{\text{objetivo}}$):</label>
                                            <select id="fisL2TargetSelect" class="form-select form-select-sm bg-dark text-white border-secondary">
                                                <option value="88.3" selected>Misión 1: Objetivo a 88.3 m</option>
                                                <option value="65.0">Misión 2: Objetivo a 65.0 m</option>
                                                <option value="104.4">Misión 3: Objetivo a 104.4 m</option>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between small text-slate-300">
                                                <label for="fisL2VelSlider">Velocidad Inicial ($v_i$):</label>
                                                <span class="fw-bold text-teal font-monospace" id="fisL2VelLabel">30 m/s</span>
                                            </div>
                                            <input type="range" class="form-range" id="fisL2VelSlider" min="15" max="35" step="1" value="30">
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between small text-slate-300">
                                                <label for="fisL2AngleSlider">Ángulo de Disparo ($\theta$):</label>
                                                <span class="fw-bold text-warning font-monospace" id="fisL2AngleLabel">38°</span>
                                            </div>
                                            <input type="range" class="form-range" id="fisL2AngleSlider" min="15" max="75" step="1" value="38">
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-light" id="fisL2SolveBtn">Calcular ángulo exacto</button>
                                            <button type="button" class="btn btn-sm btn-teal fw-bold flex-grow-1" id="fisL2FireBtn">🔥 Disparar Cañón</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="fis-sim-console" id="fisL2Console"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-fis-goto="l2-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-teal fw-bold px-4" data-fis-goto="l2-pdf">Siguiente: Guía Visual 02 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 2 · ITEM 4: GUÍA PDF (Guia_2_Formulas_Cinematicas.pdf)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l2-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="fis-type-badge fis-badge-pdf">📄 Lección 2 · Guía Visual Incorporada</span>
                            <div class="d-flex align-items-center gap-2">
                                <code class="small">Guia_2_Formulas_Cinematicas.pdf</code>
                                <button type="button" class="btn btn-teal btn-sm fw-bold" data-print-guide="fisGuideContent-2" data-guide-title="Guía 02 - Condicionales del Movimiento">
                                    🖨️ Imprimir / Guardar PDF
                                </button>
                            </div>
                        </div>

                        <!-- Código HTML/CSS de la Guía 02 incorporado directamente -->
                        <div class="fis-guide-container mb-4" id="fisGuideContent-2">
                            <header class="border-bottom border-4 pb-3 mb-4" style="border-color:#a855f7 !important;">
                                <p class="text-xs fw-bold text-uppercase tracking-wider mb-1" style="color:#9333ea;">Módulo: Física (Causa y Efecto) | Nivel: Inicial</p>
                                <h2 class="h3 fw-extrabold text-dark mb-1">GUÍA 02: Condicionales del Movimiento</h2>
                                <p class="text-secondary small mb-0">Tabla de decisión visual para fórmulas de cinemática (MRU, MRUA, Tiro Parabólico).</p>
                            </header>

                            <!-- Árbol de Decisión -->
                            <section class="mb-4">
                                <div class="bg-light p-3 rounded-3 border text-center mb-4">
                                    <h3 class="h6 fw-bold text-secondary mb-1">Pregunta Lógica Inicial:</h3>
                                    <p class="h5 fw-bold text-dark mb-0">¿El objeto tiene aceleración ($a$)?</p>
                                </div>

                                <div class="row g-4">
                                    <!-- Ruta IF (NO) -->
                                    <div class="col-12 col-md-6">
                                        <div class="border border-2 border-primary-subtle rounded-4 overflow-hidden shadow-xs h-100">
                                            <div class="bg-primary text-white p-3 text-center">
                                                <span class="code-font small bg-dark bg-opacity-25 px-2 py-1 rounded">if (a == 0)</span>
                                                <h4 class="h6 fw-bold mt-2 mb-0">Velocidad Constante (MRU)</h4>
                                            </div>
                                            <div class="p-4">
                                                <p class="small text-secondary text-center mb-3">El objeto NO acelera ni frena. Avanza en línea recta de forma constante.</p>
                                                <div class="math-box mb-2" style="background:#1e1b4b;">$$v = \frac{d}{t}$$</div>
                                                <p class="text-xs text-center text-muted mb-0">Única fórmula requerida. (Despeja la variable que necesites).</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Ruta ELSE (SÍ) -->
                                    <div class="col-12 col-md-6">
                                        <div class="border border-2 border-danger-subtle rounded-4 overflow-hidden shadow-xs h-100">
                                            <div class="bg-danger text-white p-3 text-center">
                                                <span class="code-font small bg-dark bg-opacity-25 px-2 py-1 rounded">else // (a != 0)</span>
                                                <h4 class="h6 fw-bold mt-2 mb-0">Velocidad Cambiante (MRUA)</h4>
                                            </div>
                                            <div class="p-4">
                                                <p class="small text-secondary text-center mb-3">El objeto acelera, frena o está en caída libre ($g = 9.81 \, m/s^2$).</p>
                                                <div class="d-flex flex-column gap-2">
                                                    <div class="d-flex align-items-center justify-content-between bg-danger-subtle bg-opacity-25 p-2 rounded border">
                                                        <span class="text-xs fw-bold text-danger">Sin Distancia ($d$):</span>
                                                        <span class="math-box py-1 px-3 small">$$v_f = v_i + a \cdot t$$</span>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between bg-danger-subtle bg-opacity-25 p-2 rounded border">
                                                        <span class="text-xs fw-bold text-danger">Sin Vel. Final ($v_f$):</span>
                                                        <span class="math-box py-1 px-3 small">$$d = v_i t + \frac{1}{2}at^2$$</span>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between bg-danger-subtle bg-opacity-25 p-2 rounded border">
                                                        <span class="text-xs fw-bold text-danger">Sin Tiempo ($t$):</span>
                                                        <span class="math-box py-1 px-3 small">$$v_f^2 = v_i^2 + 2ad$$</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- Tiro Parabólico Alert -->
                            <section>
                                <div class="rounded-4 p-4 text-white row g-3 align-items-center m-0" style="background: linear-gradient(90deg, #1e3a8a 0%, #312e81 100%);">
                                    <div class="col-12 col-md-6 border-end border-light border-opacity-25">
                                        <h4 class="h6 fw-bold text-info mb-2">⚡ Tiro Parabólico (Simulador 2D)</h4>
                                        <p class="small text-light opacity-90 mb-0">
                                            Un proyectil (como en el simulador "El Cañón") ejecuta dos ciclos simultáneos: El <strong>Eje X</strong> se programa con la regla del MRU (sin aceleración), y el <strong>Eje Y</strong> con la regla del MRUA (afectado por la gravedad).
                                        </p>
                                    </div>
                                    <div class="col-12 col-md-6 text-center">
                                        <p class="text-xs text-info text-uppercase fw-bold mb-2">Fórmula de Alcance Máximo ($X_{max}$)</p>
                                        <div class="math-box bg-black bg-opacity-50 border border-light border-opacity-10">
                                            $$x_{max} = \frac{v_i^2 \cdot \sin(2\theta)}{g}$$
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-fis-goto="l2-reto">← Anterior: Reto ABP</button>
                            <button type="button" class="btn btn-teal fw-bold px-4" data-fis-goto="l3-lectura">Ir a Lección 3: Las Leyes de Newton →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 3 · ITEM 1: LECTURA (Las Leyes de Newton)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l3-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <div>
                                <span class="fis-type-badge fis-badge-reading mb-1">📖 Lección 3 · Lectura Activa</span>
                                <h2 class="h3 fw-bold mb-0">Las Leyes de Newton (Condicionales Físicas)</h2>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-teal fw-semibold" data-fis-note-toggle="l3">
                                📝 Guardar nota
                            </button>
                        </div>

                        <div class="fis-note-drawer d-none" id="fisNoteDrawer-l3">
                            <label for="fisNoteInput-l3" class="form-label small fw-bold text-dark mb-1">📝 Tus apuntes de la Lección 3 (Leyes de Newton):</label>
                            <textarea id="fisNoteInput-l3" class="form-control form-control-sm mb-2" rows="2" placeholder="Anota cómo construir un Diagrama de Cuerpo Libre (DCL)..."></textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-success fw-semibold" id="fisNoteStatus-l3"></span>
                                <button type="button" class="btn btn-xs btn-teal fw-bold px-3" id="fisNoteSave-l3">Guardar apunte</button>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <strong>Tema:</strong> Inercia, $F = m \cdot a$, Acción y Reacción.
                        </p>

                        <h3 class="h5 fw-bold text-teal mb-2">¿Qué es? (Introducción Teórica)</h3>
                        <p class="text-secondary leading-relaxed mb-3">
                            Si la cinemática (Lección 2) describe <em>cómo</em> se mueven las cosas, la dinámica explica <strong><em>por qué</em> se mueven</strong>. Isaac Newton formuló tres reglas lógicas (Leyes) que actúan como el código fuente del universo físico. En un simulador, estas leyes funcionan exactamente como sentencias <code>if/else</code>:
                        </p>

                        <div class="d-flex flex-column gap-3 mb-4">
                            <div class="p-3 rounded-4 border bg-light">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-1">
                                    <h4 class="h6 fw-bold text-teal mb-0">1. Primera Ley (Inercia - La condición de reposo)</h4>
                                    <code class="small bg-dark text-info px-2 py-1 rounded">if (fuerza_neta == 0) { estado = sin_cambio }</code>
                                </div>
                                <p class="small text-secondary mb-0">
                                    Un objeto se quedará quieto (o moviéndose a velocidad constante) a menos que una fuerza externa lo obligue a cambiar.
                                </p>
                            </div>

                            <div class="p-3 rounded-4 border bg-light">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-1">
                                    <h4 class="h6 fw-bold text-teal mb-0">2. Segunda Ley (Dinámica - La fórmula del motor)</h4>
                                    <code class="small bg-dark text-emerald px-2 py-1 rounded">F = m · a</code>
                                </div>
                                <p class="small text-secondary mb-0">
                                    Nos dice exactamente cuánta fuerza se necesita para mover un objeto. Si aplicas una fuerza ($F$) a una masa ($m$), esta acelerará ($a$). Fórmula central: $F = m \cdot a$.
                                </p>
                            </div>

                            <div class="p-3 rounded-4 border bg-light">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-1">
                                    <h4 class="h6 fw-bold text-teal mb-0">3. Tercera Ley (Acción y Reacción - El ciclo de respuesta)</h4>
                                    <code class="small bg-dark text-warning px-2 py-1 rounded">F_accion = -F_reaccion</code>
                                </div>
                                <p class="small text-secondary mb-0">
                                    Toda acción genera una reacción de igual magnitud, pero en sentido contrario. Ejemplo: Si el código de tu simulador hace que un personaje empuje una pared con $50\text{ Newtons}$, la pared debe "devolver" $50\text{ Newtons}$ al personaje para que este no la atraviese.
                                </p>
                            </div>
                        </div>

                        <p class="small text-secondary leading-relaxed mb-4">
                            Para resolver problemas con estas leyes, los físicos utilizan un <strong>"Diagrama de Cuerpo Libre" (DCL)</strong>, que no es más que un plano cartesiano donde se dibujan todas las flechas (vectores) de las fuerzas que actúan sobre un objeto para sumarlas o restarlas lógicamente.
                        </p>

                        <h3 class="h5 fw-bold text-teal mb-3">Fórmulas Principales (Para el Simulador)</h3>

                        <div class="fis-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Fuerza Neta (Segunda Ley de Newton):</div>
                                <div class="small text-muted">Suma vectorial de todas las fuerzas que actúan sobre la masa $m$</div>
                            </div>
                            <div class="fis-formula-math">$$\sum F = m \cdot a$$</div>
                        </div>

                        <div class="fis-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                            <div>
                                <div class="fw-bold text-dark">Fuerza de Fricción (Roce):</div>
                                <div class="small text-muted">Donde $\mu$ es el coeficiente de fricción del suelo y $N$ es la fuerza Normal</div>
                            </div>
                            <div class="fis-formula-math">$$F_r = \mu \cdot N$$</div>
                        </div>

                        <div class="fis-tips-accordion mb-4">
                            <button class="fis-tips-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#fisTipsL3" aria-expanded="true">
                                <span>✦ Tips para el Simulador de Fricción y DCL</span>
                                <span>[ ˅ ]</span>
                            </button>
                            <div id="fisTipsL3" class="collapse show">
                                <div class="fis-tips-body">
                                    En una superficie horizontal plana, la Fuerza Normal ($N$) equilibra exactamente al Peso ($W = m \cdot g$). Para que el objeto cambie su estado a <strong>"en movimiento"</strong>, la fuerza de empuje debe superar la fricción estática máxima $F_r = \mu \cdot m \cdot g$.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                            <div class="d-flex align-items-center gap-2 fis-feedback-group">
                                <span class="small text-muted">¿Útil?</span>
                                <button type="button" class="fis-feedback-btn" data-fis-feedback="up">👍</button>
                                <button type="button" class="fis-feedback-btn" data-fis-feedback="down">👎</button>
                                <span class="small text-teal fw-semibold fis-feedback-msg"></span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-teal btn-sm fw-bold" data-fis-goto="l3-video">
                                    ▶ Vídeo Leyes de Newton
                                </button>
                                <button type="button" class="btn btn-teal fw-bold px-4 shadow-teal" data-fis-goto="l3-simulador">
                                    Ir al Simulador: Fricción y DCL ➔
                                </button>
                            </div>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 3 · ITEM 2: VÍDEO (Pop-up YouTube)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l3-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="fis-type-badge fis-badge-reading">▶ Lección 3 · Recursos Audiovisuales (Pop-up)</span>
                            <span class="text-muted small">Video: "Causa y efecto: Las Leyes de Newton"</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Leyes de Newton Explicadas con Ejemplos</h2>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-teal text-white mb-2" style="background:#0d9488;">Video Recomendado</span>
                                        <h3 class="h6 fw-bold mb-1">Leyes de Newton - EXPLICACIÓN COMPLETA con ejemplos</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>MateFacil - Física</strong></p>
                                        <p class="small text-secondary mb-3">Un desglose exhaustivo y claro sobre cómo funcionan las fuerzas, la inercia y $F = m \cdot a$.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-teal btn-sm fw-bold px-3" data-open-fis-video="l3-video" data-video-mode="main">
                                            ▶ Reproducir en Modal
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=iLt6C1oxmlc" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-secondary mb-2">Video Alternativo</span>
                                        <h3 class="h6 fw-bold mb-1">Tercera ley de Newton o Principio de Acción y Reacción</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>FÍSICA CON JIMMY SALCEDO</strong></p>
                                        <p class="small text-secondary mb-3">Explicación práctica de pares de fuerzas de acción y reacción.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-teal btn-sm fw-bold px-3" data-open-fis-video="l3-video" data-video-mode="alt">
                                            ▶ Reproducir Alternativo
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=CVsaou5_9oM" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 border bg-white small mb-4">
                            <strong>🌐 Página Web Recomendada:</strong>
                            <a href="https://es.khanacademy.org" target="_blank" rel="noopener noreferrer" class="fw-bold text-teal">Khan Academy (es.khanacademy.org)</a> — Unidad de "Fuerzas y Leyes de Newton" con artículos paso a paso sobre Diagramas de Cuerpo Libre (DCL).
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-fis-goto="l3-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-teal fw-bold px-4" data-fis-goto="l3-simulador">Siguiente: Simulador Fricción y DCL ⚙</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 3 · ITEM 3: SIMULADOR (Fricción Ajustable y DCL)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l3-simulador">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="fis-type-badge fis-badge-sim">⚙ Lección 3 · Simulador Interactivo</span>
                            <span class="text-muted small">Condicionales Físicas y Diagrama de Cuerpo Libre</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Simulador: Entorno de Fricción Ajustable y DCL</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Condición lógica:</strong> Si la fuerza de empuje ($F$) es mayor que la fricción estática máxima ($f_r = \mu \cdot N$), cambia el estado del objeto a <strong>"en movimiento"</strong> y calcula su aceleración $a = \sum F / m$.
                        </p>

                        <div class="fis-sim-box mb-4">
                            <div class="fis-sim-topbar">
                                <span class="fw-bold text-emerald small">⚙ Evaluador DCL · Leyes de Newton</span>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-warning text-dark font-monospace" id="fisL3StateBadge">ESTADO: EN REPOSO</span>
                                    <span class="badge bg-info text-dark font-monospace" id="fisL3FnetBadge">∑F = 0.0 N</span>
                                    <span class="badge bg-success text-white font-monospace" id="fisL3AccelBadge">a = 0.00 m/s²</span>
                                </div>
                            </div>
                            <div class="fis-sim-body">
                                <div class="row g-4 align-items-center mb-3">
                                    <div class="col-12 col-md-6">
                                        <div class="fis-sim-canvas-wrap">
                                            <svg viewBox="0 0 320 220" class="w-100" aria-label="Diagrama de Cuerpo Libre Interactivo">
                                                <!-- Superficie del suelo -->
                                                <line x1="20" y1="155" x2="300" y2="155" stroke="#64748b" stroke-width="3"/>
                                                <!-- Caja / Masa m -->
                                                <rect x="125" y="95" width="70" height="60" rx="8" fill="#0f766e" stroke="#2dd4bf" stroke-width="2"/>
                                                <circle cx="160" cy="125" r="4" fill="#ffffff"/>

                                                <!-- Vector Normal N (Arriba) -->
                                                <line x1="160" y1="125" x2="160" y2="42" stroke="#10b981" stroke-width="3"/>
                                                <polygon points="160,34 155,44 165,44" fill="#10b981"/>
                                                <text x="168" y="46" font-size="10" fill="#34d399" font-weight="bold">Normal (N)</text>

                                                <!-- Vector Peso W (Abajo) -->
                                                <line x1="160" y1="125" x2="160" y2="202" stroke="#38bdf8" stroke-width="3"/>
                                                <polygon points="160,210 155,200 165,200" fill="#38bdf8"/>
                                                <text x="168" y="206" font-size="10" fill="#38bdf8" font-weight="bold">Peso (W = mg)</text>

                                                <!-- Vector Fuerza Empuje F_app (Derecha) -->
                                                <line id="fisL3VecFapp" x1="160" y1="125" x2="215" y2="125" stroke="#f59e0b" stroke-width="3.5"/>
                                                <text id="fisL3LabelFapp" x="230" y="114" font-size="10" fill="#fbbf24" font-weight="bold" text-anchor="middle">F = 80 N →</text>

                                                <!-- Vector Fricción f_r (Izquierda) -->
                                                <line id="fisL3VecFr" x1="160" y1="125" x2="105" y2="125" stroke="#f43f5e" stroke-width="3.5"/>
                                                <text id="fisL3LabelFr" x="85" y="114" font-size="10" fill="#fb7185" font-weight="bold" text-anchor="middle">← f_r = 80.0 N</text>

                                                <text id="fisL3LabelWN" x="82" y="24" font-size="10" fill="#cbd5e1">N = W = 294.3 N</text>
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="mb-2">
                                            <label for="fisL3MuSelect" class="form-label small text-slate-300 mb-1">Superficie / Coeficiente de Fricción ($\mu$):</label>
                                            <select id="fisL3MuSelect" class="form-select form-select-sm bg-dark text-white border-secondary">
                                                <option value="0.10">Hielo pulido (μ = 0.10)</option>
                                                <option value="0.35" selected>Madera sobre madera (μ = 0.35)</option>
                                                <option value="0.65">Asfalto rugoso (μ = 0.65)</option>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <label for="fisL3ForceSlider" class="form-label small text-slate-300 mb-1">Fuerza de Empuje Aplicada ($F$): $0$ a $400\text{ N}$</label>
                                            <input type="range" class="form-range" id="fisL3ForceSlider" min="0" max="400" step="5" value="80">
                                        </div>
                                        <div class="mb-3">
                                            <label for="fisL3MassSlider" class="form-label small text-slate-300 mb-1">Masa del Objeto ($m$): $10$ a $60\text{ kg}$</label>
                                            <input type="range" class="form-range" id="fisL3MassSlider" min="10" max="60" step="5" value="30">
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-light" id="fisL3ResetBtn">Reiniciar</button>
                                            <button type="button" class="btn btn-sm btn-teal fw-bold flex-grow-1" id="fisL3StepBtn">Vencer fricción (Mover objeto) ▷</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="fis-sim-console" id="fisL3Console"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-fis-goto="l3-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-teal fw-bold px-4" data-fis-goto="l3-pdf">Siguiente: Guía Visual 03 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 3 · ITEM 4: GUÍA PDF (Guia_3_Diagramas_Cuerpo_Libre.pdf)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l3-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="fis-type-badge fis-badge-pdf">📄 Lección 3 · Guía Visual Incorporada</span>
                            <div class="d-flex align-items-center gap-2">
                                <code class="small">Guia_3_Diagramas_Cuerpo_Libre.pdf</code>
                                <button type="button" class="btn btn-teal btn-sm fw-bold" data-print-guide="fisGuideContent-3" data-guide-title="Guía 03 - Diagrama de Cuerpo Libre (DCL)">
                                    🖨️ Imprimir / Guardar PDF
                                </button>
                            </div>
                        </div>

                        <!-- Código HTML/CSS de la Guía 03 incorporado directamente -->
                        <div class="fis-guide-container mb-4" id="fisGuideContent-3" style="border-top: 8px solid #f59e0b;">
                            <header class="pb-3 mb-4 border-bottom">
                                <p class="text-xs fw-bold text-warning text-uppercase tracking-wider mb-1">Módulo: Física | Lección 3</p>
                                <h2 class="h3 fw-extrabold text-dark mb-1">GUÍA 03: Diagrama de Cuerpo Libre (DCL)</h2>
                                <p class="text-secondary small mb-0">Depurando (debugging) las fuerzas que actúan sobre un objeto.</p>
                            </header>

                            <!-- Algoritmo de Construcción -->
                            <section class="mb-4">
                                <h3 class="h5 fw-bold text-dark mb-3 d-flex align-items-center">
                                    <span class="badge bg-warning-subtle text-warning-emphasis me-2">#</span>
                                    Algoritmo de Construcción (Paso a Paso)
                                </h3>

                                <div class="d-flex flex-column gap-3">
                                    <div class="row g-2 bg-light p-3 rounded-3 border-start border-4 border-secondary m-0">
                                        <div class="col-12 col-md-4 fw-bold text-secondary code-font small">Paso 1: Inicialización</div>
                                        <div class="col-12 col-md-8 small text-secondary">Dibuja un punto central en un plano cartesiano. Este punto concentrará toda la masa del objeto.</div>
                                    </div>

                                    <div class="row g-2 bg-primary-subtle bg-opacity-25 p-3 rounded-3 border-start border-4 border-primary m-0">
                                        <div class="col-12 col-md-4 fw-bold text-primary code-font small">if (tiene_masa)</div>
                                        <div class="col-12 col-md-8">
                                            <p class="fw-bold text-dark small mb-1">Dibuja el Peso ($W$)</p>
                                            <p class="text-secondary small mb-2">Vector apuntando siempre hacia el Eje $-Y$ (hacia abajo).</p>
                                            <div class="math-box py-1 small">$$W = m \cdot g$$</div>
                                        </div>
                                    </div>

                                    <div class="row g-2 bg-success-subtle bg-opacity-25 p-3 rounded-3 border-start border-4 border-success m-0">
                                        <div class="col-12 col-md-4 fw-bold text-success code-font small">if (toca_superficie)</div>
                                        <div class="col-12 col-md-8">
                                            <p class="fw-bold text-dark small mb-1">Dibuja la Normal ($N$)</p>
                                            <p class="text-secondary small mb-0">Vector perpendicular ($90^\circ$) alejándose de la superficie de contacto.</p>
                                        </div>
                                    </div>

                                    <div class="row g-2 bg-danger-subtle bg-opacity-25 p-3 rounded-3 border-start border-4 border-danger m-0">
                                        <div class="col-12 col-md-4 fw-bold text-danger code-font small">if (hay_friccion)</div>
                                        <div class="col-12 col-md-8">
                                            <p class="fw-bold text-dark small mb-1">Dibuja la Fricción ($f_r$)</p>
                                            <p class="text-secondary small mb-2">Vector paralelo a la superficie, apuntando en contra de la dirección del movimiento.</p>
                                            <div class="math-box py-1 small">$$f_r = \mu \cdot N$$</div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- Evaluador de Estados -->
                            <section>
                                <h3 class="h5 fw-bold text-dark mb-3">Evaluador de Estados (Leyes de Newton)</h3>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="bg-dark text-white p-4 rounded-3 text-center h-100">
                                            <p class="text-warning fw-bold code-font small mb-1">if (SumaFuerzas == 0)</p>
                                            <h4 class="h6 fw-bold mb-1">1ra Ley (Inercia)</h4>
                                            <p class="text-light opacity-75 small mb-3">El objeto está en reposo perfecto o se mueve a velocidad constante.</p>
                                            <div class="math-box bg-black bg-opacity-50 border border-secondary">$$\sum F = 0$$</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="bg-dark text-white p-4 rounded-3 text-center h-100">
                                            <p class="text-warning fw-bold code-font small mb-1">if (SumaFuerzas != 0)</p>
                                            <h4 class="h6 fw-bold mb-1">2da Ley (Dinámica)</h4>
                                            <p class="text-light opacity-75 small mb-3">El objeto acelera en la dirección de la fuerza resultante mayor.</p>
                                            <div class="math-box bg-black bg-opacity-50 border border-secondary">$$\sum F = m \cdot a$$</div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-fis-goto="l3-simulador">← Anterior: Simulador</button>
                            <button type="button" class="btn btn-teal fw-bold px-4" data-fis-goto="l4-lectura">Ir a Lección 4: Trabajo y Energía →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 4 · ITEM 1: LECTURA (Trabajo y Energía)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l4-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <div>
                                <span class="fis-type-badge fis-badge-reading mb-1">📖 Lección 4 · Lectura Activa</span>
                                <h2 class="h3 fw-bold mb-0">Trabajo y Energía (Ciclos de Conservación)</h2>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-teal fw-semibold" data-fis-note-toggle="l4">
                                📝 Guardar nota
                            </button>
                        </div>

                        <div class="fis-note-drawer d-none" id="fisNoteDrawer-l4">
                            <label for="fisNoteInput-l4" class="form-label small fw-bold text-dark mb-1">📝 Tus apuntes de la Lección 4 (Trabajo y Energía):</label>
                            <textarea id="fisNoteInput-l4" class="form-control form-control-sm mb-2" rows="2" placeholder="Anota tus observaciones sobre el balance de Joules en la montaña rusa..."></textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-success fw-semibold" id="fisNoteStatus-l4"></span>
                                <button type="button" class="btn btn-xs btn-teal fw-bold px-3" id="fisNoteSave-l4">Guardar apunte</button>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <strong>Tema:</strong> Energía cinética, potencial y conservación de la energía.
                        </p>

                        <h3 class="h5 fw-bold text-teal mb-2">¿Qué es? (Introducción Teórica)</h3>
                        <p class="text-secondary leading-relaxed mb-3">
                            En física, la <strong>"energía"</strong> es la capacidad de hacer que las cosas pasen (generar "Trabajo"). Lo más fascinante de la energía es que obedece a una regla absoluta de conservación: <strong><em>La energía no se crea ni se destruye, solo se transforma.</em></strong>
                        </p>
                        <p class="small text-secondary leading-relaxed mb-3">
                            En un simulador como el de una montaña rusa, la energía total del sistema siempre debe sumar $100\%$. Si un tipo de energía baja, otra debe subir para compensar. Existen dos tipos principales de energía mecánica:
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-4 border border-success-subtle h-100" style="background: rgba(204,251,241,0.35);">
                                    <h4 class="h6 fw-bold text-teal mb-1">🔋 1. Energía Potencial ($E_p$ - La batería cargada)</h4>
                                    <p class="small text-secondary mb-0">
                                        Es la energía almacenada por la posición de un objeto. Mientras más alto subas el carrito de la montaña rusa, más energía potencial almacena debido a la gravedad.
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-4 border bg-light h-100">
                                    <h4 class="h6 fw-bold text-emerald mb-1">⚡ 2. Energía Cinética ($E_c$ - La energía del movimiento)</h4>
                                    <p class="small text-secondary mb-0">
                                        Cuando el carrito cae, pierde altura (pierde energía potencial), pero gana velocidad (se transforma en energía cinética).
                                    </p>
                                </div>
                            </div>
                        </div>

                        <p class="small text-secondary leading-relaxed mb-4">
                            El <strong>"balance de energía"</strong> es una ecuación lógica de igualdad. Si mides la energía en el punto más alto de la montaña rusa (Punto A) y luego en el punto más bajo (Punto B), el total de Joules debe ser exactamente el mismo: $E_{\text{total inicial}} = E_{\text{total final}}$.
                        </p>

                        <h3 class="h5 fw-bold text-teal mb-3">Fórmulas Principales (El "Código" del Balance)</h3>

                        <div class="fis-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Trabajo Mecánico ($W$):</div>
                                <div class="small text-muted">Fuerza aplicada a lo largo de una distancia $d$</div>
                            </div>
                            <div class="fis-formula-math">$$W = F \cdot d \cdot \cos(\theta)$$</div>
                        </div>

                        <div class="fis-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Energía Potencial Gravitatoria ($E_p$):</div>
                                <div class="small text-muted">$\text{masa} \cdot \text{gravedad} \cdot \text{altura}$</div>
                            </div>
                            <div class="fis-formula-math">$$E_p = m \cdot g \cdot h$$</div>
                        </div>

                        <div class="fis-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Energía Cinética ($E_c$):</div>
                                <div class="small text-muted">Mitad de la masa por la velocidad al cuadrado</div>
                            </div>
                            <div class="fis-formula-math">$$E_c = \frac{1}{2} m \cdot v^2$$</div>
                        </div>

                        <div class="fis-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                            <div>
                                <div class="fw-bold text-dark">Principio de Conservación de la Energía:</div>
                                <div class="small text-muted">Igualdad energética entre el estado inicial y final</div>
                            </div>
                            <div class="fis-formula-math">$$E_{p_{\text{inicial}}} + E_{c_{\text{inicial}}} = E_{p_{\text{final}}} + E_{c_{\text{final}}}$$</div>
                        </div>

                        <div class="fis-tips-accordion mb-4">
                            <button class="fis-tips-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#fisTipsL4" aria-expanded="true">
                                <span>✦ Tips para el Reto ABP: Montaña Rusa</span>
                                <span>[ ˅ ]</span>
                            </button>
                            <div id="fisTipsL4" class="collapse show">
                                <div class="fis-tips-body">
                                    Para que el carrito complete un bucle vertical de radio $R$ sin despegarse del riel en la cima, no basta con iniciar a la altura del bucle ($2R$); necesita conservar velocidad en la cima, requiriendo una altura mínima inicial de $h_{\min} = 2.5 \cdot R$.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                            <div class="d-flex align-items-center gap-2 fis-feedback-group">
                                <span class="small text-muted">¿Útil?</span>
                                <button type="button" class="fis-feedback-btn" data-fis-feedback="up">👍</button>
                                <button type="button" class="fis-feedback-btn" data-fis-feedback="down">👎</button>
                                <span class="small text-teal fw-semibold fis-feedback-msg"></span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-teal btn-sm fw-bold" data-fis-goto="l4-video">
                                    ▶ Vídeo Energía
                                </button>
                                <button type="button" class="btn btn-teal fw-bold px-4 shadow-teal" data-fis-goto="l4-reto">
                                    Ir al Reto ABP: Montaña Rusa ➔
                                </button>
                            </div>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 4 · ITEM 2: VÍDEO (Pop-up YouTube)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l4-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="fis-type-badge fis-badge-reading">▶ Lección 4 · Recursos Audiovisuales (Pop-up)</span>
                            <span class="text-muted small">Video: "El bucle infinito de la Energía"</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Energía Cinética, Potencial y Mecánica</h2>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-teal text-white mb-2" style="background:#0d9488;">Video Recomendado</span>
                                        <h3 class="h6 fw-bold mb-1">Energía Cinética, Potencial y Mecánica</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>La Profe Joha</strong></p>
                                        <p class="small text-secondary mb-3">Explica de manera muy visual la transferencia de energía, ideal para la analogía de la montaña rusa.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-teal btn-sm fw-bold px-3" data-open-fis-video="l4-video" data-video-mode="main">
                                            ▶ Reproducir en Modal
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=hYuNxhL111E" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-secondary mb-2">Video Alternativo</span>
                                        <h3 class="h6 fw-bold mb-1">Energía MECÁNICA | Principio de Conservación de la Energía</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>Susi Profe</strong></p>
                                        <p class="small text-secondary mb-3">Resolución práctica de ejercicios aplicando $E_{m(A)} = E_{m(B)}$.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-teal btn-sm fw-bold px-3" data-open-fis-video="l4-video" data-video-mode="alt">
                                            ▶ Reproducir Alternativo
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=f4IWnif1wjs" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 border bg-white small mb-4">
                            <strong>🌐 Simulador de Referencia PhET:</strong>
                            <a href="https://phet.colorado.edu/en/simulations/energy-skate-park" target="_blank" rel="noopener noreferrer" class="fw-bold text-teal">Energy Skate Park (Pista de Patinaje sobre Energía) ↗</a>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-fis-goto="l4-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-teal fw-bold px-4" data-fis-goto="l4-reto">Siguiente: Reto ABP Montaña Rusa ⚡</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 4 · ITEM 3: RETO ABP (Montaña Rusa: Cálculo de Joules)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l4-reto">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="fis-type-badge fis-badge-abp">⚡ Lección 4 · Reto ABP</span>
                            <span class="text-muted small">Transferencia y Balance de Joules</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Reto ABP: Montaña Rusa — Cálculo de Joules</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Objetivo:</strong> Calcula la altura mínima inicial ($h_A$) requerida para que un carrito de $m = 100\text{ kg}$ complete un bucle de radio $R = 8\text{ m}$ (altura del bucle $= 16\text{ m}$, altura crítica $= 20\text{ m}$) sin caerse, verificando que $E_p + E_c = E_{\text{total}}$ en cada punto.
                        </p>

                        <div class="fis-sim-box mb-4">
                            <div class="fis-sim-topbar">
                                <span class="fw-bold text-emerald small">⚡ Simulador de Conservación de Energía · Carrito m = 100 kg</span>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-info text-dark font-monospace" id="fisL4VelBadge">v = 0.00 m/s</span>
                                    <span class="badge bg-emerald text-white font-monospace" id="fisL4LoopBadge">✓ Bucle Superado (h_A = 24m ≥ 20m)</span>
                                </div>
                            </div>
                            <div class="fis-sim-body">
                                <div class="row g-4 align-items-center mb-3">
                                    <div class="col-12 col-md-7">
                                        <div class="fis-sim-canvas-wrap">
                                            <svg viewBox="0 0 320 200" class="w-100" aria-label="Pista de Montaña Rusa con Bucle">
                                                <!-- Suelo h = 0 -->
                                                <line x1="15" y1="175" x2="305" y2="175" stroke="#475569" stroke-width="2"/>
                                                <!-- Pista Montaña Rusa -->
                                                <path d="M 30 55 C 75 55, 95 170, 135 170 C 175 170, 250 170, 250 125 C 250 75, 180 75, 180 125 C 180 170, 260 170, 300 155"
                                                      fill="none" stroke="#14b8a6" stroke-width="3.5"/>
                                                <!-- Etiquetas de Puntos A, B, C -->
                                                <text x="35" y="42" font-size="10" fill="#5eead4" font-weight="bold">Punto A (Inicio)</text>
                                                <text x="135" y="190" font-size="10" fill="#38bdf8" font-weight="bold" text-anchor="middle">Punto B (h=0m)</text>
                                                <text x="215" y="68" font-size="10" fill="#fbbf24" font-weight="bold" text-anchor="middle">Punto C (Cima Bucle 16m)</text>

                                                <!-- Carrito -->
                                                <circle id="fisL4CarMarker" cx="42" cy="76" r="8" fill="#f59e0b" stroke="#ffffff" stroke-width="2"/>
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-5">
                                        <!-- Barras de Energía Estilo Energy Skate Park -->
                                        <div class="d-flex justify-content-around text-center mb-3 p-2 rounded-3 bg-dark border border-secondary">
                                            <div>
                                                <div class="fis-energy-bar-track">
                                                    <div id="fisL4EpBar" class="fis-energy-bar-fill" style="height: 70%; background:#38bdf8;"></div>
                                                </div>
                                                <div class="text-xs text-info fw-bold mt-1">$E_p$</div>
                                                <div class="text-xs font-monospace text-white" id="fisL4EpVal">23.5 kJ</div>
                                            </div>
                                            <div>
                                                <div class="fis-energy-bar-track">
                                                    <div id="fisL4EcBar" class="fis-energy-bar-fill" style="height: 0%; background:#10b981;"></div>
                                                </div>
                                                <div class="text-xs text-emerald fw-bold mt-1">$E_c$</div>
                                                <div class="text-xs font-monospace text-white" id="fisL4EcVal">0.0 kJ</div>
                                            </div>
                                            <div>
                                                <div class="fis-energy-bar-track">
                                                    <div id="fisL4EtotBar" class="fis-energy-bar-fill" style="height: 70%; background:#f59e0b;"></div>
                                                </div>
                                                <div class="text-xs text-warning fw-bold mt-1">$E_{\text{total}}$</div>
                                                <div class="text-xs font-monospace text-white" id="fisL4EtotVal">23.5 kJ</div>
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <label for="fisL4HeightSlider" class="form-label small text-slate-300 mb-1">Altura Inicial $h_A$ ($12\text{ m}$ a $35\text{ m}$):</label>
                                            <input type="range" class="form-range" id="fisL4HeightSlider" min="12" max="35" step="1" value="24">
                                        </div>
                                        <div class="mb-3">
                                            <label for="fisL4PointSelect" class="form-label small text-slate-300 mb-1">Inspeccionar Punto de la Pista:</label>
                                            <select id="fisL4PointSelect" class="form-select form-select-sm bg-dark text-white border-secondary">
                                                <option value="A">Punto A: Cima Inicial (v = 0, E_p máxima)</option>
                                                <option value="B">Punto B: Valle más bajo (h = 0, E_c máxima)</option>
                                                <option value="C">Punto C: Cima del Bucle (h = 16 m)</option>
                                            </select>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-teal fw-bold w-100" id="fisL4StepBtn">
                                            Recorrer siguiente punto del ciclo (A → B → C) ▷
                                        </button>
                                    </div>
                                </div>

                                <div class="fis-sim-console" id="fisL4Console"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-fis-goto="l4-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-teal fw-bold px-4" data-fis-goto="l4-pdf">Siguiente: Guía Visual 04 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 4 · ITEM 4: GUÍA PDF (Guia_4_Balance_Energia.pdf)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l4-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="fis-type-badge fis-badge-pdf">📄 Lección 4 · Guía Visual Incorporada</span>
                            <div class="d-flex align-items-center gap-2">
                                <code class="small">Guia_4_Balance_Energia.pdf</code>
                                <button type="button" class="btn btn-teal btn-sm fw-bold" data-print-guide="fisGuideContent-4" data-guide-title="Guía 04 - Conservación y Balance de Energía">
                                    🖨️ Imprimir / Guardar PDF
                                </button>
                            </div>
                        </div>

                        <!-- Código HTML/CSS de la Guía 04 incorporado directamente -->
                        <div class="fis-guide-container mb-4" id="fisGuideContent-4" style="border-top: 8px solid #14b8a6;">
                            <header class="pb-3 mb-4 border-bottom">
                                <p class="text-xs fw-bold text-teal text-uppercase tracking-wider mb-1">Módulo: Física | Lección 4</p>
                                <h2 class="h3 fw-extrabold text-dark mb-1">GUÍA 04: Conservación y Balance de Energía</h2>
                                <p class="text-secondary small mb-0">La energía como un ciclo de transformación equitativa (Input = Output).</p>
                            </header>

                            <section class="mb-4">
                                <h3 class="h5 fw-bold text-dark mb-3">Variables Energéticas</h3>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="p-4 rounded-4 border position-relative overflow-hidden h-100" style="background:#f0fdfa; border-color:#99f6e4 !important;">
                                            <span class="badge bg-teal text-white code-font mb-2" style="background:#0d9488;">if (velocidad &gt; 0)</span>
                                            <h4 class="h5 fw-extrabold text-dark mb-2">Energía Cinética ($E_c$)</h4>
                                            <p class="small text-secondary mb-3">La energía asociada a cualquier objeto que se encuentra en movimiento.</p>
                                            <div class="math-box" style="background:#134e4a;">$$E_c = \frac{1}{2} m \cdot v^2$$</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="p-4 rounded-4 border position-relative overflow-hidden h-100" style="background:#ecfdf5; border-color:#a7f3d0 !important;">
                                            <span class="badge bg-success text-white code-font mb-2">if (altura &gt; 0)</span>
                                            <h4 class="h5 fw-extrabold text-dark mb-2">Energía Potencial ($E_p$)</h4>
                                            <p class="small text-secondary mb-3">La energía almacenada dependiendo de su posición o altura respecto al suelo.</p>
                                            <div class="math-box" style="background:#064e3b;">$$E_p = m \cdot g \cdot h$$</div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="bg-dark text-white rounded-4 p-4 shadow-sm">
                                <h3 class="h5 fw-bold text-emerald text-center mb-1">La Ecuación Maestra del Balance</h3>
                                <p class="text-center text-secondary small mb-3">En un sistema cerrado (sin fricción), la suma de las energías en el Punto A es idéntica al Punto B.</p>

                                <div class="math-box bg-black bg-opacity-50 border border-success border-opacity-25 mb-3">
                                    $$E_{c(A)} + E_{p(A)} = E_{c(B)} + E_{p(B)}$$
                                </div>

                                <div class="math-box bg-black bg-opacity-50 border border-success border-opacity-25 mb-3">
                                    $$\frac{1}{2}m(v_A)^2 + mgh_A = \frac{1}{2}m(v_B)^2 + mgh_B$$
                                </div>

                                <div class="row g-2 text-center small">
                                    <div class="col-12 col-md-6">
                                        <div class="p-2 rounded bg-white bg-opacity-10">
                                            <strong class="text-info">Tip:</strong> Si el objeto parte del reposo, $v_A = 0$ y por ende $E_{c(A)} = 0$.
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="p-2 rounded bg-white bg-opacity-10">
                                            <strong class="text-emerald">Tip:</strong> Si llega al nivel del suelo, $h_B = 0$ y por ende $E_{p(B)} = 0$.
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-fis-goto="l4-reto">← Anterior: Reto ABP</button>
                            <button type="button" class="btn btn-teal fw-bold px-4" data-fis-goto="l5-lectura">Ir a Lección 5: Colisiones y Momento →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 5 · ITEM 1: LECTURA (Colisiones y Cantidad de Movimiento)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l5-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <div>
                                <span class="fis-type-badge fis-badge-reading mb-1">📖 Lección 5 · Lectura Activa</span>
                                <h2 class="h3 fw-bold mb-0">Colisiones y Cantidad de Movimiento (Sistemas de Ecuaciones)</h2>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-teal fw-semibold" data-fis-note-toggle="l5">
                                📝 Guardar nota
                            </button>
                        </div>

                        <div class="fis-note-drawer d-none" id="fisNoteDrawer-l5">
                            <label for="fisNoteInput-l5" class="form-label small fw-bold text-dark mb-1">📝 Tus apuntes de la Lección 5 (Colisiones):</label>
                            <textarea id="fisNoteInput-l5" class="form-control form-control-sm mb-2" rows="2" placeholder="Anota la diferencia entre choque elástico e inelástico..."></textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-success fw-semibold" id="fisNoteStatus-l5"></span>
                                <button type="button" class="btn btn-xs btn-teal fw-bold px-3" id="fisNoteSave-l5">Guardar apunte</button>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <strong>Tema:</strong> Impulso, choques elásticos e inelásticos.
                        </p>

                        <h3 class="h5 fw-bold text-teal mb-2">¿Qué es? (Introducción Teórica)</h3>
                        <p class="text-secondary leading-relaxed mb-3">
                            Cuando dos objetos interactúan bruscamente, ocurre una colisión o choque. Para calcular qué pasará después del impacto (como en una mesa de billar virtual), la física no usa las Leyes de Newton directamente, sino un concepto derivado: la <strong>Cantidad de Movimiento</strong> (o momento lineal, representado por la letra $p$).
                        </p>
                        <p class="small text-secondary leading-relaxed mb-3">
                            La cantidad de movimiento es el <strong>"peso del movimiento"</strong>. Depende de dos cosas: qué tan masivo es el objeto y qué tan rápido va ($p = m \cdot v$). Un camión yendo a $10\text{ km/h}$ tiene mucha más cantidad de movimiento que una bicicleta yendo a $10\text{ km/h}$.
                        </p>
                        <p class="small text-secondary mb-3">
                            Al igual que la energía, la cantidad de movimiento <strong>siempre se conserva</strong> antes y después del choque. Existen dos tipos de reglas lógicas para las colisiones:
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-4 border border-primary-subtle bg-light h-100">
                                    <h4 class="h6 fw-bold text-primary mb-1">🎱 1. Colisión Elástica (Rebote perfecto)</h4>
                                    <p class="small text-secondary mb-0">
                                        Los objetos chocan y rebotan sin deformarse (como las bolas de billar). Aquí se conserva <strong>tanto la cantidad de movimiento como la energía cinética pura</strong>.
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-4 border border-danger-subtle bg-light h-100">
                                    <h4 class="h6 fw-bold text-danger mb-1">🚗 2. Colisión Inelástica (Deformación o Acople)</h4>
                                    <p class="small text-secondary mb-0">
                                        Los objetos chocan, se abollan o se quedan pegados (como un choque de autos o plastilina). Se conserva la cantidad de movimiento, pero <em>no</em> la energía cinética, ya que parte de ella se gasta en deformar los objetos o hacer ruido.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <h3 class="h5 fw-bold text-teal mb-3">Fórmulas Principales (Para el Simulador de Billar)</h3>

                        <div class="fis-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Cantidad de Movimiento ($p$):</div>
                                <div class="small text-muted">Producto de la masa por el vector velocidad</div>
                            </div>
                            <div class="fis-formula-math">$$p = m \cdot v$$</div>
                        </div>

                        <div class="fis-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold text-dark">Impulso Mecánico ($I$):</div>
                                <div class="small text-muted">Fuerza multiplicada por el tiempo de impacto</div>
                            </div>
                            <div class="fis-formula-math">$$I = F \cdot \Delta t$$</div>
                        </div>

                        <div class="fis-formula-card d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                            <div>
                                <div class="fw-bold text-dark">Ecuación de Conservación (El choque):</div>
                                <div class="small text-muted">Momento total antes del impacto = Momento total después del impacto</div>
                            </div>
                            <div class="fis-formula-math">$$(m_1 \cdot v_{1_{\text{inicial}}}) + (m_2 \cdot v_{2_{\text{inicial}}}) = (m_1 \cdot v_{1_{\text{final}}}) + (m_2 \cdot v_{2_{\text{final}}})$$</div>
                        </div>

                        <div class="fis-tips-accordion mb-4">
                            <button class="fis-tips-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#fisTipsL5" aria-expanded="true">
                                <span>✦ Tips para el Simulador de Mesa de Billar</span>
                                <span>[ ˅ ]</span>
                            </button>
                            <div id="fisTipsL5" class="collapse show">
                                <div class="fis-tips-body">
                                    Recuerda que la velocidad es un vector con signo: si la Bola 2 se mueve hacia la izquierda (en contra de la Bola 1), debes ingresar su velocidad inicial como un <strong>número negativo</strong> (ej. $v_{2i} = -4 \, m/s$).
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                            <div class="d-flex align-items-center gap-2 fis-feedback-group">
                                <span class="small text-muted">¿Útil?</span>
                                <button type="button" class="fis-feedback-btn" data-fis-feedback="up">👍</button>
                                <button type="button" class="fis-feedback-btn" data-fis-feedback="down">👎</button>
                                <span class="small text-teal fw-semibold fis-feedback-msg"></span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-teal btn-sm fw-bold" data-fis-goto="l5-video">
                                    ▶ Vídeo Colisiones
                                </button>
                                <button type="button" class="btn btn-teal fw-bold px-4 shadow-teal" data-fis-goto="l5-simulador">
                                    Ir al Simulador: Mesa de Billar ➔
                                </button>
                            </div>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 5 · ITEM 2: VÍDEO (Pop-up YouTube)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l5-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="fis-type-badge fis-badge-reading">▶ Lección 5 · Recursos Audiovisuales (Pop-up)</span>
                            <span class="text-muted small">Video: "La física de los impactos"</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Colisiones y Choques en Física</h2>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-teal text-white mb-2" style="background:#0d9488;">Video Recomendado</span>
                                        <h3 class="h6 fw-bold mb-1">COLISIONES - ELÁSTICAS e INELÁSTICAS - Choques | Explicación y Ejercicios</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>Profe Edi</strong></p>
                                        <p class="small text-secondary mb-3">Explica detalladamente cómo diferenciar los tipos de impactos y conservar el momento lineal.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-teal btn-sm fw-bold px-3" data-open-fis-video="l5-video" data-video-mode="main">
                                            ▶ Reproducir en Modal
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=_zu67RXVuUM" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-4 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-secondary mb-2">Video Alternativo</span>
                                        <h3 class="h6 fw-bold mb-1">Tipos de Colisiones o Choques en Física</h3>
                                        <p class="small text-muted mb-2">Canal: <strong>Es Ciencia</strong></p>
                                        <p class="small text-secondary mb-3">Resumen claro de choques elásticos, inelásticos y perfectamente inelásticos.</p>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-teal btn-sm fw-bold px-3" data-open-fis-video="l5-video" data-video-mode="alt">
                                            ▶ Reproducir Alternativo
                                        </button>
                                        <a href="https://www.youtube.com/watch?v=f2ABNZVMIC4" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                            Abrir en YouTube ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 border bg-white small mb-4">
                            <strong>🌐 Página Web Recomendada:</strong>
                            <a href="http://hyperphysics.phy-astr.gsu.edu" target="_blank" rel="noopener noreferrer" class="fw-bold text-teal">HyperPhysics (hyperphysics.phy-astr.gsu.edu)</a> — Biblioteca de mapas conceptuales de física universitaria ("Momento y Colisiones").
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-fis-goto="l5-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-teal fw-bold px-4" data-fis-goto="l5-simulador">Siguiente: Simulador Mesa de Billar ⚙</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 5 · ITEM 3: SIMULADOR (Mesa de Billar Virtual)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l5-simulador">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="fis-type-badge fis-badge-sim">⚙ Lección 5 · Simulador Interactivo</span>
                            <span class="text-muted small">Conservación de Cantidad de Movimiento $p = m \cdot v$</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Simulador: Mesa de Billar Virtual Interactiva</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Ejercicio:</strong> Manipula la masa y velocidad inicial de las dos bolas, elige el tipo de choque y comprueba las velocidades finales tras la colisión verificando que $p_{\text{inicial}} = p_{\text{final}}$.
                        </p>

                        <div class="fis-sim-box mb-4">
                            <div class="fis-sim-topbar">
                                <span class="fw-bold text-emerald small">🎱 Mesa de Billar Virtual · Comprobador de Variables</span>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-info text-dark font-monospace" id="fisL5PInitialBadge">p_inicial = 10.00 kg·m/s</span>
                                    <span class="badge bg-emerald text-white font-monospace" id="fisL5PFinalBadge">p_final = 10.00 kg·m/s</span>
                                </div>
                            </div>
                            <div class="fis-sim-body">
                                <div class="fis-sim-canvas-wrap mb-3" style="background:#064e3b; border: 4px solid #78350f;">
                                    <svg viewBox="0 0 340 120" class="w-100" aria-label="Mesa de Billar Virtual">
                                        <!-- Línea de acción central -->
                                        <line x1="25" y1="65" x2="315" y2="65" stroke="rgba(255,255,255,0.25)" stroke-width="1.5" stroke-dasharray="4,4"/>
                                        <!-- Bola 1 -->
                                        <circle cx="115" cy="65" r="22" fill="#38bdf8" stroke="#ffffff" stroke-width="2"/>
                                        <text x="115" y="69" font-size="11" font-weight="bold" fill="#0f172a" text-anchor="middle">m₁</text>
                                        <line x1="138" y1="65" x2="165" y2="65" stroke="#fde047" stroke-width="3"/>

                                        <!-- Bola 2 -->
                                        <circle cx="225" cy="65" r="22" fill="#f43f5e" stroke="#ffffff" stroke-width="2"/>
                                        <text x="225" y="69" font-size="11" font-weight="bold" fill="#ffffff" text-anchor="middle">m₂</text>
                                        <line x1="202" y1="65" x2="175" y2="65" stroke="#fde047" stroke-width="3"/>

                                        <text x="170" y="30" font-size="11" fill="#a7f3d0" font-weight="bold" text-anchor="middle">⚡ Punto de Colisión</text>
                                    </svg>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-md-4">
                                        <label for="fisL5TypeSelect" class="form-label small text-slate-300 mb-1">Regla Lógica de Colisión:</label>
                                        <select id="fisL5TypeSelect" class="form-select form-select-sm bg-dark text-white border-secondary">
                                            <option value="elastica" selected>Choque Elástico (Rebote perfecto)</option>
                                            <option value="inelastica">Choque Inelástico (Se acoplan juntos)</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label for="fisL5M1Input" class="form-label small text-info mb-1">Masa $m_1$ (kg):</label>
                                        <input type="number" id="fisL5M1Input" class="form-control form-control-sm bg-dark text-white border-info font-monospace" value="2" min="0.5" max="20" step="0.5">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label for="fisL5V1Input" class="form-label small text-info mb-1">Vel. $v_{1i}$ (m/s):</label>
                                        <input type="number" id="fisL5V1Input" class="form-control form-control-sm bg-dark text-white border-info font-monospace" value="6" min="-20" max="20" step="1">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label for="fisL5M2Input" class="form-label small text-danger mb-1">Masa $m_2$ (kg):</label>
                                        <input type="number" id="fisL5M2Input" class="form-control form-control-sm bg-dark text-white border-danger font-monospace" value="2" min="0.5" max="20" step="0.5">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label for="fisL5V2Input" class="form-label small text-danger mb-1">Vel. $v_{2i}$ (m/s):</label>
                                        <input type="number" id="fisL5V2Input" class="form-control form-control-sm bg-dark text-white border-danger font-monospace" value="-1" min="-20" max="20" step="1">
                                    </div>
                                </div>

                                <div class="row g-3 align-items-center mb-3">
                                    <div class="col-6 col-md-4">
                                        <div class="p-2 rounded-3 bg-dark border border-info text-center">
                                            <div class="text-xs text-info">Velocidad Final Bola 1 ($v_{1f}$)</div>
                                            <div class="fw-bold fs-6 font-monospace text-white" id="fisL5V1fDisplay">-1.00 m/s</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <div class="p-2 rounded-3 bg-dark border border-danger text-center">
                                            <div class="text-xs text-danger">Velocidad Final Bola 2 ($v_{2f}$)</div>
                                            <div class="fw-bold fs-6 font-monospace text-white" id="fisL5V2fDisplay">+6.00 m/s</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <button type="button" class="btn btn-teal fw-bold w-100 py-2" id="fisL5CollideBtn">
                                            🎱 Ejecutar Choque y Comprobar
                                        </button>
                                    </div>
                                </div>

                                <div class="fis-sim-console" id="fisL5Console"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-fis-goto="l5-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-teal fw-bold px-4" data-fis-goto="l5-pdf">Siguiente: Guía Visual 05 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 5 · ITEM 4: GUÍA PDF (Guia_5_Resolucion_Choques.pdf)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="l5-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="fis-type-badge fis-badge-pdf">📄 Lección 5 · Guía Visual Incorporada</span>
                            <div class="d-flex align-items-center gap-2">
                                <code class="small">Guia_5_Resolucion_Choques.pdf</code>
                                <button type="button" class="btn btn-teal btn-sm fw-bold" data-print-guide="fisGuideContent-5" data-guide-title="Guía 05 - Algoritmo para Colisiones y Choques">
                                    🖨️ Imprimir / Guardar PDF
                                </button>
                            </div>
                        </div>

                        <!-- Código HTML/CSS de la Guía 05 incorporado directamente -->
                        <div class="fis-guide-container mb-4" id="fisGuideContent-5" style="border-top: 8px solid #e11d48;">
                            <header class="pb-3 mb-4 border-bottom">
                                <p class="text-xs fw-bold text-danger text-uppercase tracking-wider mb-1">Módulo: Física | Lección 5</p>
                                <h2 class="h3 fw-extrabold text-dark mb-1">GUÍA 05: Algoritmo para Colisiones y Choques</h2>
                                <p class="text-secondary small mb-0">Conservación de la cantidad de movimiento (momentum) en impactos.</p>
                            </header>

                            <!-- El Momento -->
                            <section class="mb-4 row g-3 align-items-center bg-light rounded-4 p-4 border m-0">
                                <div class="col-12 col-md-8">
                                    <h3 class="h5 fw-bold text-dark mb-2">La Variable de "Momento" ($p$)</h3>
                                    <p class="text-secondary small mb-3">
                                        En los impactos, no importa solo qué tan rápido va un objeto, sino qué tan "pesado" es. La Cantidad de Movimiento es el vector principal a conservar antes y después del choque.
                                    </p>
                                    <span class="badge bg-danger-subtle text-danger code-font px-3 py-2">
                                        Regla: Momento Inicial = Momento Final
                                    </span>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="math-box" style="background:#881337;">
                                        $$p = m \cdot v$$
                                    </div>
                                </div>
                            </section>

                            <!-- Árbol de Choques -->
                            <section>
                                <h3 class="h5 fw-bold text-dark mb-3 text-center">Clasificador de Impactos (Árbol de Decisión)</h3>

                                <div class="d-flex flex-column gap-3">
                                    <div class="border border-2 border-primary-subtle rounded-4 p-4">
                                        <span class="badge bg-primary text-uppercase mb-2">Choque Elástico</span>
                                        <p class="small text-secondary fw-semibold mb-3">Comportamiento: Chocan y rebotan separándose de inmediato (ej. Bolas de billar).</p>
                                        <div class="math-box" style="background:#1e3a8a;">
                                            $$(m_1 \cdot v_{1i}) + (m_2 \cdot v_{2i}) = (m_1 \cdot v_{1f}) + (m_2 \cdot v_{2f})$$
                                        </div>
                                    </div>

                                    <div class="border border-2 border-danger-subtle rounded-4 p-4">
                                        <span class="badge bg-danger text-uppercase mb-2">Choque Inelástico</span>
                                        <p class="small text-secondary fw-semibold mb-3">Comportamiento: Chocan, se acoplan y se mueven juntos a una misma velocidad final.</p>
                                        <div class="math-box" style="background:#881337;">
                                            $$(m_1 \cdot v_{1i}) + (m_2 \cdot v_{2i}) = (m_1 + m_2) \cdot v_f$$
                                        </div>
                                    </div>
                                </div>

                                <!-- Advertencia de Signos -->
                                <div class="mt-4 p-3 rounded-3 border border-warning bg-warning-subtle bg-opacity-25 d-flex align-items-start gap-3">
                                    <div class="fs-4">⚠️</div>
                                    <div>
                                        <h4 class="h6 fw-bold text-warning-emphasis text-uppercase mb-1">Cuidado con los Signos (Vectores)</h4>
                                        <p class="small text-secondary mb-0">
                                            La velocidad es un vector. Si un objeto viaja hacia la <strong>izquierda</strong>, DEBES ingresar su velocidad como un número negativo en las ecuaciones (ej. $-15 \, m/s$).
                                        </p>
                                    </div>
                                </div>
                            </section>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-fis-goto="l5-simulador">← Anterior: Simulador</button>
                            <button type="button" class="btn btn-teal fw-bold px-4 shadow-teal" data-fis-goto="cierre-encuesta">Ir al Módulo de Cierre y Certificación 🎓 →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         MÓDULO DE CIERRE · ITEM 1: ENCUESTA ESCALA LIKERT (1 AL 5)
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="cierre-encuesta">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="fis-type-badge fis-badge-closing">📊 Módulo de Cierre · Instrumento de Evaluación</span>
                            <button type="button" class="btn btn-xs btn-outline-teal fw-semibold" id="fisSurveyQuickFillBtn">
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
                            <span class="badge" style="background:#0d9488;">5: Totalmente de acuerdo (TA)</span>
                            <span class="badge bg-success">4: De acuerdo (DA)</span>
                            <span class="badge bg-secondary">3: Neutral / Indeciso (N)</span>
                            <span class="badge bg-warning text-dark">2: En desacuerdo (ED)</span>
                            <span class="badge bg-danger">1: Totalmente en desacuerdo (TED)</span>
                        </div>

                        <form id="fisLikertSurveyForm">
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
                                            <label class="form-check-label"><input type="radio" name="fis_sexo" value="M" class="form-check-input me-1">M</label>
                                            <label class="form-check-label"><input type="radio" name="fis_sexo" value="F" class="form-check-input me-1">F</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold mb-1">Módulo Evaluado:</label>
                                        <input type="text" class="form-control form-control-sm fw-bold text-teal" value="Física (Enfoque de Causa, Efecto y Simulación)" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Apartado II: Valoración de la Experiencia y el Impacto (Escala Likert) -->
                            <h3 class="h6 fw-bold mb-2">Apartado II: Valoración de la Experiencia y el Impacto (Escala Likert)</h3>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered fis-likert-table align-middle small mb-0">
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
                                        $criteriosFisica = [
                                            1  => ['Utilidad visual:', 'El simulador interactivo facilitó la visualización de los conceptos abstractos (como cambios, funciones o variables físicas) sin necesidad de recurrir únicamente a la memorización de fórmulas.'],
                                            2  => ['Reducción de carga cognitiva:', 'El uso de las herramientas visuales disminuyó la frustración y el esfuerzo mental excesivo al momento de enfrentar problemas complejos.'],
                                            3  => ['Aprendizaje Basado en Problemas (ABP):', 'Los retos prácticos basados en situaciones reales le dieron un sentido lógico y aplicable a los temas estudiados.'],
                                            4  => ['Razonamiento vs. Memorización:', 'Las actividades interactivas me obligaron a razonar el procedimiento lógico y la toma de decisiones en lugar de solo copiar o memorizar código/resultados.'],
                                            5  => ['Puente cognitivo hacia las ciencias exactas:', 'La estructura lógica de las lecciones me ayudó a comprender mejor la conexión entre la programación y la resolución de problemas de cálculo y física.'],
                                            6  => ['Autonomía en el aprendizaje:', 'Los recursos didácticos y guías descargables me permitieron avanzar a mi propio ritmo y resolver ejercicios de forma más independiente.'],
                                            7  => ['Motivación académica:', 'La dinámica interactiva de la plataforma incrementó mi interés y motivación por superar los retos planteados en la materia.'],
                                            8  => ['Reducción del riesgo de reprobación:', 'Considero que el uso constante de este curso de reforzamiento mejora significativamente mis probabilidades de aprobar con éxito las evaluaciones de la asignatura.'],
                                            9  => ['Satisfacción general con la plataforma:', 'La interfaz, el diseño y la distribución de las 5 lecciones del módulo resultaron intuitivas y agradables de utilizar.'],
                                            10 => ['Pertinencia institucional:', 'Recomendaría la implementación oficial de esta herramienta interactiva como apoyo obligatorio para los estudiantes de nuevo ingreso.'],
                                        ];
                                        foreach ($criteriosFisica as $num => [$tituloCrit, $descCrit]):
                                        ?>
                                            <tr>
                                                <td class="text-center fw-bold"><?= $num ?></td>
                                                <td><strong><?= e($tituloCrit) ?></strong> <?= e($descCrit) ?></td>
                                                <?php for ($val = 5; $val >= 1; $val--): ?>
                                                    <td class="text-center">
                                                        <input type="radio" class="fis-likert-radio" name="fis_likert_<?= $num ?>" value="<?= $val ?>" aria-label="Pregunta <?= $num ?> opción <?= $val ?>">
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
                                    <label for="fisComment1" class="form-label small fw-semibold mb-1">1. ¿Qué fue lo que más te gustó de los simuladores visuales y los retos de este módulo para comprender la materia?</label>
                                    <textarea id="fisComment1" class="form-control form-control-sm" rows="2"></textarea>
                                </div>
                                <div class="mb-2">
                                    <label for="fisComment2" class="form-label small fw-semibold mb-1">2. ¿En qué aspectos consideras que la plataforma o las lecciones podrían mejorar para facilitar aún más tu aprendizaje?</label>
                                    <textarea id="fisComment2" class="form-control form-control-sm" rows="2"></textarea>
                                </div>
                                <div>
                                    <label for="fisComment3" class="form-label small fw-semibold mb-1">3. Comentarios, observaciones o sugerencias adicionales:</label>
                                    <textarea id="fisComment3" class="form-control form-control-sm" rows="2"></textarea>
                                </div>
                                <p class="text-xs text-muted fst-italic mt-2 mb-0">
                                    ¡Agradecemos tu valiosa participación para el desarrollo y mejora continua de este proyecto de investigación!
                                </p>
                            </div>

                            <div id="fisSurveyStatusAlert" class="d-none"></div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                                <button type="submit" class="btn btn-teal fw-bold px-4">
                                    Enviar Encuesta y Validar Progreso ✓
                                </button>

                                <!-- Botón deshabilitado que cambia a habilitado al completar la encuesta -->
                                <button type="button" id="fisBtnDownloadCertSurvey" class="btn btn-secondary fw-bold px-4 opacity-75" disabled>
                                    🔒 Descargar Certificado
                                </button>
                            </div>
                        </form>
                    </article>

                    <!-- =====================================================
                         MÓDULO DE CIERRE · ITEM 2: CERTIFICADO DE ACREDITACIÓN
                         ===================================================== -->
                    <article class="fis-content-pane" data-fis-pane="cierre-certificado">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="fis-type-badge fis-badge-closing">🎓 Módulo de Cierre · Acreditación de Física</span>
                            <span class="text-muted small font-monospace">Folio: <?= e($folioCertificado) ?></span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Certificado de Acreditación: Física (Causa, Efecto y Simulación)</h2>

                        <div id="fisCertLockNotice" class="alert alert-warning d-flex align-items-center gap-2 py-2 px-3 small mb-3">
                            <span>🔒</span>
                            <div>
                                <strong>Requisito previo:</strong> Para desbloquear el botón <strong>"Descargar Certificado"</strong>, primero envía la <button type="button" class="btn btn-link btn-sm p-0 align-baseline fw-bold text-teal" data-fis-goto="cierre-encuesta">Encuesta de Satisfacción e Impacto</button>.
                            </div>
                        </div>

                        <div class="p-4 rounded-4 bg-white text-center shadow-sm mb-4" style="border: 6px double #0f766e;">
                            <div class="text-uppercase text-xs tracking-wider text-teal fw-bold mb-1">
                                Instituto Tecnológico de Ciudad Victoria · Lógica Visual ABP
                            </div>
                            <h3 class="h5 fw-bold text-dark mb-3">CERTIFICADO DE ACREDITACIÓN</h3>
                            <p class="small text-secondary mb-1">Se otorga el presente reconocimiento a:</p>
                            <div class="h3 fw-bold text-teal my-2"><?= e($nombreAlumno) ?></div>
                            <p class="small text-secondary max-w-2xl mx-auto mb-3">
                                Por haber acreditado satisfactoriamente las 5 lecciones interactivas, simuladores físicos y retos de Aprendizaje Basado en Problemas del módulo:
                            </p>
                            <div class="d-inline-block px-4 py-2 rounded-3 fw-bold h5 mb-3" style="background:#f0fdfa; border:2px solid #0d9488; color:#0f766e;">
                                <?= e($moduloCompletado) ?>
                            </div>
                            <div class="d-flex flex-wrap justify-content-around align-items-center small text-muted pt-3 border-top mt-2">
                                <div><strong>Fecha de finalización:</strong> <?= e($fechaActual) ?></div>
                                <div><strong>Folio de verificación:</strong> <span class="font-monospace"><?= e($folioCertificado) ?></span></div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-fis-goto="cierre-encuesta">← Volver a la Encuesta</button>
                            <button type="button" id="fisBtnDownloadCertPane" class="btn btn-secondary fw-bold px-4 opacity-75" disabled>
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
    <div class="modal fade" id="fisYoutubeModal" tabindex="-1" aria-labelledby="fisYoutubeModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
                <div class="modal-header fis-modal-header py-3 px-4">
                    <div>
                        <span class="badge bg-emerald text-white fw-bold mb-1" id="fisYoutubeModalChannel" style="background:#10b981;">Canal Oficial</span>
                        <h3 class="modal-title h6 fw-bold mb-0 text-white" id="fisYoutubeModalTitle">Reproductor de Vídeo</h3>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0 bg-black">
                    <div class="ratio ratio-16x9">
                        <iframe id="fisYoutubeIframe"
                                src=""
                                title="Reproductor de video de Física en YouTube"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen></iframe>
                    </div>
                </div>
                <div class="modal-footer bg-light d-flex flex-wrap justify-content-between align-items-center px-4 py-3">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="https://www.youtube.com/watch?v=IrTeyyzerjI" id="fisYoutubeDirectLink" target="_blank" rel="noopener noreferrer" class="btn btn-danger btn-sm fw-bold px-3">
                            ▶ Abrir directamente en YouTube ↗
                        </a>
                        <button type="button" id="fisYoutubeAltBtn" class="btn btn-outline-teal btn-sm fw-semibold d-none">
                            🎬 Ver Video Alternativo
                        </button>
                        <span id="fisYoutubePlaylistWrap" class="d-none">
                            <a href="#" id="fisYoutubePlaylistLink" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm fw-semibold">
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

<?php
/**
 * Vista Principal del Curso: Cálculo Diferencial (Enfoque Lógico-Visual)
 * Diseño Split-Screen E-Learning (Sidebar Izquierdo ~30% / Panel Principal ~70%)
 *
 * Variables disponibles desde ModuloController::show():
 * @var \App\Models\Modulo $modulo
 * @var \App\Models\Inscripcion|null $inscripcion
 */

use App\Core\Auth;

$usuarioActual = Auth::user();

// =========================================================================
// PARÁMETROS DINÁMICOS DE SESIÓN PARA LA GENERACIÓN DEL CERTIFICADO PDF
// Aquí se inyectan los datos de la sesión activa del alumno autenticado:
//   - $nombreAlumno     (Nombre_Alumno)     : Tomado de Auth::user()->nombre
//   - $moduloCompletado (Modulo_Completado) : "Cálculo Diferencial (Enfoque Lógico-Visual)"
//   - $fechaActual      (Fecha)             : Fecha de finalización en formato d/m/Y
// =========================================================================
$nombreAlumno     = $usuarioActual !== null ? $usuarioActual->nombre : 'Johan Gael Castillo Gonzalez';
$moduloCompletado = 'Cálculo Diferencial (Enfoque Lógico-Visual)';
$fechaActual      = ($inscripcion !== null && $inscripcion->finalizadoEn !== null)
    ? fecha($inscripcion->finalizadoEn, 'd/m/Y')
    : date('d/m/Y');
$folioCertificado = sprintf(
    'LV-CD-%05d-%04d',
    $inscripcion !== null ? $inscripcion->id : 2,
    $usuarioActual !== null ? $usuarioActual->id : 1
);
?>

<div class="cd-elearning-wrapper" id="calculoDiferencialApp">
    <!-- Contenedor de metadatos dinámicos de sesión para generación del PDF -->
    <div id="cdCertificateData"
         class="d-none"
         data-nombre-alumno="<?= e($nombreAlumno) ?>"
         data-modulo-completado="<?= e($moduloCompletado) ?>"
         data-fecha-emision="<?= e($fechaActual) ?>"
         data-folio="<?= e($folioCertificado) ?>"></div>

    <!-- Encabezado Superior Compacto del Curso -->
    <section class="bg-navy text-white py-4 position-relative border-bottom border-navy-light">
        <div class="bg-grid position-absolute top-0 start-0 w-100 h-100 pointer-events-none"></div>
        <div class="container-fluid max-w-7xl mx-auto px-3 px-md-4 position-relative z-1">
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?= url('/modulos') ?>" class="text-slate-400 text-decoration-none">Módulos Interactivos</a></li>
                    <li class="breadcrumb-item active text-cyan" aria-current="page">Cálculo Diferencial</li>
                </ol>
            </nav>

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="module-icon-box bg-indigo-subtle text-indigo fs-3">📈</div>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <h1 class="h3 fw-bold text-white mb-0">Cálculo Diferencial (Enfoque Lógico-Visual)</h1>
                            <span class="badge bg-cyan text-navy fw-bold">Nivel Inicial · ABP</span>
                        </div>
                        <p class="small text-slate-300 mb-0 max-w-4xl">
                            El objetivo de este módulo es evitar que el alumno memorice fórmulas, obligándolo a interactuar con simuladores de iteración y cambio (con condiciones lógicas de parada).
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <?php if (Auth::check() && $inscripcion === null): ?>
                        <form action="<?= url('/modulos/' . e($modulo->slug) . '/inscripcion') ?>" method="POST" class="m-0">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-cyan btn-sm fw-bold px-3 shadow-cyan">
                                Inscribirme al curso ▷
                            </button>
                        </form>
                    <?php elseif (Auth::check() && $inscripcion !== null && !$inscripcion->finalizado()): ?>
                        <form action="<?= url('/modulos/' . e($modulo->slug) . '/avance') ?>" method="POST" class="m-0">
                            <?= csrf_field() ?>
                            <input type="hidden" name="accion" value="finalizar">
                            <button type="submit" class="btn btn-outline-info btn-sm fw-bold px-3">
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
        <div class="cd-split-layout">

            <!-- =============================================================
                 1. PANEL LATERAL IZQUIERDO (30%): RUTA DE APRENDIZAJE Y NAVEGACIÓN
                 ============================================================= -->
            <aside class="cd-sidebar-panel" aria-label="Ruta de Aprendizaje y Navegación">
                <div class="cd-sidebar-card">
                    <div class="cd-sidebar-header">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-uppercase fw-bold text-xs tracking-wider text-info">Ruta de Aprendizaje</span>
                            <span class="badge bg-info-subtle text-info rounded-pill" id="cdGlobalProgressText">1/22 elementos (5%)</span>
                        </div>
                        <h2 class="h6 fw-bold mb-2">Navegación del Módulo</h2>
                        <div class="progress" style="height: 7px;">
                            <div class="progress-bar bg-info" id="cdGlobalProgressBar" role="progressbar" style="width: 5%"></div>
                        </div>
                    </div>

                    <div class="accordion cd-lesson-accordion" id="cdSidebarAccordion">
                        <!-- LECCIÓN 1 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="cdHeadL1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#cdCollapseL1" aria-expanded="true" aria-controls="cdCollapseL1">
                                    <div>
                                        Lección 1: Funciones como Entradas y Salidas (Cajas Negras)
                                        <span class="cd-lesson-meta" id="cdLessonCount-1">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="cdCollapseL1" class="accordion-collapse collapse show" aria-labelledby="cdHeadL1">
                                <ul class="cd-nav-list">
                                    <li>
                                        <button type="button" class="cd-nav-item is-active" data-cd-item="l1-lectura" data-cd-lesson="1" data-cd-type="lectura">
                                            <span class="cd-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Variables, Dominio y Rango</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l1-video" data-cd-lesson="1" data-cd-type="video"
                                                data-video-title="Lección 1: Visualizando las funciones — La esencia del cálculo"
                                                data-video-channel="Canal: 3Blue1Brown Español"
                                                data-video-embed="https://www.youtube.com/embed/WUvTyaaNkzM"
                                                data-video-url="https://youtu.be/WUvTyaaNkzM"
                                                data-video-playlist="https://youtube.com/playlist?list=PLZHQObOWTQDMsr9K-rj53DwVRMYO3t5Yr&si=IJ7d68_BWKl7_vsz">
                                            <span class="cd-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • La esencia del cálculo (Pop-up YouTube)</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l1-simulador" data-cd-lesson="1" data-cd-type="simulador">
                                            <span class="cd-nav-icon">⚙</span>
                                            <span><strong>Simulador</strong> • Entorno interactivo: Inputs y Outputs en tiempo real</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l1-pdf" data-cd-lesson="1" data-cd-type="pdf">
                                            <span class="cd-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • gu_a_1_mapeo_de_funciones.pdf</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- LECCIÓN 2 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="cdHeadL2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cdCollapseL2" aria-expanded="false" aria-controls="cdCollapseL2">
                                    <div>
                                        Lección 2: Límites y Aproximaciones (Ciclos e Iteraciones)
                                        <span class="cd-lesson-meta" id="cdLessonCount-2">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="cdCollapseL2" class="accordion-collapse collapse" aria-labelledby="cdHeadL2">
                                <ul class="cd-nav-list">
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l2-lectura" data-cd-lesson="2" data-cd-type="lectura">
                                            <span class="cd-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Continuidad, límites laterales y Valor intermedio</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l2-video" data-cd-lesson="2" data-cd-type="video"
                                                data-video-title="Lección 2: El límite como un ciclo de aproximación continua — Una fantástica introducción a los límites"
                                                data-video-channel="Canal: BlueDot"
                                                data-video-embed="https://www.youtube.com/embed/FHLsTqxW9uc"
                                                data-video-url="https://youtu.be/FHLsTqxW9uc"
                                                data-video-playlist="https://youtube.com/playlist?list=PLuph9OrdxiK6tgoHLi4AHwo6T40BQFWF3&si=XOS0TDBqMnppqdGq">
                                            <span class="cd-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • Una fantástica introducción a los límites (Pop-up YouTube)</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l2-reto" data-cd-lesson="2" data-cd-type="reto">
                                            <span class="cd-nav-icon">⚡</span>
                                            <span><strong>Reto ABP</strong> • Programar condición de paro</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l2-pdf" data-cd-lesson="2" data-cd-type="pdf">
                                            <span class="cd-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • gu_a_2_algoritmo_de_l_mites.pdf</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- LECCIÓN 3 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="cdHeadL3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cdCollapseL3" aria-expanded="false" aria-controls="cdCollapseL3">
                                    <div>
                                        Lección 3: La Derivada como Razón de Cambio
                                        <span class="cd-lesson-meta" id="cdLessonCount-3">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="cdCollapseL3" class="accordion-collapse collapse" aria-labelledby="cdHeadL3">
                                <ul class="cd-nav-list">
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l3-lectura" data-cd-lesson="3" data-cd-type="lectura">
                                            <span class="cd-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Recta secante vs. Recta tangente</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l3-video" data-cd-lesson="3" data-cd-type="video"
                                                data-video-title="Lección 3: Visualizando el cambio — La paradoja de la derivada | La esencia del cálculo, cap. 2"
                                                data-video-channel="Canal: 3Blue1Brown Español"
                                                data-video-embed="https://www.youtube.com/embed/byYkHqabnKM"
                                                data-video-url="https://youtu.be/byYkHqabnKM"
                                                data-video-playlist="https://youtube.com/playlist?list=PLZHQObOWTQDMsr9K-rj53DwVRMYO3t5Yr&si=IJ7d68_BWKl7_vsz">
                                            <span class="cd-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • La paradoja de la derivada (Pop-up YouTube)</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l3-simulador" data-cd-lesson="3" data-cd-type="simulador">
                                            <span class="cd-nav-icon">⚙</span>
                                            <span><strong>Simulador</strong> • Deslizador visual (reduciendo h a 0)</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l3-pdf" data-cd-lesson="3" data-cd-type="pdf">
                                            <span class="cd-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • gu_a_3_concepto_visual_de_la_derivada.pdf</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- LECCIÓN 4 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="cdHeadL4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cdCollapseL4" aria-expanded="false" aria-controls="cdCollapseL4">
                                    <div>
                                        Lección 4: Reglas de Derivación (Reconocimiento de Patrones)
                                        <span class="cd-lesson-meta" id="cdLessonCount-4">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="cdCollapseL4" class="accordion-collapse collapse" aria-labelledby="cdHeadL4">
                                <ul class="cd-nav-list">
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l4-lectura" data-cd-lesson="4" data-cd-type="lectura">
                                            <span class="cd-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Regla de la cadena, producto y cociente</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l4-video" data-cd-lesson="4" data-cd-type="video"
                                                data-video-title="Lección 4: Patrones lógicos en las derivadas — Fórmulas de derivación explicadas con geometría"
                                                data-video-channel="Canal: 3Blue1Brown Español"
                                                data-video-embed="https://www.youtube.com/embed/nQruR83shIA"
                                                data-video-url="https://youtu.be/nQruR83shIA"
                                                data-video-playlist="https://youtube.com/playlist?list=PLZHQObOWTQDMsr9K-rj53DwVRMYO3t5Yr&si=IJ7d68_BWKl7_vsz">
                                            <span class="cd-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • Fórmulas de derivación explicadas con geometría (Pop-up YouTube)</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l4-reto" data-cd-lesson="4" data-cd-type="reto">
                                            <span class="cd-nav-icon">⚡</span>
                                            <span><strong>Reto ABP</strong> • Bloques lógicos para regla de la cadena</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l4-pdf" data-cd-lesson="4" data-cd-type="pdf">
                                            <span class="cd-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • gu_a_4_rbol_de_derivaci_n.pdf</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- LECCIÓN 5 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="cdHeadL5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cdCollapseL5" aria-expanded="false" aria-controls="cdCollapseL5">
                                    <div>
                                        Lección 5: Optimización de Problemas (Máximos y Mínimos)
                                        <span class="cd-lesson-meta" id="cdLessonCount-5">0/4 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="cdCollapseL5" class="accordion-collapse collapse" aria-labelledby="cdHeadL5">
                                <ul class="cd-nav-list">
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l5-lectura" data-cd-lesson="5" data-cd-type="lectura">
                                            <span class="cd-nav-icon">📖</span>
                                            <span><strong>Lectura</strong> • Puntos críticos aplicados a casos reales</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l5-video" data-cd-lesson="5" data-cd-type="video"
                                                data-video-title="Lección 5: Optimización — Resolviendo problemas del mundo real"
                                                data-video-channel="Recurso Audiovisual de Optimización"
                                                data-video-embed="https://www.youtube.com/embed/KYvTWs54EgE"
                                                data-video-url="https://youtu.be/KYvTWs54EgE"
                                                data-video-playlist="">
                                            <span class="cd-nav-icon">▶</span>
                                            <span><strong>Vídeo</strong> • Optimización: Resolviendo problemas del mundo real (Pop-up YouTube)</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l5-reto" data-cd-lesson="5" data-cd-type="reto">
                                            <span class="cd-nav-icon">⚡</span>
                                            <span><strong>Reto ABP</strong> • Maximizar volumen de caja (Restricciones)</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="l5-pdf" data-cd-lesson="5" data-cd-type="pdf">
                                            <span class="cd-nav-icon">📄</span>
                                            <span><strong>Guía PDF</strong> • gu_a_5_algoritmo_de_optimizaci_n.pdf</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- MÓDULO DE CIERRE Y CERTIFICACIÓN -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="cdHeadL6">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cdCollapseL6" aria-expanded="false" aria-controls="cdCollapseL6">
                                    <div>
                                        Módulo de Cierre y Certificación
                                        <span class="cd-lesson-meta" id="cdLessonCount-6">0/2 elementos</span>
                                    </div>
                                </button>
                            </h3>
                            <div id="cdCollapseL6" class="accordion-collapse collapse" aria-labelledby="cdHeadL6">
                                <ul class="cd-nav-list">
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="cierre-encuesta" data-cd-lesson="6" data-cd-type="encuesta">
                                            <span class="cd-nav-icon">📊</span>
                                            <span><strong>Encuesta</strong> • Satisfacción e Impacto del Módulo (Escala Likert)</span>
                                            <span class="cd-nav-check">✓</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="cd-nav-item" data-cd-item="cierre-certificado" data-cd-lesson="6" data-cd-type="certificado">
                                            <span class="cd-nav-icon">🎓</span>
                                            <span><strong>Certificado</strong> • Descarga de acreditación</span>
                                            <span class="cd-nav-check">✓</span>
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
            <div class="cd-main-panel" id="cdMainWorkspace">
                <div class="cd-workspace-card">

                    <!-- =====================================================
                         LECCIÓN 1 · ITEM 1: LECTURA (Variables, Dominio y Rango)
                         ===================================================== -->
                    <article class="cd-content-pane is-active" data-cd-pane="l1-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-reading">📖 Lección 1 · Lectura</span>
                            <span class="text-muted small">Tema: Relación entre variables dependientes e independientes, dominio y rango</span>
                        </div>

                        <h2 class="h3 fw-bold mb-3">Lección 1: Funciones como Entradas y Salidas (Cajas Negras)</h2>

                        <p class="text-secondary leading-relaxed mb-3">
                            La relación fundamental radica en que la <strong>variable independiente</strong> (usualmente <span class="math-inline">x</span>) es el <em>input</em> que se controla o elige libremente, mientras que la <strong>variable dependiente</strong> (usualmente <span class="math-inline">y</span> o <span class="math-inline">f(x)</span>) es el <em>output</em> cuyo valor resulta de aplicar una regla de correspondencia a la independiente. El <strong>dominio</strong> es el conjunto de todos los valores válidos que puede tomar la variable independiente, y el <strong>rango</strong> es el conjunto de los valores correspondientes que toma la variable dependiente tras ser evaluada.
                        </p>

                        <p class="text-secondary leading-relaxed mb-4">
                            En una función, a cada elemento del dominio le corresponde exactamente un elemento del rango, lo que establece una relación unívoca donde el valor de <span class="math-inline">y</span> depende completamente de <span class="math-inline">x</span>. Gráficamente, el dominio se representa en el eje horizontal (<span class="math-inline">x</span>) y el rango en el eje vertical (<span class="math-inline">y</span>), definiendo así los límites de entrada y salida de la relación matemática.
                        </p>

                        <!-- Tarjetas de los 4 conceptos fundamentales -->
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-3 border bg-light h-100">
                                    <div class="fw-bold text-primary mb-1">🔹 Variable Independiente (<span class="math-inline">x</span>)</div>
                                    <p class="small text-secondary mb-0">Input que no depende de otras variables dentro del contexto; se fija libremente.</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-3 border bg-light h-100">
                                    <div class="fw-bold text-indigo mb-1">🔹 Variable Dependiente (<span class="math-inline">y = f(x)</span>)</div>
                                    <p class="small text-secondary mb-0">Output cuyo valor está determinado por la variable independiente.</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-3 border bg-light h-100">
                                    <div class="fw-bold text-teal mb-1">🔹 Dominio (<span class="math-inline">D<sub>f</sub></span>)</div>
                                    <p class="small text-secondary mb-0">Conjunto de todos los valores posibles de <span class="math-inline">x</span> que hacen válida la función.</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-3 border bg-light h-100">
                                    <div class="fw-bold text-purple mb-1">🔹 Rango (<span class="math-inline">R<sub>f</sub></span>)</div>
                                    <p class="small text-secondary mb-0">Conjunto de valores resultantes de <span class="math-inline">y</span> obtenidos al sustituir el dominio en la regla de la función.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Diagrama Lógico de Caja Negra -->
                        <div class="cd-graph-card mb-4">
                            <div class="small fw-bold text-uppercase text-muted mb-2">Modelo Lógico-Visual de una Función (Caja Negra)</div>
                            <svg viewBox="0 0 600 130" class="w-100" style="max-width: 560px;" role="img" aria-label="Diagrama de Caja Negra de una Función">
                                <rect x="15" y="35" width="145" height="60" rx="10" fill="#e0f2fe" stroke="#0284c7" stroke-width="2"/>
                                <text x="87" y="60" text-anchor="middle" font-size="13" font-weight="bold" fill="#0369a1">INPUT: x</text>
                                <text x="87" y="80" text-anchor="middle" font-size="11" fill="#0c4a6e">Dominio (Independiente)</text>

                                <line x1="160" y1="65" x2="215" y2="65" stroke="#0284c7" stroke-width="2.5"/>
                                <polygon points="215,60 225,65 215,70" fill="#0284c7"/>

                                <rect x="225" y="22" width="150" height="86" rx="14" fill="#0f172a" stroke="#38bdf8" stroke-width="2"/>
                                <text x="300" y="55" text-anchor="middle" font-size="14" font-weight="bold" fill="#22d3ee">CAJA NEGRA</text>
                                <text x="300" y="76" text-anchor="middle" font-size="13" font-family="Georgia, serif" font-style="italic" fill="#f8fafc">Regla f(x)</text>
                                <text x="300" y="95" text-anchor="middle" font-size="10" fill="#94a3b8">Transformación lógica</text>

                                <line x1="375" y1="65" x2="430" y2="65" stroke="#10b981" stroke-width="2.5"/>
                                <polygon points="430,60 440,65 430,70" fill="#10b981"/>

                                <rect x="440" y="35" width="145" height="60" rx="10" fill="#d1fae5" stroke="#059669" stroke-width="2"/>
                                <text x="512" y="60" text-anchor="middle" font-size="13" font-weight="bold" fill="#065f46">OUTPUT: y = f(x)</text>
                                <text x="512" y="80" text-anchor="middle" font-size="11" fill="#064e3b">Rango (Dependiente)</text>
                            </svg>
                        </div>

                        <div class="d-flex justify-content-end pt-3 border-top">
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l1-video">
                                Siguiente: Vídeo La esencia del cálculo ▶
                            </button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 1 · ITEM 2: VÍDEO (Pop-up YouTube)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l1-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-reading">▶ Lección 1 · Vídeo Pop-up</span>
                            <span class="text-muted small">Canal: 3Blue1Brown Español</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Visualizando las funciones: El algoritmo matemático</h2>
                        <p class="text-secondary mb-4">
                            Este recurso audiovisual se reproduce en una <strong>ventana emergente (Modal centrado)</strong> para mantener limpio tu espacio de trabajo. También puedes abrir el video o la lista de reproducción directamente en YouTube.
                        </p>

                        <div class="p-4 rounded-4 border bg-light text-center mb-4">
                            <div class="display-5 mb-2">🎬</div>
                            <h3 class="h5 fw-bold mb-1">La esencia del cálculo (Canal: 3Blue1Brown Español)</h3>
                            <p class="small text-muted mb-3">Enlace oficial: <code>https://youtu.be/WUvTyaaNkzM</code></p>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <button type="button" class="btn btn-cyan fw-bold px-4" data-open-video-modal="l1-video">
                                    ▶ Abrir Reproductor en Ventana Emergente
                                </button>
                                <a href="https://youtu.be/WUvTyaaNkzM" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary fw-semibold">
                                    Abrir en YouTube ↗
                                </a>
                                <a href="https://youtube.com/playlist?list=PLZHQObOWTQDMsr9K-rj53DwVRMYO3t5Yr&si=IJ7d68_BWKl7_vsz" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary">
                                    Playlist completa ↗
                                </a>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l1-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l1-simulador">Siguiente: Simulador Interactivo ⚙</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 1 · ITEM 3: SIMULADOR (Inputs y Outputs en tiempo real)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l1-simulador">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-sim">⚙ Lección 1 · Simulador Interactivo</span>
                            <span class="text-muted small">Inputs y Outputs en tiempo real</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Simulador: Funciones como Cajas Negras</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Objetivo:</strong> Ingresa diferentes valores (<em>Inputs</em>) y observa cómo la función transforma el resultado (<em>Outputs</em>) en tiempo real, identificando el patrón lógico y las restricciones del dominio.
                        </p>

                        <!-- Contenedor Área de Ejecución de Lógica -->
                        <div class="cd-logic-box mb-4">
                            <div class="cd-logic-topbar">
                                <span class="fw-bold text-cyan small">⚙ Área de ejecución de lógica · Mapeo Input → Output</span>
                                <span class="badge bg-info text-dark font-monospace" id="l1StepBadge">Paso 0 / 5</span>
                            </div>
                            <div class="cd-logic-body">
                                <div class="row g-3 align-items-end mb-4">
                                    <div class="col-12 col-md-6">
                                        <label for="l1FuncSelect" class="form-label small text-slate-300">Selecciona la regla de correspondencia <span class="math-inline">f(x)</span>:</label>
                                        <select id="l1FuncSelect" class="form-select bg-dark text-white border-secondary">
                                            <option value="lineal">f(x) = 2x + 3 (Polinomial Lineal · Dominio ℝ)</option>
                                            <option value="cuadratica">f(x) = x² - 1 (Polinomial Cuadrática · Dominio ℝ)</option>
                                            <option value="racional">f(x) = 1 / (x - 2) (Racional · Restricción x ≠ 2)</option>
                                            <option value="radical">f(x) = √(x + 4) (Radical · Restricción x ≥ -4)</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="l1InputX" class="form-label small text-slate-300">Ingresa un valor para el Input <span class="math-inline">x</span> (Dominio):</label>
                                        <input type="number" id="l1InputX" class="form-control bg-dark text-cyan border-secondary font-monospace fw-bold" value="1" step="0.5">
                                    </div>
                                </div>

                                <!-- Visualizador Caja Negra en Vivo -->
                                <div class="row g-3 text-center align-items-center mb-4 p-3 rounded-3" style="background: rgba(255,255,255,0.04);">
                                    <div class="col-12 col-md-4">
                                        <div class="p-3 rounded-3 border border-info">
                                            <div class="text-xs text-slate-400 text-uppercase">Input (Independiente)</div>
                                            <div class="fw-bold fs-5 text-white font-monospace mt-1" id="l1BoxInputDisplay">x = 1</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="p-3 rounded-3 bg-navy border border-secondary">
                                            <div class="text-xs text-cyan text-uppercase">Caja Negra (Regla)</div>
                                            <div class="fw-bold fs-6 text-warning font-monospace mt-1" id="l1BoxRuleDisplay">f(x) = 2x + 3</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="p-3 rounded-3 border border-success">
                                            <div class="text-xs text-slate-400 text-uppercase">Output (Dependiente)</div>
                                            <div class="fw-bold fs-5 text-cyan font-monospace mt-1" id="l1BoxOutputDisplay">y = 5</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                    <span class="small text-slate-400">Prueba valores libremente o ejecuta la batería de casos de prueba:</span>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-light" id="l1ResetBtn">Reiniciar</button>
                                        <button type="button" class="btn btn-sm btn-cyan fw-bold px-3" id="l1StepBtn">Ejecutar paso ▷</button>
                                    </div>
                                </div>

                                <div class="cd-logic-console" id="l1Console">
                                    <div class="text-cyan">&gt; [Tiempo Real] <strong>Input (Variable Independiente):</strong> x = 1</div>
                                    <div class="text-cyan">&gt; <strong>Regla de correspondencia:</strong> f(x) = 2x + 3 | Dominio: ℝ (-∞, +∞)</div>
                                    <div class="text-cyan">&gt; <strong>Output (Variable Dependiente):</strong> Sustituyendo x = 1: f(1) = 2(1) + 3 = 5</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l1-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l1-pdf">Siguiente: Guía PDF 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 1 · ITEM 4: GUÍA PDF (gu_a_1_mapeo_de_funciones.pdf)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l1-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-pdf">📄 Lección 1 · Guía Descargable</span>
                            <code class="small">gu_a_1_mapeo_de_funciones.pdf</code>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Guía 01: Mapa mental de mapeo de variables y clasificación de funciones básicas</h2>
                        <p class="text-secondary small mb-3">
                            Descarga o consulta directamente la guía de referencia con las restricciones lógicas del dominio (división entre cero, raíces pares negativas y logaritmos) y la clasificación de funciones.
                        </p>

                        <div class="p-3 rounded-3 border bg-light d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                            <div>
                                <div class="fw-bold">📄 gu_a_1_mapeo_de_funciones.pdf</div>
                                <div class="small text-muted">Mapa mental de mapeo de variables y clasificación de funciones básicas</div>
                            </div>
                            <a href="<?= asset('pdf/gu_a_1_mapeo_de_funciones.pdf') ?>" download="gu_a_1_mapeo_de_funciones.pdf" target="_blank" class="btn btn-cyan fw-bold btn-sm px-3">
                                ⬇ Descargar Guía PDF
                            </a>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered small align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Restricción Matemática</th>
                                        <th>Equivalente en Lógica (Condición de Paro)</th>
                                        <th>Ejemplo Visual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">División entre Cero</td>
                                        <td><code>if (denominador == 0) { ERROR }</code></td>
                                        <td>En <span class="math-inline">f(x) = 1 / (x - 2)</span>, la restricción lógica es <span class="math-inline">x ≠ 2</span>.</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Raíces Pares Negativas</td>
                                        <td><code>if (radicando &lt; 0) { ERROR }</code></td>
                                        <td>En <span class="math-inline">f(x) = √(x + 5)</span>, el sistema solo acepta <span class="math-inline">x ≥ -5</span>.</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Logaritmos de ≤ 0</td>
                                        <td><code>if (argumento &lt;= 0) { ERROR }</code></td>
                                        <td>En <span class="math-inline">f(x) = ln(x)</span>, la entrada debe ser estrictamente <span class="math-inline">x &gt; 0</span>.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l1-simulador">← Anterior: Simulador</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l2-lectura">Ir a Lección 2: Límites y Aproximaciones →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 2 · ITEM 1: LECTURA (Continuidad, límites laterales y Valor intermedio)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l2-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-reading">📖 Lección 2 · Lectura</span>
                            <span class="text-muted small">Tema: Concepto intuitivo de límite, límites laterales y continuidad</span>
                        </div>

                        <h2 class="h3 fw-bold mb-3">Lección 2: Límites y Aproximaciones (Ciclos e Iteraciones)</h2>

                        <h3 class="h5 fw-bold border-bottom pb-2 mb-3">Continuidad y límites laterales</h3>
                        <p class="text-secondary leading-relaxed mb-3">
                            En matemáticas, el término <em>continuo</em> tiene el mismo significado que el de uso cotidiano. Decir, de manera informal, que una función <span class="math-inline">f</span> es continua en <span class="math-inline">x = c</span> significa que no hay interrupción de la gráfica de <span class="math-inline">f</span> en <span class="math-inline">c</span>. Es decir, la gráfica es continua en <span class="math-inline">c</span>. En las siguientes figuras se muestran tres condiciones para <span class="math-inline">x</span> en los que la gráfica de <span class="math-inline">f</span> <em>no es continua</em>. En los demás puntos del intervalo <span class="math-inline">(a, b)</span>, la gráfica de <span class="math-inline">f</span> no sufre interrupciones y es <strong>continua</strong>.
                        </p>

                        <!-- LAS 3 GRÁFICAS DE DISCONTINUIDAD EN (a, b) (Páginas 2 y 3 del PDF) -->
                        <div class="row g-3 mb-4">
                            <!-- Figura 1: Discontinuidad evitable (f(c) no definida) -->
                            <div class="col-12 col-md-4">
                                <div class="cd-graph-card h-100">
                                    <svg viewBox="0 0 240 200" class="cd-graph-svg" aria-label="Discontinuidad evitable">
                                        <!-- Cuadrícula -->
                                        <g stroke="#e2e8f0" stroke-width="1">
                                            <line x1="20" y1="30" x2="220" y2="30"/><line x1="20" y1="60" x2="220" y2="60"/>
                                            <line x1="20" y1="90" x2="220" y2="90"/><line x1="20" y1="120" x2="220" y2="120"/>
                                            <line x1="75" y1="15" x2="75" y2="165"/><line x1="130" y1="15" x2="130" y2="165"/><line x1="185" y1="15" x2="185" y2="165"/>
                                        </g>
                                        <!-- Ejes -->
                                        <line x1="40" y1="165" x2="40" y2="15" stroke="#0f172a" stroke-width="1.5"/>
                                        <polygon points="40,10 36,18 44,18" fill="#0f172a"/>
                                        <line x1="15" y1="150" x2="225" y2="150" stroke="#0f172a" stroke-width="1.5"/>
                                        <polygon points="230,150 222,146 222,154" fill="#0f172a"/>
                                        <!-- Líneas punteadas rojas en a, c, b -->
                                        <line x1="75" y1="20" x2="75" y2="150" stroke="#b91c1c" stroke-width="1.2" stroke-dasharray="3,2"/>
                                        <line x1="130" y1="92" x2="130" y2="150" stroke="#b91c1c" stroke-width="1.2" stroke-dasharray="3,2"/>
                                        <line x1="185" y1="20" x2="185" y2="150" stroke="#b91c1c" stroke-width="1.2" stroke-dasharray="3,2"/>
                                        <!-- Curva -->
                                        <path d="M 75 72 Q 105 95, 130 92 Q 160 89, 185 103" fill="none" stroke="#0f172a" stroke-width="2"/>
                                        <!-- Huecos -->
                                        <circle cx="75" cy="72" r="4" fill="#ffffff" stroke="#0f172a" stroke-width="2"/>
                                        <circle cx="130" cy="92" r="4" fill="#ffffff" stroke="#0f172a" stroke-width="2"/>
                                        <circle cx="185" cy="103" r="4" fill="#ffffff" stroke="#0f172a" stroke-width="2"/>
                                        <!-- Etiquetas -->
                                        <text x="75" y="166" text-anchor="middle" font-family="Georgia, serif" font-style="italic" font-size="13">a</text>
                                        <text x="130" y="166" text-anchor="middle" font-family="Georgia, serif" font-style="italic" font-size="13">c</text>
                                        <text x="185" y="166" text-anchor="middle" font-family="Georgia, serif" font-style="italic" font-size="13">b</text>
                                    </svg>
                                    <div class="cd-graph-caption">Discontinuidad evitable</div>
                                    <div class="text-xs text-muted">1. <span class="math-inline">f(c)</span> NO está definida</div>
                                </div>
                            </div>

                            <!-- Figura 2: Discontinuidad inevitable (Salto: no existe el límite en x = c) -->
                            <div class="col-12 col-md-4">
                                <div class="cd-graph-card h-100">
                                    <svg viewBox="0 0 240 200" class="cd-graph-svg" aria-label="Discontinuidad inevitable por salto">
                                        <g stroke="#e2e8f0" stroke-width="1">
                                            <line x1="20" y1="30" x2="220" y2="30"/><line x1="20" y1="60" x2="220" y2="60"/>
                                            <line x1="20" y1="90" x2="220" y2="90"/><line x1="20" y1="120" x2="220" y2="120"/>
                                            <line x1="75" y1="15" x2="75" y2="165"/><line x1="130" y1="15" x2="130" y2="165"/><line x1="185" y1="15" x2="185" y2="165"/>
                                        </g>
                                        <line x1="40" y1="165" x2="40" y2="15" stroke="#0f172a" stroke-width="1.5"/>
                                        <polygon points="40,10 36,18 44,18" fill="#0f172a"/>
                                        <line x1="15" y1="150" x2="225" y2="150" stroke="#0f172a" stroke-width="1.5"/>
                                        <polygon points="230,150 222,146 222,154" fill="#0f172a"/>
                                        <line x1="75" y1="20" x2="75" y2="150" stroke="#b91c1c" stroke-width="1.2" stroke-dasharray="3,2"/>
                                        <line x1="130" y1="105" x2="130" y2="150" stroke="#b91c1c" stroke-width="1.2" stroke-dasharray="3,2"/>
                                        <line x1="185" y1="20" x2="185" y2="150" stroke="#b91c1c" stroke-width="1.2" stroke-dasharray="3,2"/>
                                        <!-- Dos ramas -->
                                        <path d="M 75 95 Q 95 55, 130 58" fill="none" stroke="#0f172a" stroke-width="2"/>
                                        <path d="M 130 105 Q 165 108, 185 72" fill="none" stroke="#0f172a" stroke-width="2"/>
                                        <circle cx="75" cy="95" r="4" fill="#ffffff" stroke="#0f172a" stroke-width="2"/>
                                        <circle cx="130" cy="58" r="4" fill="#0f172a"/>
                                        <circle cx="130" cy="105" r="4" fill="#ffffff" stroke="#0f172a" stroke-width="2"/>
                                        <circle cx="185" cy="72" r="4" fill="#ffffff" stroke="#0f172a" stroke-width="2"/>
                                        <text x="75" y="166" text-anchor="middle" font-family="Georgia, serif" font-style="italic" font-size="13">a</text>
                                        <text x="130" y="166" text-anchor="middle" font-family="Georgia, serif" font-style="italic" font-size="13">c</text>
                                        <text x="185" y="166" text-anchor="middle" font-family="Georgia, serif" font-style="italic" font-size="13">b</text>
                                    </svg>
                                    <div class="cd-graph-caption">Discontinuidad inevitable</div>
                                    <div class="text-xs text-muted">2. No existe el límite de <span class="math-inline">f(x)</span> en <span class="math-inline">x = c</span></div>
                                </div>
                            </div>

                            <!-- Figura 3: El límite existe pero no es igual a f(c) -->
                            <div class="col-12 col-md-4">
                                <div class="cd-graph-card h-100">
                                    <svg viewBox="0 0 240 200" class="cd-graph-svg" aria-label="Límite distinto de f(c)">
                                        <g stroke="#e2e8f0" stroke-width="1">
                                            <line x1="20" y1="30" x2="220" y2="30"/><line x1="20" y1="60" x2="220" y2="60"/>
                                            <line x1="20" y1="90" x2="220" y2="90"/><line x1="20" y1="120" x2="220" y2="120"/>
                                            <line x1="75" y1="15" x2="75" y2="165"/><line x1="130" y1="15" x2="130" y2="165"/><line x1="185" y1="15" x2="185" y2="165"/>
                                        </g>
                                        <line x1="40" y1="165" x2="40" y2="15" stroke="#0f172a" stroke-width="1.5"/>
                                        <polygon points="40,10 36,18 44,18" fill="#0f172a"/>
                                        <line x1="15" y1="150" x2="225" y2="150" stroke="#0f172a" stroke-width="1.5"/>
                                        <polygon points="230,150 222,146 222,154" fill="#0f172a"/>
                                        <line x1="75" y1="20" x2="75" y2="150" stroke="#b91c1c" stroke-width="1.2" stroke-dasharray="3,2"/>
                                        <line x1="130" y1="45" x2="130" y2="150" stroke="#b91c1c" stroke-width="1.2" stroke-dasharray="3,2"/>
                                        <line x1="185" y1="20" x2="185" y2="150" stroke="#b91c1c" stroke-width="1.2" stroke-dasharray="3,2"/>
                                        <!-- Curva con punto fuera -->
                                        <path d="M 75 78 Q 105 75, 130 45 Q 160 55, 185 95" fill="none" stroke="#0f172a" stroke-width="2"/>
                                        <circle cx="75" cy="78" r="4" fill="#ffffff" stroke="#0f172a" stroke-width="2"/>
                                        <circle cx="130" cy="45" r="4" fill="#ffffff" stroke="#0f172a" stroke-width="2"/>
                                        <circle cx="130" cy="105" r="4.5" fill="#0f172a"/>
                                        <circle cx="185" cy="95" r="4" fill="#ffffff" stroke="#0f172a" stroke-width="2"/>
                                        <text x="75" y="166" text-anchor="middle" font-family="Georgia, serif" font-style="italic" font-size="13">a</text>
                                        <text x="130" y="166" text-anchor="middle" font-family="Georgia, serif" font-style="italic" font-size="13">c</text>
                                        <text x="185" y="166" text-anchor="middle" font-family="Georgia, serif" font-style="italic" font-size="13">b</text>
                                    </svg>
                                    <div class="cd-graph-caption">Discontinuidad en x = c</div>
                                    <div class="text-xs text-muted">3. El límite existe, pero <span class="math-inline">lim f(x) ≠ f(c)</span></div>
                                </div>
                            </div>
                        </div>

                        <p class="text-secondary small mb-2">La continuidad en <span class="math-inline">x = c</span> no existe en las gráficas anteriores en virtud de las condiciones que se enuncian a continuación:</p>
                        <ol class="small text-secondary mb-4">
                            <li>La función <strong>NO</strong> está definida para <span class="math-inline">x = c</span>.</li>
                            <li>No existe el límite de <span class="math-inline">f(x)</span> en <span class="math-inline">x = c</span>.</li>
                            <li>El límite de <span class="math-inline">f(x)</span> en <span class="math-inline">x = c</span> existe, pero no es igual a <span class="math-inline">f(c)</span>.</li>
                        </ol>

                        <!-- Definición Formal de Continuidad (Páginas 3 y 4 del PDF) -->
                        <div class="p-3 rounded-3 border-start border-4 border-primary bg-light mb-4">
                            <h4 class="h6 fw-bold text-primary mb-2">Definición de continuidad en un punto <span class="math-inline">c</span></h4>
                            <p class="small mb-2">Una función <span class="math-inline">f</span> es continua en un punto <span class="math-inline">c</span> si se satisface:</p>
                            <div class="d-flex flex-wrap gap-4 align-items-center font-serif">
                                <div><strong>1.</strong> <span class="math-inline">f(c)</span> está definida</div>
                                <div>
                                    <strong>2.</strong>
                                    <span class="math-lim"><span class="math-lim-top">lím</span><span class="math-lim-sub">x → c</span></span>
                                    <span class="math-inline">f(x)</span> existe
                                </div>
                                <div>
                                    <strong>3.</strong>
                                    <span class="math-lim"><span class="math-lim-top">lím</span><span class="math-lim-sub">x → c</span></span>
                                    <span class="math-inline">f(x) = f(c)</span>
                                </div>
                            </div>
                        </div>

                        <p class="small text-secondary mb-4">
                            <strong>Continuidad en un intervalo abierto:</strong> Una función es <strong>continua en un intervalo abierto <span class="math-inline">(a, b)</span></strong> si es continua en cada punto del intervalo. Una función continua en la recta numérica de los números reales en el intervalo <span class="math-inline">(-∞, +∞)</span> es <strong>continua en todas partes</strong>.
                        </p>

                        <!-- Ejemplos de Continuidad (Página 4 del PDF) -->
                        <h4 class="h6 fw-bold mb-3">Ejemplos: Analizar la continuidad en el conjunto de los números reales</h4>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-4">
                                <div class="p-3 border rounded-3 h-100">
                                    <div class="math-block py-2 my-1">
                                        <span>a) <em>f(x)</em> =</span>
                                        <span class="math-frac"><span class="math-frac-num">1</span><span class="math-frac-den"><em>x</em></span></span>
                                    </div>
                                    <p class="small text-secondary mb-0"><strong>Solución:</strong> Definida en los reales distintos de cero. En <span class="math-inline">x = 0</span>, <span class="math-inline">f</span> tiene una <strong>discontinuidad inevitable</strong>.</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 border rounded-3 h-100">
                                    <div class="math-block py-2 my-1">
                                        <span>b) <em>g(x)</em> =</span>
                                        <span class="math-frac"><span class="math-frac-num"><em>x</em>² − 4</span><span class="math-frac-den"><em>x</em> − 2</span></span>
                                    </div>
                                    <p class="small text-secondary mb-0"><strong>Solución:</strong> Definida en los reales distintos de dos. En <span class="math-inline">x = 2</span>, <span class="math-inline">g</span> tiene una <strong>discontinuidad evitable</strong>.</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 border rounded-3 h-100">
                                    <div class="math-block py-2 my-1">
                                        <span>c) <em>h(x)</em> =</span>
                                        <span class="math-cases">
                                            <span class="math-cases-brace">{</span>
                                            <span class="math-cases-rows">
                                                <span><em>x</em>² si <em>x</em> ≤ 2</span>
                                                <span>8 − 2<em>x</em> si <em>x</em> &gt; 2</span>
                                            </span>
                                        </span>
                                    </div>
                                    <p class="small text-secondary mb-0"><strong>Solución:</strong> Continua en <span class="math-inline">(-∞, 2)</span> y <span class="math-inline">(2, +∞)</span>, y puesto que <span class="math-inline">lím<sub>x→2</sub> h(x) = 4</span>, es <strong>continua en ℝ</strong>.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Límites Laterales y Definición Formal ε-δ (Páginas 4 y 5 del PDF) -->
                        <h4 class="h6 fw-bold mb-2">Límites laterales y Límite bilateral</h4>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-3 border rounded-3 bg-light h-100">
                                    <div class="fw-bold small text-primary mb-1">Límite lateral por la derecha (<span class="math-inline">x → a<sup>+</sup></span>)</div>
                                    <div class="math-block py-2 my-2">
                                        <span class="math-lim"><span class="math-lim-top">lim</span><span class="math-lim-sub">x → a<sup>+</sup></span></span>
                                        <span><em>f(x) = L</em></span>
                                    </div>
                                    <p class="text-xs text-secondary mb-0">
                                        Si para cualquier <span class="math-inline">ε &gt; 0</span> existe un <span class="math-inline">δ &gt; 0</span> tal que:<br>
                                        <strong><span class="math-inline">0 &lt; x − a &lt; δ ⇒ |f(x) − L| &lt; ε</span></strong>
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-3 border rounded-3 bg-light h-100">
                                    <div class="fw-bold small text-indigo mb-1">Límite lateral por la izquierda (<span class="math-inline">x → a<sup>−</sup></span>)</div>
                                    <div class="math-block py-2 my-2">
                                        <span class="math-lim"><span class="math-lim-top">lim</span><span class="math-lim-sub">x → a<sup>−</sup></span></span>
                                        <span><em>f(x) = L</em></span>
                                    </div>
                                    <p class="text-xs text-secondary mb-0">
                                        Si para cualquier <span class="math-inline">ε &gt; 0</span> existe un <span class="math-inline">δ &gt; 0</span> tal que:<br>
                                        <strong><span class="math-inline">0 &lt; a − x &lt; δ ⇒ |f(x) − L| &lt; ε</span></strong>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 border bg-light mb-4">
                            <div class="fw-bold small mb-1">Teorema del Límite Bilateral:</div>
                            <p class="small text-secondary mb-2">Si <span class="math-inline">f</span> es una función y <span class="math-inline">c</span> y <span class="math-inline">L</span> son números reales, el límite de <span class="math-inline">f(x)</span> cuando <span class="math-inline">x</span> se aproxima a <span class="math-inline">c</span> es <span class="math-inline">L</span> si y sólo si:</p>
                            <div class="math-block py-2 my-0">
                                <span class="math-lim"><span class="math-lim-top">lim</span><span class="math-lim-sub">x → c<sup>+</sup></span></span>
                                <span><em>f(x)</em> =</span>
                                <span class="math-lim"><span class="math-lim-top">lim</span><span class="math-lim-sub">x → c<sup>−</sup></span></span>
                                <span><em>f(x) = L</em></span>
                            </div>
                        </div>

                        <!-- Ejemplos de Límites Laterales con sus Gráficas SVG (Páginas 5, 6, 7 y 8 del PDF) -->
                        <h4 class="h6 fw-bold mb-3">Ejemplos de Límites Laterales y Continuidad en Intervalo Cerrado <span class="math-inline">[a, b]</span></h4>
                        <div class="row g-3 mb-4">
                            <!-- Ejemplo a (Pág. 5-6) -->
                            <div class="col-12 col-md-6">
                                <div class="cd-graph-card text-start h-100">
                                    <div class="small fw-bold mb-1">Ejemplo a) Función escalonada en <span class="math-inline">x = 1</span></div>
                                    <div class="math-block py-1 px-2 fs-6">
                                        <span><em>f(x)</em> =</span>
                                        <span class="math-cases">
                                            <span class="math-cases-brace">{</span>
                                            <span class="math-cases-rows">
                                                <span>2 si <em>x</em> &lt; 1</span>
                                                <span>−1 si <em>x</em> = 1</span>
                                                <span>−3 si <em>x</em> &gt; 1</span>
                                            </span>
                                        </span>
                                    </div>
                                    <svg viewBox="0 0 220 140" class="cd-graph-svg my-2" style="max-width:220px;">
                                        <line x1="20" y1="65" x2="200" y2="65" stroke="#0f172a" stroke-width="1.3"/>
                                        <line x1="80" y1="10" x2="80" y2="130" stroke="#0f172a" stroke-width="1.3"/>
                                        <!-- y = 2 para x < 1 -->
                                        <line x1="25" y1="35" x2="105" y2="35" stroke="#15803d" stroke-width="2.5"/>
                                        <circle cx="105" cy="35" r="3.5" fill="#ffffff" stroke="#0f172a" stroke-width="1.8"/>
                                        <!-- (1, -1) -->
                                        <circle cx="105" cy="80" r="3.8" fill="#0f172a"/>
                                        <!-- y = -3 para x > 1 -->
                                        <line x1="105" y1="110" x2="185" y2="110" stroke="#15803d" stroke-width="2.5"/>
                                        <circle cx="105" cy="110" r="3.5" fill="#ffffff" stroke="#0f172a" stroke-width="1.8"/>
                                    </svg>
                                    <div class="text-xs text-secondary">
                                        <span class="math-inline">lim<sub>x→1⁻</sub> f(x) = 2</span> y <span class="math-inline">lim<sub>x→1⁺</sub> f(x) = −3</span>.<br>
                                        <strong>∴ <span class="math-inline">lim<sub>x→1</sub> f(x)</span> no existe</strong> puesto que <span class="math-inline">lim<sub>x→1⁻</sub> f(x) ≠ lim<sub>x→1⁺</sub> f(x)</span>.
                                    </div>
                                </div>
                            </div>

                            <!-- Continuidad en intervalo cerrado [a, b] (Pág. 8-9) -->
                            <div class="col-12 col-md-6">
                                <div class="cd-graph-card text-start h-100">
                                    <div class="small fw-bold mb-1">Continuidad en un intervalo cerrado <span class="math-inline">[a, b]</span></div>
                                    <p class="text-xs text-secondary mb-2">
                                        Continua en <span class="math-inline">(a, b)</span> y cumple <span class="math-inline">lim<sub>x→a⁺</sub> f(x) = f(a)</span> y <span class="math-inline">lim<sub>x→b⁻</sub> f(x) = f(b)</span>.
                                    </p>
                                    <div class="math-block py-1 px-2 fs-6">
                                        <span>Ejemplo: <em>f(x)</em> =</span>
                                        <span class="math-sqrt">√<span class="math-sqrt-radicand">1 − <em>x</em>²</span></span>
                                        <span class="ms-2 small">en [−1, 1]</span>
                                    </div>
                                    <p class="text-xs text-secondary mb-2">
                                        Como <span class="math-inline">lim<sub>x→−1⁺</sub> √(1−x²) = 0 = f(−1)</span> y <span class="math-inline">lim<sub>x→1⁻</sub> √(1−x²) = 0 = f(1)</span>, la función es continua en el intervalo cerrado <span class="math-inline">[−1, 1]</span>.
                                    </p>
                                    <div class="p-2 rounded bg-light text-xs text-secondary">
                                        <strong>Unión de polinomios en <span class="math-inline">[0, 4]</span>:</strong> Para <span class="math-inline">f(x) = 2x+3</span> (<span class="math-inline">0 ≤ x &lt; 1</span>) y <span class="math-inline">x³−6x²+9x+1</span> (<span class="math-inline">1 ≤ x ≤ 4</span>), en <span class="math-inline">x = 1</span> ambos límites laterales coinciden en <strong>5</strong>, por lo que es continua en <span class="math-inline">[0, 4]</span>.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Teorema del Valor Intermedio y Tabla Excel de Raíces (Páginas 9, 10 y 11 del PDF) -->
                        <h4 class="h6 fw-bold mb-2">Teorema del Valor Intermedio (Búsqueda de Ceros por Aproximación)</h4>
                        <p class="small text-secondary mb-3">
                            Si <span class="math-inline">f</span> es continua en el intervalo cerrado <span class="math-inline">[a, b]</span>, <span class="math-inline">f(a) ≠ f(b)</span> y <span class="math-inline">k</span> es cualquier número entre <span class="math-inline">f(a)</span> y <span class="math-inline">f(b)</span>, entonces existe al menos un número <span class="math-inline">c</span> en <span class="math-inline">[a, b]</span> tal que <span class="math-inline">f(c) = k</span>. Si <span class="math-inline">f(a)</span> y <span class="math-inline">f(b)</span> tienen signo distinto, garantiza al menos una raíz en <span class="math-inline">[a, b]</span>.
                        </p>

                        <div class="row g-3 align-items-center mb-4">
                            <div class="col-12 col-md-6">
                                <div class="cd-graph-card">
                                    <div class="small fw-bold mb-1">Ejemplo: <span class="math-inline">f(x) = x³ − 3x + 1</span> en <span class="math-inline">(0, 1)</span></div>
                                    <svg viewBox="0 0 240 190" class="cd-graph-svg" style="max-width:250px;">
                                        <g stroke="#e2e8f0" stroke-width="1">
                                            <line x1="20" y1="30" x2="220" y2="30"/><line x1="20" y1="65" x2="220" y2="65"/>
                                            <line x1="20" y1="100" x2="220" y2="100"/><line x1="20" y1="135" x2="220" y2="135"/>
                                            <line x1="50" y1="15" x2="50" y2="175"/><line x1="85" y1="15" x2="85" y2="175"/>
                                            <line x1="155" y1="15" x2="155" y2="175"/><line x1="190" y1="15" x2="190" y2="175"/>
                                        </g>
                                        <line x1="20" y1="135" x2="220" y2="135" stroke="#0f172a" stroke-width="1.5"/>
                                        <line x1="120" y1="175" x2="120" y2="15" stroke="#0f172a" stroke-width="1.5"/>
                                        <!-- Curva cúbica x^3 - 3x + 1 -->
                                        <path d="M 48 165 C 65 25, 85 25, 120 100 C 142 152, 165 170, 195 30" fill="none" stroke="#15803d" stroke-width="2.2"/>
                                        <circle cx="132" cy="135" r="4" fill="#e11d48"/>
                                        <text x="136" y="128" font-size="10" font-weight="bold" fill="#be123c">c ≈ 0.3473</text>
                                    </svg>
                                    <div class="text-xs text-muted">Cambio de signo entre <span class="math-inline">f(0) = 1</span> y <span class="math-inline">f(1) = −1</span></div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <!-- Tabla numérica de aproximación sucesiva (Página 11 del PDF) -->
                                <table class="cd-excel-table mx-auto">
                                    <thead>
                                        <tr class="cd-excel-formula-bar">
                                            <th colspan="2"><em>fx</em> &nbsp; <code>=C2^3-3*C2+1</code></th>
                                        </tr>
                                        <tr class="cd-excel-col-header">
                                            <th>C (x)</th>
                                            <th>D (f(x))</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="text-center bg-light">Teorema del valor intermedio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td class="text-end">0</td><td class="text-end">1</td></tr>
                                        <tr><td class="text-end">0,1</td><td class="text-end">0,701</td></tr>
                                        <tr><td class="text-end">0,2</td><td class="text-end">0,408</td></tr>
                                        <tr class="cd-excel-highlight"><td class="text-end">0,3</td><td class="text-end">0,127</td></tr>
                                        <tr class="cd-excel-highlight"><td class="text-end">0,4</td><td class="text-end">-0,136</td></tr>
                                        <tr class="cd-excel-highlight"><td class="text-end">0,34</td><td class="text-end">0,019304</td></tr>
                                        <tr class="cd-excel-highlight"><td class="text-end">0,35</td><td class="text-end">-0,007125</td></tr>
                                        <tr class="cd-excel-highlight"><td class="text-end">0,3472</td><td class="text-end">0,00025421</td></tr>
                                        <tr class="cd-excel-highlight"><td class="text-end">0,3473</td><td class="text-end">-9,61518E-06</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Verifica tu aprendizaje: Ejercicios 1 al 11 (Páginas 11 y 12 del PDF) -->
                        <div class="p-3 rounded-3 border bg-light mb-4">
                            <h4 class="h6 fw-bold mb-2">📝 Verifica tu aprendizaje (Ejercicios 1 al 11 del Módulo)</h4>
                            <p class="text-xs text-secondary mb-2"><strong>En los ejercicios 1 al 7 encuentre el límite indicado; en los ejercicios 8 al 11 analice la continuidad en el intervalo indicado:</strong></p>
                            <div class="row g-2 small font-serif">
                                <div class="col-12 col-md-6">1. <span class="math-inline">lim<sub>x→−3⁻</sub> [ x / √(x² − 9) ]</span></div>
                                <div class="col-12 col-md-6">2. <span class="math-inline">lim<sub>x→5⁺</sub> [ (x − 5) / (x² − 25) ]</span></div>
                                <div class="col-12 col-md-6">3. <span class="math-inline">lim<sub>x→3</sub> f(x)</span>, si <span class="math-inline">f(x) = { 3x+2 (x≤3) ; 17−2x (x&gt;3) }</span></div>
                                <div class="col-12 col-md-6">4. <span class="math-inline">lim<sub>x→1</sub> f(x)</span>, si <span class="math-inline">f(x) = { x³+1 (x&lt;1) ; ³√(21+x) (x≥1) }</span></div>
                                <div class="col-12 col-md-6">5. <span class="math-inline">lim<sub>x→1</sub> f(x)</span>, si <span class="math-inline">f(x) = { x (x≤1) ; 1−x² (x&gt;1) }</span></div>
                                <div class="col-12 col-md-6">6. <span class="math-inline">lim<sub>x→2</sub> f(x)</span>, si <span class="math-inline">f(x) = { x²−4x+6 (x&lt;2) ; −x²−4x (x≥2) }</span></div>
                                <div class="col-12 col-md-6">7. <span class="math-inline">lim<sub>x→2</sub> f(x)</span>, si <span class="math-inline">f(x) = { x²−4x+6 (x&lt;2) ; −x²+4x−2 (x≥2) }</span></div>
                                <div class="col-12 col-md-6">8. <span class="math-inline">f(x) = √(25 − x²)</span> en <span class="math-inline">[−5, 5]</span></div>
                                <div class="col-12 col-md-6">9. <span class="math-inline">g(x) = 4 − √(16 − x²)</span> en <span class="math-inline">[−4, 4]</span></div>
                                <div class="col-12 col-md-6">10. <span class="math-inline">h(x) = { 3−x (x≤0) ; 3+0.5x (x&gt;0) }</span> en <span class="math-inline">[−1, 2]</span></div>
                                <div class="col-12">11. <span class="math-inline">p(x) = 1 / (x² − 4)</span> en <span class="math-inline">[−1, 2]</span></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l1-pdf">← Lección 1</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l2-video">Siguiente: Vídeo de Límites ▶</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 2 · ITEM 2: VÍDEO (Pop-up YouTube)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l2-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-reading">▶ Lección 2 · Vídeo Pop-up</span>
                            <span class="text-muted small">Canal: BlueDot</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">El límite como un ciclo de aproximación continua</h2>
                        <p class="text-secondary mb-4">
                            Visualiza el video <strong>"Una fantástica introducción a los límites"</strong> en la ventana emergente o ábrelo directamente en YouTube.
                        </p>

                        <div class="p-4 rounded-4 border bg-light text-center mb-4">
                            <div class="display-5 mb-2">🎬</div>
                            <h3 class="h5 fw-bold mb-1">Una fantástica introducción a los límites (Canal: BlueDot)</h3>
                            <p class="small text-muted mb-3">Enlace oficial: <code>https://youtu.be/FHLsTqxW9uc</code></p>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <button type="button" class="btn btn-cyan fw-bold px-4" data-open-video-modal="l2-video">
                                    ▶ Abrir Reproductor en Ventana Emergente
                                </button>
                                <a href="https://youtu.be/FHLsTqxW9uc" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary fw-semibold">
                                    Abrir en YouTube ↗
                                </a>
                                <a href="https://youtube.com/playlist?list=PLuph9OrdxiK6tgoHLi4AHwo6T40BQFWF3&si=XOS0TDBqMnppqdGq" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary">
                                    Playlist completa ↗
                                </a>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l2-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l2-reto">Siguiente: Reto ABP Condición de Paro ⚡</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 2 · ITEM 3: RETO ABP (Programar condición de paro)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l2-reto">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-abp">⚡ Lección 2 · Reto ABP</span>
                            <span class="text-muted small">Ciclos de aproximación y condición de paro</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Reto ABP: Programar la "Condición de Paro" de un Límite</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Instrucción:</strong> Utilizando el Simulador Interactivo de Lógica, programa la "condición de paro" (<span class="math-inline">|x − c| ≤ ε</span>) de una variable <span class="math-inline">x</span> que se acerca infinitamente a <span class="math-inline">c = 2</span> en <span class="math-inline">g(x) = (x² − 4)/(x − 2)</span>, observando cómo <span class="math-inline">y</span> se aproxima al límite <span class="math-inline">L = 4</span>.
                        </p>

                        <div class="cd-logic-box mb-4">
                            <div class="cd-logic-topbar">
                                <span class="fw-bold text-cyan small">⚡ Área de ejecución de lógica · Ciclo while (|x - 2| &gt; ε)</span>
                                <span class="badge bg-info text-dark font-monospace" id="l2StepBadge">Iteración 0 / 5</span>
                            </div>
                            <div class="cd-logic-body">
                                <div class="row g-3 align-items-center mb-3">
                                    <div class="col-12 col-md-7">
                                        <label for="l2EpsilonSelect" class="form-label small text-slate-300 mb-1">Configura la tolerancia de paro <span class="math-inline">ε</span> (<code>if |x - 2| &lt;= ε break;</code>):</label>
                                        <select id="l2EpsilonSelect" class="form-select form-select-sm bg-dark text-cyan border-secondary font-monospace">
                                            <option value="0.01">ε = 0.01 (Paro en 3ra iteración)</option>
                                            <option value="0.001" selected>ε = 0.001 (Paro en 4ta iteración)</option>
                                            <option value="0.0001">ε = 0.0001 (Paro en 5ta iteración · Alta precisión)</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-5 d-flex justify-content-md-end gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-light" id="l2ResetBtn">Reiniciar</button>
                                        <button type="button" class="btn btn-sm btn-cyan fw-bold px-3" id="l2StepBtn">Ejecutar paso ▷</button>
                                    </div>
                                </div>

                                <div class="table-responsive mb-3">
                                    <table class="table table-dark table-sm table-bordered font-monospace text-xs align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Paso</th>
                                                <th>x⁻ (Izquierda)</th>
                                                <th>g(x⁻)</th>
                                                <th>x⁺ (Derecha)</th>
                                                <th>g(x⁺)</th>
                                                <th>Distancia |x-2|</th>
                                                <th>Estado Condición</th>
                                            </tr>
                                        </thead>
                                        <tbody id="l2IterTableBody"></tbody>
                                    </table>
                                </div>

                                <div class="cd-logic-console" id="l2Console">
                                    <span class="text-cyan">&gt;</span> Ciclo listo. Función: g(x) = (x² - 4)/(x - 2) cuando x → 2. Selecciona ε y presiona "Ejecutar paso".
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l2-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l2-pdf">Siguiente: Guía PDF 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 2 · ITEM 4: GUÍA PDF (gu_a_2_algoritmo_de_l_mites.pdf)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l2-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-pdf">📄 Lección 2 · Guía Descargable</span>
                            <code class="small">gu_a_2_algoritmo_de_l_mites.pdf</code>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Guía 02: Diagrama de flujo para determinar si un límite existe o diverge</h2>
                        <p class="text-secondary small mb-3">
                            Consulta el algoritmo de 3 pasos (Sustitución Directa → Estructura de Decisión → Ciclo de Reparación Algebraica para indeterminaciones <span class="math-inline">0/0</span>).
                        </p>

                        <div class="p-3 rounded-3 border bg-light d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                            <div>
                                <div class="fw-bold">📄 gu_a_2_algoritmo_de_l_mites.pdf</div>
                                <div class="small text-muted">Diagrama de flujo para determinar si un límite existe o diverge</div>
                            </div>
                            <a href="<?= asset('pdf/gu_a_2_algoritmo_de_l_mites.pdf') ?>" download="gu_a_2_algoritmo_de_l_mites.pdf" target="_blank" class="btn btn-cyan fw-bold btn-sm px-3">
                                ⬇ Descargar Guía PDF
                            </a>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l2-reto">← Anterior: Reto ABP</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l3-lectura">Ir a Lección 3: La Derivada →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 3 · ITEM 1: LECTURA (Recta secante vs. Recta tangente)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l3-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-reading">📖 Lección 3 · Lectura</span>
                            <span class="text-muted small">Tema: Velocidad instantánea, recta secante vs. recta tangente</span>
                        </div>

                        <h2 class="h3 fw-bold mb-3">Lección 3: La Derivada como Razón de Cambio</h2>

                        <p class="text-secondary leading-relaxed mb-3">
                            La diferencia fundamental entre la <strong>recta secante</strong> y la <strong>recta tangente</strong> radica en que la primera conecta dos puntos de una curva para representar una <strong>tasa de cambio promedio</strong>, mientras que la segunda toca la curva en un único punto para representar una <strong>tasa de cambio instantánea</strong>.
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-3 border bg-light h-100">
                                    <div class="fw-bold text-warning-emphasis mb-1">📐 Recta secante (Velocidad promedio)</div>
                                    <p class="small text-secondary mb-2">
                                        Corta la curva en <strong>dos puntos</strong> distintos. Su pendiente se calcula como <span class="math-inline">m<sub>sec</sub> = [f(x+h) − f(x)] / h</span> y representa la <strong>velocidad promedio</strong> de un objeto en un intervalo de tiempo.
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-3 border bg-light h-100">
                                    <div class="fw-bold text-success mb-1">🎯 Recta tangente (Velocidad instantánea)</div>
                                    <p class="small text-secondary mb-2">
                                        Toca la curva en <strong>exactamente un punto</strong>. Su pendiente es la derivada y representa la <strong>velocidad instantánea</strong> en un instante específico.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <p class="text-secondary leading-relaxed mb-3">
                            La <strong>velocidad instantánea</strong> se define como el límite de la velocidad promedio cuando el intervalo de tiempo tiende a cero; geométricamente, esto corresponde a la pendiente de la <strong>recta tangente</strong> a la curva de posición-tiempo en ese punto. A medida que los dos puntos de la secante se acercan, la secante se aproxima a la tangente, estableciendo la conexión entre el cálculo promedio y el instantáneo.
                        </p>

                        <div class="math-block mb-4">
                            <span><em>f'(x)</em> =</span>
                            <span class="math-lim"><span class="math-lim-top">lim</span><span class="math-lim-sub">h → 0</span></span>
                            <span class="math-frac">
                                <span class="math-frac-num"><em>f(x + h) − f(x)</em></span>
                                <span class="math-frac-den"><em>h</em></span>
                            </span>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l2-pdf">← Lección 2</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l3-video">Siguiente: Vídeo La paradoja de la derivada ▶</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 3 · ITEM 2: VÍDEO (Pop-up YouTube)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l3-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-reading">▶ Lección 3 · Vídeo Pop-up</span>
                            <span class="text-muted small">Canal: 3Blue1Brown Español</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Visualizando el cambio: Qué significa realmente derivar</h2>
                        <p class="text-secondary mb-4">
                            Reproduce el episodio <strong>"La paradoja de la derivada | La esencia del cálculo, cap. 2"</strong> en la ventana emergente o ábrelo en YouTube.
                        </p>

                        <div class="p-4 rounded-4 border bg-light text-center mb-4">
                            <div class="display-5 mb-2">🎬</div>
                            <h3 class="h5 fw-bold mb-1">La paradoja de la derivada | La esencia del cálculo, cap. 2</h3>
                            <p class="small text-muted mb-3">Enlace oficial: <code>https://youtu.be/byYkHqabnKM</code></p>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <button type="button" class="btn btn-cyan fw-bold px-4" data-open-video-modal="l3-video">
                                    ▶ Abrir Reproductor en Ventana Emergente
                                </button>
                                <a href="https://youtu.be/byYkHqabnKM" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary fw-semibold">
                                    Abrir en YouTube ↗
                                </a>
                                <a href="https://youtube.com/playlist?list=PLZHQObOWTQDMsr9K-rj53DwVRMYO3t5Yr&si=IJ7d68_BWKl7_vsz" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary">
                                    Playlist completa ↗
                                </a>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l3-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l3-simulador">Siguiente: Simulador Deslizador h → 0 ⚙</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 3 · ITEM 3: SIMULADOR (Deslizador visual reduciendo h a 0)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l3-simulador">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-sim">⚙ Lección 3 · Simulador Visual</span>
                            <span class="text-muted small">Reduciendo h → 0 en tiempo real</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Simulador: De Recta Secante a Recta Tangente (<span class="math-inline">h → 0</span>)</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Instrucción:</strong> Usa el control deslizante visual o presiona <strong>"Ejecutar paso"</strong> para disminuir la distancia <span class="math-inline">h</span> entre dos puntos en la curva hasta que la recta secante se convierta en recta tangente.
                        </p>

                        <div class="cd-logic-box mb-4">
                            <div class="cd-logic-topbar">
                                <span class="fw-bold text-cyan small">⚙ Área de ejecución de lógica · Límite geométrico h → 0</span>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-warning text-dark font-monospace" id="l3HBadge">h = 2.000</span>
                                    <span class="badge bg-info text-dark font-monospace" id="l3SlopeBadge">m_sec = 1.5000 (Exacta f'(2) = 1.0000)</span>
                                </div>
                            </div>
                            <div class="cd-logic-body">
                                <div class="row g-4 align-items-center mb-3">
                                    <div class="col-12 col-md-6">
                                        <div class="bg-white rounded-3 p-2 text-center">
                                            <svg viewBox="0 0 280 210" class="w-100" style="max-width:290px;" aria-label="Gráfica interactiva secante a tangente">
                                                <line x1="40" y1="190" x2="265" y2="190" stroke="#334155" stroke-width="1.5"/>
                                                <line x1="40" y1="190" x2="40" y2="15" stroke="#334155" stroke-width="1.5"/>
                                                <!-- Curva f(x) = 0.25 x^2 -->
                                                <path d="M 40 190 Q 130 190, 242 38" fill="none" stroke="#0284c7" stroke-width="2.5"/>
                                                <!-- Recta tangente de referencia punteada en P(2,1) -->
                                                <line x1="58" y1="184" x2="247" y2="121" stroke="#10b981" stroke-width="1.5" stroke-dasharray="4,3"/>
                                                <!-- Recta secante dinámica -->
                                                <line id="l3SecantLine" x1="58" y1="184" x2="247" y2="91" stroke="#f59e0b" stroke-width="2.5"/>
                                                <!-- Punto fijo P(2,1) -->
                                                <circle cx="130" cy="160" r="5" fill="#10b981"/>
                                                <text x="115" y="176" font-size="11" font-weight="bold" fill="#065f46">P(2, 1)</text>
                                                <!-- Punto móvil Q(2+h, f(2+h)) -->
                                                <circle id="l3MovingPoint" cx="220" cy="70" r="5.5" fill="#e11d48"/>
                                                <text id="l3MovingLabel" x="195" y="60" font-size="11" font-weight="bold" fill="#be123c">Q(2+2.00)</text>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="l3SliderH" class="form-label small text-slate-300">Deslizador de distancia <span class="math-inline">h</span> (de <span class="math-inline">2.0</span> a <span class="math-inline">0.001</span>):</label>
                                        <input type="range" class="form-range mb-3" id="l3SliderH" min="0.001" max="2.0" step="0.001" value="2.0">
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            <button type="button" class="btn btn-sm btn-outline-light" id="l3ResetBtn">Reiniciar (h = 2)</button>
                                            <button type="button" class="btn btn-sm btn-cyan fw-bold px-3" id="l3StepBtn">Ejecutar paso (Reducir h) ▷</button>
                                        </div>
                                        <p class="text-xs text-slate-400 mb-0">
                                            Observa cómo la línea amarilla (Recta Secante) se inclina gradualmente hasta superponerse con la línea verde punteada (Recta Tangente en <span class="math-inline">P</span>) cuando <span class="math-inline">h → 0</span>.
                                        </p>
                                    </div>
                                </div>

                                <div class="cd-logic-console" id="l3Console">
                                    <span class="text-cyan">&gt;</span> Estado inicial: h = 2.000. Mueve el deslizador o haz clic en "Ejecutar paso (Reducir h)".
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l3-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l3-pdf">Siguiente: Guía PDF 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 3 · ITEM 4: GUÍA PDF (gu_a_3_concepto_visual_de_la_derivada.pdf)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l3-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-pdf">📄 Lección 3 · Guía Descargable</span>
                            <code class="small">gu_a_3_concepto_visual_de_la_derivada.pdf</code>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Guía 03: Fichas visuales sobre la interpretación geométrica de la derivada</h2>
                        <div class="p-3 rounded-3 border bg-light d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                            <div>
                                <div class="fw-bold">📄 gu_a_3_concepto_visual_de_la_derivada.pdf</div>
                                <div class="small text-muted">Fichas visuales sobre la interpretación geométrica de la derivada</div>
                            </div>
                            <a href="<?= asset('pdf/gu_a_3_concepto_visual_de_la_derivada.pdf') ?>" download="gu_a_3_concepto_visual_de_la_derivada.pdf" target="_blank" class="btn btn-cyan fw-bold btn-sm px-3">
                                ⬇ Descargar Guía PDF
                            </a>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l3-simulador">← Anterior: Simulador</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l4-lectura">Ir a Lección 4: Reglas de Derivación →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 4 · ITEM 1: LECTURA (Regla de la cadena, producto y cociente)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l4-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-reading">📖 Lección 4 · Lectura</span>
                            <span class="text-muted small">Tema: Regla de la cadena, producto y cociente</span>
                        </div>

                        <h2 class="h3 fw-bold mb-3">Lección 4: Reglas de Derivación (Reconocimiento de Patrones)</h2>

                        <!-- 1. Regla de la Cadena -->
                        <div class="p-3 rounded-3 border mb-3">
                            <h3 class="h6 fw-bold text-primary mb-2">1. La Regla de la Cadena (Funciones Compuestas)</h3>
                            <p class="small text-secondary mb-2">
                                La <strong>regla de la cadena</strong> se utiliza para derivar <strong>funciones compuestas</strong> (una función dentro de otra), aplicando la fórmula:
                            </p>
                            <div class="math-block py-2 my-2">
                                <span class="math-frac"><span class="math-frac-num"><em>d</em></span><span class="math-frac-den"><em>dx</em></span></span>
                                <span><em>f(g(x)) = f'(g(x)) · g'(x)</em></span>
                            </div>
                            <p class="small text-secondary mb-0">
                                Esto implica derivar la función exterior dejando la interior intacta y multiplicar por la derivada de la función interior.
                            </p>
                        </div>

                        <!-- 2. Regla del Producto -->
                        <div class="p-3 rounded-3 border mb-3">
                            <h3 class="h6 fw-bold text-indigo mb-2">2. La Regla del Producto</h3>
                            <p class="small text-secondary mb-2">
                                La <strong>regla del producto</strong> aplica cuando se multiplican dos funciones, <span class="math-inline">f(x)</span> y <span class="math-inline">g(x)</span>, y su derivada es:
                            </p>
                            <div class="math-block py-2 my-2">
                                <span><em>(f · g)' = f'g + fg'</em></span>
                            </div>
                            <p class="small text-secondary mb-0">
                                Cada término se deriva por separado mientras el otro se mantiene sin derivar.
                            </p>
                        </div>

                        <!-- 3. Regla del Cociente -->
                        <div class="p-3 rounded-3 border mb-4">
                            <h3 class="h6 fw-bold text-teal mb-2">3. La Regla del Cociente</h3>
                            <p class="small text-secondary mb-2">
                                La <strong>regla del cociente</strong> se usa al dividir una función <span class="math-inline">f(x)</span> entre <span class="math-inline">g(x)</span>:
                            </p>
                            <div class="math-block py-2 my-2">
                                <span>(</span>
                                <span class="math-frac"><span class="math-frac-num"><em>f</em></span><span class="math-frac-den"><em>g</em></span></span>
                                <span>)' =</span>
                                <span class="math-frac">
                                    <span class="math-frac-num"><em>f'g − fg'</em></span>
                                    <span class="math-frac-den"><em>g</em>²</span>
                                </span>
                            </div>
                            <p class="small text-secondary mb-0">
                                El numerador sigue la estructura de la regla del producto pero con un signo menos, y el denominador es el cuadrado de la función original del denominador.
                            </p>
                        </div>

                        <!-- Criterios de Reconocimiento de Patrones (Página 16 del PDF) -->
                        <div class="p-3 rounded-3 bg-navy text-white mb-4">
                            <h4 class="h6 fw-bold text-cyan mb-2">🧠 Síntesis de Reconocimiento de Patrones</h4>
                            <ul class="small text-slate-300 mb-0 space-y-2">
                                <li class="mb-2">
                                    <strong>Orden de aplicación:</strong> Si una expresión combina operaciones (ej. un producto donde un factor es una potencia compuesta), se aplica primero la regla de la estructura externa (producto o cociente) y luego la regla de la cadena en los factores necesarios.
                                </li>
                                <li class="mb-2">
                                    <strong>Relación:</strong> La regla del cociente es conceptualmente una aplicación de la regla del producto combinada con la regla de la cadena (al derivar <span class="math-inline">1/g(x)</span>).
                                </li>
                                <li>
                                    <strong>Ejemplo combinado:</strong> Para <span class="math-inline text-cyan">y = x²(3x + 1)⁴</span>, se usa la regla del producto primero, y dentro de la derivada del segundo factor, se aplica la regla de la cadena para <span class="math-inline text-cyan">(3x + 1)⁴</span>.
                                </li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l3-pdf">← Lección 3</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l4-video">Siguiente: Vídeo Fórmulas con Geometría ▶</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 4 · ITEM 2: VÍDEO (Pop-up YouTube)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l4-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-reading">▶ Lección 4 · Vídeo Pop-up</span>
                            <span class="text-muted small">Canal: 3Blue1Brown Español</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Patrones lógicos en las derivadas: Fórmulas de derivación explicadas con geometría</h2>
                        <div class="p-4 rounded-4 border bg-light text-center mb-4">
                            <div class="display-5 mb-2">🎬</div>
                            <h3 class="h5 fw-bold mb-1">Fórmulas de derivación explicadas con geometría</h3>
                            <p class="small text-muted mb-3">Enlace oficial: <code>https://youtu.be/nQruR83shIA</code></p>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <button type="button" class="btn btn-cyan fw-bold px-4" data-open-video-modal="l4-video">
                                    ▶ Abrir Reproductor en Ventana Emergente
                                </button>
                                <a href="https://youtu.be/nQruR83shIA" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary fw-semibold">
                                    Abrir en YouTube ↗
                                </a>
                                <a href="https://youtube.com/playlist?list=PLZHQObOWTQDMsr9K-rj53DwVRMYO3t5Yr&si=IJ7d68_BWKl7_vsz" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary">
                                    Playlist completa ↗
                                </a>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l4-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l4-reto">Siguiente: Reto ABP Bloques Lógicos ⚡</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 4 · ITEM 3: RETO ABP (Bloques lógicos para regla de la cadena)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l4-reto">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-abp">⚡ Lección 4 · Reto ABP</span>
                            <span class="text-muted small">Programación por bloques lógicos</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Reto ABP: Bloques Lógicos para la Regla de la Cadena</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Instrucción:</strong> En lugar de memorizar, selecciona los "bloques lógicos" en orden o presiona <strong>"Ejecutar paso"</strong> para armar correctamente la derivada de <span class="math-inline">f(g(x)) = (3x + 1)⁴</span> paso a paso.
                        </p>

                        <div class="cd-logic-box mb-4">
                            <div class="cd-logic-topbar">
                                <span class="fw-bold text-cyan small">⚡ Área de ejecución de lógica · Ensamblador Regla de la Cadena</span>
                                <span class="badge bg-info text-dark font-monospace" id="l4StepBadge">Bloques: 0 / 4</span>
                            </div>
                            <div class="cd-logic-body">
                                <div class="small text-slate-300 mb-2">1. Bloques lógicos disponibles (haz clic en el orden correcto de afuera hacia adentro):</div>
                                <div class="d-flex flex-wrap gap-2 mb-3" id="l4BlocksPool">
                                    <button type="button" class="cd-block-chip" data-block-id="int">g'(x) = d/dx(3x + 1) = 3</button>
                                    <button type="button" class="cd-block-chip" data-block-id="ext">f'(g(x)) = 4(3x + 1)³</button>
                                    <button type="button" class="cd-block-chip" data-block-id="res">= 12(3x + 1)³</button>
                                    <button type="button" class="cd-block-chip" data-block-id="mul">· (Multiplicar)</button>
                                </div>

                                <div class="small text-slate-300 mb-2">2. Línea de ensamblaje <span class="math-inline">d/dx[(3x + 1)⁴]</span>:</div>
                                <div class="cd-block-slot mb-3" id="l4AssemblySlot">
                                    <span class="text-slate-400 small">Haz clic en los bloques lógicos de arriba o presiona "Ejecutar paso" para ensamblar d/dx[(3x + 1)⁴]...</span>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mb-3">
                                    <button type="button" class="btn btn-sm btn-outline-light" id="l4ResetBtn">Reiniciar bloques</button>
                                    <button type="button" class="btn btn-sm btn-cyan fw-bold px-3" id="l4StepBtn">Ejecutar paso ▷</button>
                                </div>

                                <div class="cd-logic-console" id="l4Console">
                                    <span class="text-cyan">&gt;</span> Ensamblador listo. Recuerda: primero la derivada exterior f'(g(x)), luego el operador · y después la derivada interior g'(x).
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l4-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l4-pdf">Siguiente: Guía PDF 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 4 · ITEM 4: GUÍA PDF (gu_a_4_rbol_de_derivaci_n.pdf)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l4-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-pdf">📄 Lección 4 · Guía Descargable</span>
                            <code class="small">gu_a_4_rbol_de_derivaci_n.pdf</code>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Guía 04: Cheat-sheet (formulario) estructurado como un diagrama de decisiones</h2>
                        <p class="text-secondary small mb-3">
                            <em>"Si tienes una función dentro de otra → Usa Regla de la Cadena"</em>. Descarga el árbol de decisiones completo en PDF.
                        </p>

                        <div class="p-3 rounded-3 border bg-light d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                            <div>
                                <div class="fw-bold">📄 gu_a_4_rbol_de_derivaci_n.pdf</div>
                                <div class="small text-muted">Árbol de Decisiones para Derivación (Cheat-Sheet)</div>
                            </div>
                            <a href="<?= asset('pdf/gu_a_4_rbol_de_derivaci_n.pdf') ?>" download="gu_a_4_rbol_de_derivaci_n.pdf" target="_blank" class="btn btn-cyan fw-bold btn-sm px-3">
                                ⬇ Descargar Guía PDF
                            </a>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l4-reto">← Anterior: Reto ABP</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l5-lectura">Ir a Lección 5: Optimización →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 5 · ITEM 1: LECTURA (Puntos críticos aplicados a casos reales)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l5-lectura">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-reading">📖 Lección 5 · Lectura</span>
                            <span class="text-muted small">Tema: Puntos críticos, primera y segunda derivada aplicada a casos reales</span>
                        </div>

                        <h2 class="h3 fw-bold mb-3">Lección 5: Optimización de Problemas (Máximos y Mínimos)</h2>

                        <p class="text-secondary leading-relaxed mb-4">
                            Los <strong>puntos críticos</strong> son valores de <span class="math-inline">x</span> donde la <strong>primera derivada</strong> <span class="math-inline">f'(x)</span> es cero o no existe, identificando posibles <strong>máximos o mínimos locales</strong> de una función. El <strong>criterio de la primera derivada</strong> clasifica estos puntos analizando el cambio de signo de <span class="math-inline">f'(x)</span> (de positivo a negativo indica máximo; de negativo a positivo, mínimo), mientras que el <strong>criterio de la segunda derivada</strong> evalúa la concavidad en esos puntos: si <span class="math-inline">f''(c) &lt; 0</span> es un <strong>máximo</strong> (cóncava hacia abajo) y si <span class="math-inline">f''(c) &gt; 0</span> es un <strong>mínimo</strong> (cóncava hacia arriba).
                        </p>

                        <h3 class="h6 fw-bold mb-3">Aplicaciones fundamentales en casos reales por disciplina:</h3>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-3 border bg-light h-100">
                                    <div class="fw-bold text-primary mb-1">📈 Economía</div>
                                    <p class="small text-secondary mb-0">
                                        Se utiliza para maximizar ganancias o minimizar costos. Por ejemplo, en una función de ganancias <span class="math-inline">G(x) = −2x² + 8x</span>, la primera derivada <span class="math-inline">G'(x) = −4x + 8</span> se iguala a cero para encontrar <span class="math-inline">x = 2</span> como el punto crítico que representa el <strong>máximo de ganancias</strong>.
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-3 border bg-light h-100">
                                    <div class="fw-bold text-indigo mb-1">⚙️ Ingeniería y Diseño</div>
                                    <p class="small text-secondary mb-0">
                                        Permite determinar dimensiones óptimas para materiales. Un ejemplo clásico es diseñar un parterre rectangular dentro de un jardín semicircular o minimizar el uso de material en envases, buscando el <strong>mínimo área superficial</strong> para un volumen fijo.
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-3 border bg-light h-100">
                                    <div class="fw-bold text-teal mb-1">🧬 Biología y Medicina</div>
                                    <p class="small text-secondary mb-0">
                                        Se aplica para modelar tasas de crecimiento de poblaciones o concentración de fármacos, identificando el <strong>punto de inflexión</strong> (donde <span class="math-inline">f''(x) = 0</span>) que indica el cambio en la velocidad de crecimiento, o el <strong>máximo</strong> de efectividad de un tratamiento.
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-3 border bg-light h-100">
                                    <div class="fw-bold text-purple mb-1">💹 Finanzas</div>
                                    <p class="small text-secondary mb-0">
                                        Los analistas usan la primera derivada para estudiar tendencias de precios de acciones, identificando momentos clave de inversión basados en la <strong>monotonía</strong> (crecimiento o decrecimiento) de los activos.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l4-pdf">← Lección 4</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l5-video">Siguiente: Vídeo Optimización ▶</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 5 · ITEM 2: VÍDEO (Pop-up YouTube)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l5-video">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-reading">▶ Lección 5 · Vídeo Pop-up</span>
                            <span class="text-muted small">Problemas de Optimización</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Optimización: Resolviendo problemas del mundo real</h2>
                        <div class="p-4 rounded-4 border bg-light text-center mb-4">
                            <div class="display-5 mb-2">🎬</div>
                            <h3 class="h5 fw-bold mb-1">Optimización: Resolviendo problemas del mundo real</h3>
                            <p class="small text-muted mb-3">Enlace oficial: <code>https://youtu.be/KYvTWs54EgE</code></p>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <button type="button" class="btn btn-cyan fw-bold px-4" data-open-video-modal="l5-video">
                                    ▶ Abrir Reproductor en Ventana Emergente
                                </button>
                                <a href="https://youtu.be/KYvTWs54EgE" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary fw-semibold">
                                    Abrir en YouTube ↗
                                </a>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l5-lectura">← Anterior: Lectura</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l5-reto">Siguiente: Reto ABP Maximizar Volumen ⚡</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 5 · ITEM 3: RETO ABP (Maximizar volumen de caja con restricciones)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l5-reto">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-abp">⚡ Lección 5 · Reto ABP</span>
                            <span class="text-muted small">Puntos críticos y restricciones reales</span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Reto ABP: Maximizar el Volumen de una Caja de Envío</h2>
                        <p class="text-secondary small mb-3">
                            <strong>Caso real:</strong> A partir de una lámina rectangular de <span class="math-inline">24 cm × 16 cm</span>, se recortan cuadrados de lado <span class="math-inline">x</span> en las esquinas para doblar una caja de volumen <span class="math-inline">V(x) = x(24 − 2x)(16 − 2x) = 4x³ − 80x² + 384x</span>. Encuentra dónde la derivada <span class="math-inline">V'(x) = 0</span> alcanza el punto óptimo.
                        </p>

                        <div class="cd-logic-box mb-4">
                            <div class="cd-logic-topbar">
                                <span class="fw-bold text-cyan small">⚡ Área de ejecución de lógica · Optimización V'(x) = 0</span>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-info text-dark font-monospace" id="l5XBadge">Corte x = 1.00 cm</span>
                                    <span class="badge bg-success text-white font-monospace" id="l5VolBadge">V(x) = 308.00 cm³</span>
                                    <span class="badge bg-warning text-dark font-monospace" id="l5DerivBadge">V'(x) = 236.00</span>
                                </div>
                            </div>
                            <div class="cd-logic-body">
                                <div class="row g-4 align-items-center mb-3">
                                    <div class="col-12 col-md-6">
                                        <div class="bg-white rounded-3 p-2 text-center">
                                            <svg viewBox="0 0 300 205" class="w-100" style="max-width:300px;" aria-label="Curva de Volumen V(x) y punto óptimo">
                                                <line x1="40" y1="185" x2="280" y2="185" stroke="#334155" stroke-width="1.5"/>
                                                <line x1="40" y1="185" x2="40" y2="20" stroke="#334155" stroke-width="1.5"/>
                                                <!-- Curva V(x) para x en [0, 8] con máximo en x=3.14 (cx=134.2, cy=57.6) -->
                                                <path d="M 40 185 C 75 70, 110 57, 134.2 57.6 C 175 58, 225 130, 280 185" fill="none" stroke="#0284c7" stroke-width="2.5"/>
                                                <!-- Tangente horizontal en el máximo V'(3.14) = 0 -->
                                                <line x1="95" y1="57.6" x2="175" y2="57.6" stroke="#10b981" stroke-width="1.8" stroke-dasharray="4,3"/>
                                                <text x="134" y="48" text-anchor="middle" font-size="10" font-weight="bold" fill="#059669">Máximo V'(x) = 0 (x ≈ 3.14)</text>
                                                <!-- Punto actual -->
                                                <circle id="l5PointMarker" cx="70" cy="113" r="6" fill="#e11d48" stroke="#ffffff" stroke-width="1.5"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="l5SliderX" class="form-label small text-slate-300">Ajusta la restricción de corte <span class="math-inline">x</span> (<span class="math-inline">0.5 cm ≤ x ≤ 7.5 cm</span>):</label>
                                        <input type="range" class="form-range mb-3" id="l5SliderX" min="0.5" max="7.5" step="0.01" value="1.0">
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            <button type="button" class="btn btn-sm btn-outline-light" id="l5ResetBtn">Reiniciar</button>
                                            <button type="button" class="btn btn-sm btn-cyan fw-bold px-3" id="l5StepBtn">Ejecutar paso (Buscar V'(x)=0) ▷</button>
                                        </div>
                                        <p class="text-xs text-slate-400 mb-0">
                                            Observa cómo al acercarse a <span class="math-inline">x ≈ 3.14 cm</span>, la pendiente <span class="math-inline">V'(x)</span> se vuelve <strong>cero</strong> (recta tangente horizontal), alcanzando el volumen máximo de <span class="math-inline">546.08 cm³</span>.
                                        </p>
                                    </div>
                                </div>

                                <div class="cd-logic-console" id="l5Console">
                                    <span class="text-cyan">&gt;</span> Simulador de optimización listo. Ajusta el corte x o haz clic en "Ejecutar paso".
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l5-video">← Anterior: Vídeo</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="l5-pdf">Siguiente: Guía PDF 📄</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         LECCIÓN 5 · ITEM 4: GUÍA PDF (gu_a_5_algoritmo_de_optimizaci_n.pdf)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="l5-pdf">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-pdf">📄 Lección 5 · Guía Descargable</span>
                            <code class="small">gu_a_5_algoritmo_de_optimizaci_n.pdf</code>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Guía 05: Metodología paso a paso (algoritmo de 5 pasos) para resolver cualquier problema de optimización</h2>
                        <div class="p-3 rounded-3 border bg-light d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                            <div>
                                <div class="fw-bold">📄 gu_a_5_algoritmo_de_optimizaci_n.pdf</div>
                                <div class="small text-muted">Algoritmo de Optimización en 5 Pasos</div>
                            </div>
                            <a href="<?= asset('pdf/gu_a_5_algoritmo_de_optimizaci_n.pdf') ?>" download="gu_a_5_algoritmo_de_optimizaci_n.pdf" target="_blank" class="btn btn-cyan fw-bold btn-sm px-3">
                                ⬇ Descargar Guía PDF
                            </a>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="l5-reto">← Anterior: Reto ABP</button>
                            <button type="button" class="btn btn-cyan fw-bold px-4" data-cd-goto="cierre-encuesta">Ir al Módulo de Cierre y Certificación 🎓 →</button>
                        </div>
                    </article>

                    <!-- =====================================================
                         MÓDULO DE CIERRE · ITEM 1: ENCUESTA ESCALA LIKERT (1 AL 5)
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="cierre-encuesta">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-closing">📊 Módulo de Cierre · Instrumento de Evaluación</span>
                            <button type="button" class="btn btn-xs btn-outline-info fw-semibold" id="cdSurveyQuickFillBtn">
                                ⚡ Autocompletar demostración
                            </button>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Encuesta de Satisfacción e Impacto del Módulo Interactivo</h2>
                        <p class="small text-secondary leading-relaxed mb-3">
                            <strong>Instrucciones:</strong> La presente encuesta tiene como propósito conocer tu percepción, nivel de satisfacción y el impacto académico derivado del uso del simulador visual y la metodología de Aprendizaje Basado en Problemas (ABP) durante este módulo. Tus respuestas son confidenciales y serán utilizadas exclusivamente con fines de investigación para evaluar y mejorar la estrategia didáctica en el <strong>Instituto Tecnológico de Ciudad Victoria</strong>.
                        </p>

                        <!-- Leyenda de Escala Likert 1 a 5 -->
                        <div class="d-flex flex-wrap gap-2 p-2 rounded-3 bg-light border small mb-4">
                            <span class="badge bg-primary">5: Totalmente de acuerdo (TA)</span>
                            <span class="badge bg-info text-dark">4: De acuerdo (DA)</span>
                            <span class="badge bg-secondary">3: Neutral / Indeciso (N)</span>
                            <span class="badge bg-warning text-dark">2: En desacuerdo (ED)</span>
                            <span class="badge bg-danger">1: Totalmente en desacuerdo (TED)</span>
                        </div>

                        <form id="cdLikertSurveyForm">
                            <!-- Apartado I: Datos Generales (Opcional / Estadístico) -->
                            <div class="p-3 rounded-3 border bg-light mb-4">
                                <h3 class="h6 fw-bold mb-3">Apartado I: Datos Generales (Opcional / Estadístico)</h3>
                                <div class="row g-3 small">
                                    <div class="col-12 col-md-5">
                                        <label class="form-label fw-semibold mb-1">Carrera:</label>
                                        <input type="text" class="form-control form-control-sm" value="Ingeniería en Sistemas Computacionales">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label class="form-label fw-semibold mb-1">Semestre:</label>
                                        <input type="text" class="form-control form-control-sm" value="6">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label class="form-label fw-semibold mb-1">Grupo:</label>
                                        <input type="text" class="form-control form-control-sm" placeholder="A">
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label fw-semibold mb-1">Módulo Evaluado:</label>
                                        <input type="text" class="form-control form-control-sm fw-bold" value="Cálculo Diferencial" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Apartado II: Valoración de la Experiencia y el Impacto (Escala Likert 1 al 5) -->
                            <h3 class="h6 fw-bold mb-2">Apartado II: Valoración de la Experiencia y el Impacto (Escala Likert)</h3>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered cd-likert-table align-middle small mb-0">
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
                                        $criteriosLikert = [
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
                                        foreach ($criteriosLikert as $num => [$tituloCrit, $descCrit]):
                                        ?>
                                            <tr>
                                                <td class="text-center fw-bold"><?= $num ?></td>
                                                <td><strong><?= e($tituloCrit) ?></strong> <?= e($descCrit) ?></td>
                                                <?php for ($val = 5; $val >= 1; $val--): ?>
                                                    <td class="text-center">
                                                        <input type="radio" class="cd-likert-radio" name="likert_<?= $num ?>" value="<?= $val ?>" aria-label="Pregunta <?= $num ?> opción <?= $val ?>">
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
                                    <label for="cdComment1" class="form-label small fw-semibold mb-1">1. ¿Qué fue lo que más te gustó de los simuladores visuales y los retos de este módulo para comprender la materia?</label>
                                    <textarea id="cdComment1" class="form-control form-control-sm" rows="2"></textarea>
                                </div>
                                <div class="mb-2">
                                    <label for="cdComment2" class="form-label small fw-semibold mb-1">2. ¿En qué aspectos consideras que la plataforma o las lecciones podrían mejorar para facilitar aún más tu aprendizaje?</label>
                                    <textarea id="cdComment2" class="form-control form-control-sm" rows="2"></textarea>
                                </div>
                                <div>
                                    <label for="cdComment3" class="form-label small fw-semibold mb-1">3. Comentarios, observaciones o sugerencias adicionales:</label>
                                    <textarea id="cdComment3" class="form-control form-control-sm" rows="2"></textarea>
                                </div>
                            </div>

                            <div id="cdSurveyStatusAlert" class="d-none"></div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                                <button type="submit" class="btn btn-primary fw-bold px-4">
                                    Enviar Encuesta y Validar Progreso ✓
                                </button>

                                <!-- Botón bloqueado que se habilita tras enviar la encuesta -->
                                <button type="button" id="cdBtnDownloadCertSurvey" class="btn btn-secondary fw-bold px-4 opacity-75" disabled>
                                    🔒 Descargar Certificado (Completa la encuesta primero)
                                </button>
                            </div>
                        </form>
                    </article>

                    <!-- =====================================================
                         MÓDULO DE CIERRE · ITEM 2: CERTIFICADO DE ACREDITACIÓN
                         ===================================================== -->
                    <article class="cd-content-pane" data-cd-pane="cierre-certificado">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="cd-type-badge cd-badge-closing">🎓 Módulo de Cierre · Acreditación Oficial</span>
                            <span class="text-muted small font-monospace">Folio: <?= e($folioCertificado) ?></span>
                        </div>

                        <h2 class="h4 fw-bold mb-2">Certificado de Acreditación del Módulo</h2>

                        <div id="cdCertLockNotice" class="alert alert-warning d-flex align-items-center gap-2 py-2 px-3 small mb-3">
                            <span>🔒</span>
                            <div>
                                <strong>Requisito previo:</strong> Para habilitar el botón <strong>"Descargar Certificado"</strong>, primero envía la <button type="button" class="btn btn-link btn-sm p-0 align-baseline fw-bold" data-cd-goto="cierre-encuesta">Encuesta de Satisfacción e Impacto</button>.
                            </div>
                        </div>

                        <?php
                        /*
                         * =====================================================================
                         * INYECCIÓN DE PARÁMETROS DINÁMICOS DE SESIÓN PARA EL CERTIFICADO PDF:
                         * Para generar el documento PDF oficial de acreditación, los parámetros
                         * dinámicos de la sesión activa se inyectan aquí:
                         *   1. Nombre_Alumno     : $nombreAlumno     (ej. Auth::user()->nombre)
                         *   2. Modulo_Completado : $moduloCompletado ("Cálculo Diferencial (Enfoque Lógico-Visual)")
                         *   3. Fecha             : $fechaActual      (Fecha de finalización en formato d/m/Y)
                         * =====================================================================
                         */
                        ?>
                        <div class="p-4 rounded-4 border-2 border-dark bg-white text-center shadow-sm mb-4" style="border-style: double !important; border-width: 6px !important;">
                            <div class="text-uppercase text-xs tracking-wider text-muted fw-bold mb-1">
                                Instituto Tecnológico de Ciudad Victoria · Lógica Visual ABP
                            </div>
                            <h3 class="h5 fw-bold text-navy mb-3">CERTIFICADO DE ACREDITACIÓN</h3>
                            <p class="small text-secondary mb-1">Se otorga el presente reconocimiento a:</p>
                            <div class="h3 fw-bold text-primary my-2" id="cdCertStudentName"><?= e($nombreAlumno) ?></div>
                            <p class="small text-secondary max-w-2xl mx-auto mb-3">
                                Por haber acreditado satisfactoriamente las 5 lecciones interactivas, simuladores de iteración y retos de Aprendizaje Basado en Problemas del módulo:
                            </p>
                            <div class="d-inline-block px-4 py-2 rounded-3 bg-info-subtle text-info-emphasis fw-bold h5 mb-3" id="cdCertModuleName">
                                <?= e($moduloCompletado) ?>
                            </div>
                            <div class="d-flex flex-wrap justify-content-around align-items-center small text-muted pt-3 border-top mt-2">
                                <div><strong>Fecha de finalización:</strong> <span id="cdCertDate"><?= e($fechaActual) ?></span></div>
                                <div><strong>Folio de verificación:</strong> <span class="font-monospace"><?= e($folioCertificado) ?></span></div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-cd-goto="cierre-encuesta">← Volver a la Encuesta</button>
                            <button type="button" id="cdBtnDownloadCertPane" class="btn btn-secondary fw-bold px-4 opacity-75" disabled>
                                🔒 Descargar Certificado (Completa la encuesta primero)
                            </button>
                        </div>
                    </article>

                </div>
            </div>

        </div>
    </div>

    <!-- =================================================================
         MODAL EMERGENTE (POP-UP) CENTRADO PARA VÍDEOS DE YOUTUBE
         (No embebe el video directamente en el panel principal)
         ================================================================= -->
    <div class="modal fade" id="cdYoutubeModal" tabindex="-1" aria-labelledby="cdYoutubeModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
                <div class="modal-header bg-navy text-white border-bottom border-secondary py-3 px-4">
                    <div>
                        <span class="badge bg-cyan text-navy fw-bold mb-1" id="cdYoutubeModalChannel">Canal Oficial</span>
                        <h3 class="modal-title h6 fw-bold mb-0 text-white" id="cdYoutubeModalTitle">Reproductor de Vídeo</h3>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar ventana emergente"></button>
                </div>
                <div class="modal-body p-0 bg-black">
                    <div class="ratio ratio-16x9">
                        <iframe id="cdYoutubeIframe"
                                src=""
                                title="Reproductor de video de YouTube"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen></iframe>
                    </div>
                </div>
                <div class="modal-footer bg-light d-flex flex-wrap justify-content-between align-items-center px-4 py-3">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="https://youtu.be/WUvTyaaNkzM" id="cdYoutubeDirectLink" target="_blank" rel="noopener noreferrer" class="btn btn-danger btn-sm fw-bold px-3">
                            ▶ Abrir en YouTube ↗
                        </a>
                        <span id="cdYoutubePlaylistWrap" class="d-none">
                            <a href="#" id="cdYoutubePlaylistLink" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm fw-semibold">
                                Ver Playlist Completa en YouTube ↗
                            </a>
                        </span>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

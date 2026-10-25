<?php
/**
 * Blog de Lógica Visual ABP
 * Artículos, técnicas de estudio, novedades educativas y recursos multimedia
 */
$pdfAbp = asset('pdf/abp.pdf');
$pdfCap21 = asset('pdf/Cap-21-Formacion-Docente-en-las-Universidades.pdf');
$pdfSimulacion = asset('pdf/El aprendizaje basado en simulacion y el aporte de las teorias educativas.pdf');
?>

<section class="py-5 bg-navy text-white position-relative overflow-hidden">
    <div class="bg-grid position-absolute top-0 start-0 w-100 h-100 pointer-events-none opacity-20"></div>
    <div class="container-fluid max-w-7xl mx-auto px-4 position-relative z-1">
        <div class="d-flex align-items-center gap-3 mb-3">
            <span class="fs-1 p-3 rounded-4 bg-white bg-opacity-10 shadow-xs">✍️</span>
            <div>
                <span class="badge bg-cyan text-navy fw-bold text-uppercase px-3 py-1 mb-2">Publicaciones y Didáctica</span>
                <h1 class="h2 fw-bold text-white mb-0">Blog de Lógica Visual ABP</h1>
                <p class="text-cyan small mb-0">Artículos, técnicas de estudio y fundamentos de simulación interactiva</p>
            </div>
        </div>
        <p class="lead text-slate-300 max-w-2xl mb-0">
            Estrategias pedagógicas fundamentadas en investigación universitaria y herramientas prácticas para dominar la programación, las ciencias exactas y el razonamiento lógico.
        </p>
    </div>
</section>

<!-- Sección 1: Tarjetas de Presentación Principales -->
<section class="py-5 bg-slate-50 border-bottom border-slate-200">
    <div class="container-fluid max-w-7xl mx-auto px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h5 fw-bold text-navy mb-1">Publicaciones Destacadas</h2>
                <p class="text-muted small mb-0">Selecciona un apartado para leer su contenido completo y acceder a sus recursos multimedia.</p>
            </div>
            <span class="badge bg-white text-secondary border px-3 py-2 rounded-pill shadow-xs">2 Artículos de Investigación</span>
        </div>

        <div class="row g-4">
            <!-- Tarjeta de presentación 1 -->
            <div class="col-12 col-md-6">
                <article class="card h-100 border-0 rounded-4 shadow-xs p-4 p-md-5 bg-white hover-card d-flex flex-column transition-all">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-cyan text-navy fw-bold px-3 py-1">Metodología ABP</span>
                        <span class="small text-muted font-monospace">20 Octubre, 2026</span>
                    </div>
                    <h2 class="h4 fw-bold mb-3 text-navy">Las 4 fases del Aprendizaje Basado en Problemas</h2>
                    <p class="text-muted leading-relaxed mb-4 flex-grow-1">
                        Estructura tu proceso cognitivo en cuatro etapas: Explorar, Formular, Experimentar y Reflexionar,
                        transformando problemas abstractos en soluciones comprensibles mediante el rol activo del estudiante.
                    </p>
                    <div class="d-flex flex-wrap gap-2 pt-3 border-top align-items-center justify-content-between">
                        <a href="#apartado-abp" class="btn btn-navy btn-sm rounded-pill px-4 fw-bold">
                            Ver apartado completo ↓
                        </a>
                        <div class="d-flex gap-1">
                            <span class="badge bg-light text-secondary border">🌐 Enlace</span>
                            <span class="badge bg-light text-secondary border">🎬 Videos (3)</span>
                            <span class="badge bg-light text-secondary border">📄 PDF</span>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Tarjeta de presentación 2 -->
            <div class="col-12 col-md-6">
                <article class="card h-100 border-0 rounded-4 shadow-xs p-4 p-md-5 bg-white hover-card d-flex flex-column transition-all">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-indigo-subtle text-indigo fw-bold px-3 py-1">Didáctica y Simulación</span>
                        <span class="small text-muted font-monospace">14 Octubre, 2026</span>
                    </div>
                    <h2 class="h4 fw-bold mb-3 text-navy">Por qué los simuladores superan a la teoría estática</h2>
                    <p class="text-muted leading-relaxed mb-4 flex-grow-1">
                        La interactividad inmediata permite al alumno comprobar la validez de sus hipótesis en segundos,
                        acelerando la curva de aprendizaje en algoritmos, cálculo y física mediante entornos seguros y debriefing.
                    </p>
                    <div class="d-flex flex-wrap gap-2 pt-3 border-top align-items-center justify-content-between">
                        <a href="#apartado-simuladores" class="btn btn-indigo btn-sm rounded-pill px-4 fw-bold text-white">
                            Ver apartado completo ↓
                        </a>
                        <div class="d-flex gap-1">
                            <span class="badge bg-light text-secondary border">🌐 Enlace</span>
                            <span class="badge bg-light text-secondary border">🎬 Videos (4)</span>
                            <span class="badge bg-light text-secondary border">📄 PDFs (2)</span>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>

<!-- SECCIÓN 2: DESARROLLO DE INFORMACIÓN DE CADA APARTADO -->
<section class="py-5 bg-white">
    <div class="container-fluid max-w-7xl mx-auto px-4">

        <!-- ============================================================= -->
        <!-- APARTADO 1: Las 4 fases del Aprendizaje Basado en Problemas -->
        <!-- ============================================================= -->
        <article class="p-4 p-md-5 rounded-4 border border-slate-200 bg-slate-50 mb-5 shadow-xs" id="apartado-abp">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <span class="fs-2 text-cyan">🧩</span>
                    <div>
                        <span class="badge bg-cyan text-navy fw-bold text-uppercase px-3 py-1 mb-1">Apartado 01 · Metodología ABP</span>
                        <h2 class="h3 fw-bold text-navy mb-0">Las 4 fases del Aprendizaje Basado en Problemas</h2>
                    </div>
                </div>
            </div>

            <!-- Menú de Opciones: Enlace, Video, PDF -->
            <div class="p-3 bg-white rounded-3 border border-slate-200 shadow-2xs mb-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div class="small fw-bold text-navy text-uppercase tracking-wider">
                        📂 Menú de opciones y recursos:
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <!-- Botón Enlace Externo -->
                        <a href="https://www.hastingsschool.com/es/blog/aprendizaje-basado-en-problemas/?_gl=1*1wwo4dw*_up*MQ..*_ga*NjkyMjYzNjg5LjE3OTI4ODcwOTg.*_ga_4JS5Y8N997*czE3OTI4ODcwOTckbzEkZzEkdDE3OTI4ODcxMTQkajQzJGwwJGgxNjgzNjA4NzE2"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold d-inline-flex align-items-center gap-2 shadow-2xs"
                           title="Abrir enlace web de referencia">
                            <span>🌐 Enlace web</span>
                            <span class="small opacity-75">↗</span>
                        </a>

                        <!-- Botón Video con Modal y Dropdown a YouTube -->
                        <div class="btn-group shadow-2xs">
                            <button type="button"
                                    class="btn btn-danger btn-sm rounded-start-pill px-3 fw-semibold d-inline-flex align-items-center gap-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalBlogVideos"
                                    data-blog-apartado="1"
                                    id="btnVideosApartado1">
                                <span>🎬 Videos (3)</span>
                                <span class="badge bg-white text-danger rounded-pill">Ver</span>
                            </button>
                            <button type="button"
                                    class="btn btn-danger btn-sm rounded-end-pill dropdown-toggle dropdown-toggle-split px-2"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    title="Opciones de YouTube">
                                <span class="visually-hidden">Desplegar opciones de videos</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-md border-0 rounded-3">
                                <li><h6 class="dropdown-header text-uppercase fs-8 text-muted">Ventana emergente</h6></li>
                                <li>
                                    <button class="dropdown-item small py-2 d-flex align-items-center gap-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalBlogVideos"
                                            data-blog-apartado="1">
                                        <span>▶</span>
                                        <span>Abrir reproductor emergente</span>
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header text-uppercase fs-8 text-muted">Abrir directo en YouTube ↗</h6></li>
                                <li>
                                    <a class="dropdown-item small py-2 d-flex align-items-center gap-2 text-danger"
                                       href="https://youtu.be/CUDRZB-wdzI" target="_blank" rel="noopener noreferrer">
                                        <span>📺</span>
                                        <span>Video 1: Fases de ABP en YouTube</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item small py-2 d-flex align-items-center gap-2 text-danger"
                                       href="https://youtu.be/hrBjKEu5EtE" target="_blank" rel="noopener noreferrer">
                                        <span>📺</span>
                                        <span>Video 2: Metodología ABP en YouTube</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item small py-2 d-flex align-items-center gap-2 text-danger"
                                       href="https://youtu.be/J2rv0TCg3vs" target="_blank" rel="noopener noreferrer">
                                        <span>📺</span>
                                        <span>Video 3: Pensamiento Crítico en YouTube</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Botón PDF con Modal y Enlace Directo -->
                        <div class="btn-group shadow-2xs">
                            <button type="button"
                                    class="btn btn-cyan btn-sm rounded-start-pill px-3 fw-bold d-inline-flex align-items-center gap-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalBlogPdf"
                                    data-blog-pdf="abp"
                                    id="btnPdfAbp">
                                <span>📄 Ver PDF</span>
                                <span class="badge bg-navy text-white rounded-pill">abp.pdf</span>
                            </button>
                            <a href="<?= e($pdfAbp) ?>"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="btn btn-outline-cyan btn-sm rounded-end-pill px-2 d-inline-flex align-items-center"
                               title="Abrir abp.pdf en pestaña completa">
                                <span class="small fw-bold">↗</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido Informativo del Apartado 1 -->
            <div class="row g-4 mb-4">
                <div class="col-12 col-lg-7">
                    <h3 class="h5 fw-bold text-navy mb-3">Estructuración del Proceso Cognitivo en ABP</h3>
                    <p class="text-slate-600 leading-relaxed mb-3">
                        El <strong>Aprendizaje Basado en Problemas (ABP)</strong> invierte el camino tradicional de la enseñanza. Mientras que en el modelo convencional primero se memoriza la teoría y luego se busca una aplicación, en el ABP se plantea primero una situación problemática de la realidad que detona la necesidad intrínseca de investigar, formular hipótesis y construir soluciones.
                    </p>
                    <p class="text-slate-600 leading-relaxed mb-4">
                        Según las investigaciones del <em>Tecnológico de Monterrey (ITESM)</em> y las teorías constructivistas de Piaget, Ausubel y Vygotsky, este proceso activa la metacognición en <strong>cuatro fases indispensables</strong>:
                    </p>

                    <div class="space-y-3">
                        <div class="p-3 bg-white rounded-3 border-start border-4 border-cyan shadow-2xs mb-2">
                            <h4 class="h6 fw-bold text-navy mb-1">1. Fase de Exploración (Explora)</h4>
                            <p class="small text-muted mb-0">
                                Los alumnos leen y analizan el escenario planteado, identifican lo que ya saben, definen términos desconocidos y reconocen con precisión las variables del problema y las lagunas de información.
                            </p>
                        </div>
                        <div class="p-3 bg-white rounded-3 border-start border-4 border-indigo shadow-2xs mb-2">
                            <h4 class="h6 fw-bold text-navy mb-1">2. Fase de Formulación (Formula)</h4>
                            <p class="small text-muted mb-0">
                                El equipo elabora un esquema o mapa conceptual, genera hipótesis explicativas de causa-efecto y diseña un plan de trabajo detallado con objetivos de aprendizaje específicos y división colaborativa de responsabilidades.
                            </p>
                        </div>
                        <div class="p-3 bg-white rounded-3 border-start border-4 border-purple shadow-2xs mb-2">
                            <h4 class="h6 fw-bold text-navy mb-1">3. Fase de Experimentación (Experimenta)</h4>
                            <p class="small text-muted mb-0">
                                Puesta en práctica de la estrategia: se ejecutan simuladores interactivos, pruebas algorítmicas o cálculos numéricos para verificar si las hipótesis resisten la comprobación experimental en tiempo real.
                            </p>
                        </div>
                        <div class="p-3 bg-white rounded-3 border-start border-4 border-sky shadow-2xs mb-2">
                            <h4 class="h6 fw-bold text-navy mb-1">4. Fase de Reflexión y Retroalimentación (Reflexiona)</h4>
                            <p class="small text-muted mb-0">
                                Proceso de <em>debriefing</em> guiado por el tutor: los estudiantes analizan el porqué de sus aciertos y desaciertos, defienden su solución con evidencia sólida y consolidan el aprendizaje significativo perdurable.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="card border-0 rounded-4 bg-white p-4 shadow-xs h-100 d-flex flex-column">
                        <h4 class="h6 fw-bold text-navy mb-3 border-bottom pb-2">
                            Roles y Dinámica en el Aula ABP
                        </h4>
                        <div class="mb-3">
                            <span class="badge bg-cyan-subtle text-cyan fw-bold mb-1">Rol del Alumno</span>
                            <p class="small text-muted mb-0">
                                Sujeto activo, independiente y colaborativo. Aprende a tomar decisiones, evaluar críticamente fuentes de información, tolerar la ambigüedad y asumir la responsabilidad de su propio crecimiento profesional.
                            </p>
                        </div>
                        <div class="mb-3">
                            <span class="badge bg-indigo-subtle text-indigo fw-bold mb-1">Rol del Profesor / Tutor</span>
                            <p class="small text-muted mb-0">
                                Deja de ser el expositor magistral y se convierte en facilitador, formulador de preguntas socráticas desafiantes, mediador de la interacción grupal y guía en la zona de desarrollo próximo (Vygotsky).
                            </p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-3 mt-auto">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="text-cyan">📌</span>
                                <span class="small fw-bold text-slate-700">Enlace de Referencia:</span>
                            </div>
                            <a href="https://www.hastingsschool.com/es/blog/aprendizaje-basado-en-problemas/?_gl=1*1wwo4dw*_up*MQ..*_ga*NjkyMjYzNjg5LjE3OTI4ODcwOTg.*_ga_4JS5Y8N997*czE3OTI4ODcwOTckbzEkZzEkdDE3OTI4ODcxMTQkajQzJGwwJGgxNjgzNjA4NzE2"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="small text-cyan fw-semibold text-break text-decoration-none">
                                Hastings School · Guía Práctica de ABP ↗
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </article>


        <!-- ============================================================= -->
        <!-- APARTADO 2: Por qué los simuladores superan a la teoría estática -->
        <!-- ============================================================= -->
        <article class="p-4 p-md-5 rounded-4 border border-slate-200 bg-slate-50 shadow-xs" id="apartado-simuladores">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <span class="fs-2 text-indigo">🔬</span>
                    <div>
                        <span class="badge bg-indigo-subtle text-indigo fw-bold text-uppercase px-3 py-1 mb-1">Apartado 02 · Didáctica y Simulación</span>
                        <h2 class="h3 fw-bold text-navy mb-0">Por qué los simuladores superan a la teoría estática</h2>
                    </div>
                </div>
            </div>

            <!-- Menú de Opciones: Enlace, Video, PDF -->
            <div class="p-3 bg-white rounded-3 border border-slate-200 shadow-2xs mb-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div class="small fw-bold text-navy text-uppercase tracking-wider">
                        📂 Menú de opciones y recursos:
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <!-- Botón Enlace Externo -->
                        <a href="https://www.unir.net/revista/ingenieria/modelos-simulacion/"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold d-inline-flex align-items-center gap-2 shadow-2xs"
                           title="Abrir enlace web de referencia">
                            <span>🌐 Enlace web</span>
                            <span class="small opacity-75">↗</span>
                        </a>

                        <!-- Botón Video con Modal y Dropdown a YouTube -->
                        <div class="btn-group shadow-2xs">
                            <button type="button"
                                    class="btn btn-danger btn-sm rounded-start-pill px-3 fw-semibold d-inline-flex align-items-center gap-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalBlogVideos"
                                    data-blog-apartado="2"
                                    id="btnVideosApartado2">
                                <span>🎬 Videos (4)</span>
                                <span class="badge bg-white text-danger rounded-pill">Ver</span>
                            </button>
                            <button type="button"
                                    class="btn btn-danger btn-sm rounded-end-pill dropdown-toggle dropdown-toggle-split px-2"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    title="Opciones de YouTube">
                                <span class="visually-hidden">Desplegar opciones de videos</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-md border-0 rounded-3">
                                <li><h6 class="dropdown-header text-uppercase fs-8 text-muted">Ventana emergente</h6></li>
                                <li>
                                    <button class="dropdown-item small py-2 d-flex align-items-center gap-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalBlogVideos"
                                            data-blog-apartado="2">
                                        <span>▶</span>
                                        <span>Abrir reproductor emergente</span>
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header text-uppercase fs-8 text-muted">Abrir directo en YouTube ↗</h6></li>
                                <li>
                                    <a class="dropdown-item small py-2 d-flex align-items-center gap-2 text-danger"
                                       href="https://youtu.be/IV0Cs5wlycY" target="_blank" rel="noopener noreferrer">
                                        <span>📺</span>
                                        <span>Video 1: Modelos de Simulación en YouTube</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item small py-2 d-flex align-items-center gap-2 text-danger"
                                       href="https://youtu.be/MAjm9Sfc9Mg" target="_blank" rel="noopener noreferrer">
                                        <span>📺</span>
                                        <span>Video 2: Debriefing y Feedback en YouTube</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item small py-2 d-flex align-items-center gap-2 text-danger"
                                       href="https://youtu.be/4J-lLDp4OXs" target="_blank" rel="noopener noreferrer">
                                        <span>📺</span>
                                        <span>Video 3: Simuladores vs Teoría en YouTube</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item small py-2 d-flex align-items-center gap-2 text-danger"
                                       href="https://youtu.be/1TQxM3lpCf4" target="_blank" rel="noopener noreferrer">
                                        <span>📺</span>
                                        <span>Video 4: Entornos Virtuales en YouTube</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Botón Dropdown para los 2 PDFs -->
                        <div class="dropdown shadow-2xs">
                            <button type="button"
                                    class="btn btn-indigo btn-sm rounded-pill px-3 fw-bold text-white dropdown-toggle d-inline-flex align-items-center gap-2"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    id="btnDropdownPdfs">
                                <span>📄 Ver PDFs (2)</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-md border-0 rounded-3" style="min-width: 320px;">
                                <li><h6 class="dropdown-header text-uppercase fs-8 text-muted">Documento 1: Formación Docente</h6></li>
                                <li>
                                    <button class="dropdown-item small py-2 d-flex align-items-center justify-content-between"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalBlogPdf"
                                            data-blog-pdf="cap21">
                                        <span class="d-flex align-items-center gap-2">
                                            <span>📕</span>
                                            <span class="fw-semibold">Cap-21-Formacion-Docente.pdf</span>
                                        </span>
                                        <span class="badge bg-indigo text-white rounded-pill">Ver ventana</span>
                                    </button>
                                </li>
                                <li>
                                    <a class="dropdown-item small py-1 ps-4 text-muted"
                                       href="<?= e($pdfCap21) ?>" target="_blank" rel="noopener noreferrer">
                                        <span>↗ Abrir en pestaña completa</span>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header text-uppercase fs-8 text-muted">Documento 2: Teorías Educativas</h6></li>
                                <li>
                                    <button class="dropdown-item small py-2 d-flex align-items-center justify-content-between"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalBlogPdf"
                                            data-blog-pdf="simulacion">
                                        <span class="d-flex align-items-center gap-2">
                                            <span>📘</span>
                                            <span class="fw-semibold">Aprendizaje en Simulación...pdf</span>
                                        </span>
                                        <span class="badge bg-indigo text-white rounded-pill">Ver ventana</span>
                                    </button>
                                </li>
                                <li>
                                    <a class="dropdown-item small py-1 ps-4 text-muted"
                                       href="<?= e($pdfSimulacion) ?>" target="_blank" rel="noopener noreferrer">
                                        <span>↗ Abrir en pestaña completa</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido Informativo del Apartado 2 -->
            <div class="row g-4 mb-4">
                <div class="col-12 col-lg-7">
                    <h3 class="h5 fw-bold text-navy mb-3">El Valor Pedagógico del Error en Entornos Simulados</h3>
                    <p class="text-slate-600 leading-relaxed mb-3">
                        Los libros de texto y diapositivas transmiten información estática en un canal unidireccional. Por el contrario, los <strong>simuladores interactivos</strong> reproducen comportamientos dinámicos basados en modelos matemáticos y algorítmicos, permitiendo que el estudiante intervenga de forma activa y observe las consecuencias inmediatas de sus decisiones.
                    </p>
                    <p class="text-slate-600 leading-relaxed mb-4">
                        Tal como demuestran los estudios de la UNAM y Revista Espacios (González, Bravo & Ortiz, 2018), la simulación aplicada a la educación superior ofrece ventajas trascendentales:
                    </p>

                    <div class="space-y-3">
                        <div class="p-3 bg-white rounded-3 border-start border-4 border-indigo shadow-2xs mb-2">
                            <h4 class="h6 fw-bold text-navy mb-1">Aprendizaje Seguro a Través del Error Deliberado</h4>
                            <p class="small text-muted mb-0">
                                Permite repetir el proceso tantas veces como sea necesario para corregir errores sin consecuencias iatrogénicas o de pérdida material. El alumno aprende del fallo sin miedo a ser castigado, reduciendo la ansiedad y reforzando la plasticidad neuronal.
                            </p>
                        </div>
                        <div class="p-3 bg-white rounded-3 border-start border-4 border-cyan shadow-2xs mb-2">
                            <h4 class="h6 fw-bold text-navy mb-1">Retroalimentación Inmediata (Feedback & Debriefing)</h4>
                            <p class="small text-muted mb-0">
                                Frente a una prueba que se evalúa semanas después, el simulador responde en milisegundos con métricas, gráficos o cambios de estado. La sesión posterior de debriefing conecta las emociones de la práctica con la conceptualización teórica.
                            </p>
                        </div>
                        <div class="p-3 bg-white rounded-3 border-start border-4 border-purple shadow-2xs mb-2">
                            <h4 class="h6 fw-bold text-navy mb-1">Fidelidad Adaptable (Baja, Intermedia y Alta)</h4>
                            <p class="small text-muted mb-0">
                                Desde entrenadores de tareas específicas (comprensión de un ciclo o fórmula) hasta simulaciones complejas de cuerpo completo o redes de datos en la nube que desafían el pensamiento crítico y la toma de decisiones bajo incertidumbre.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="card border-0 rounded-4 bg-white p-4 shadow-xs h-100 d-flex flex-column">
                        <h4 class="h6 fw-bold text-navy mb-3 border-bottom pb-2">
                            Fundamentación Teórica de la Simulación
                        </h4>
                        <div class="mb-3">
                            <span class="badge bg-purple-subtle text-purple fw-bold mb-1">Aprendizaje Experiencial (Kolb)</span>
                            <p class="small text-muted mb-0">
                                El ciclo completo: Experiencia concreta → Observación reflexiva → Conceptualización abstracta → Experimentación activa.
                            </p>
                        </div>
                        <div class="mb-3">
                            <span class="badge bg-sky-subtle text-sky fw-bold mb-1">Conectivismo y Caos (Siemens & Downes)</span>
                            <p class="small text-muted mb-0">
                                En la era digital, el conocimiento reside en artefactos no humanos (simuladores y bases de datos); aprender consiste en navegar redes complejas y autoorganizadas.
                            </p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-3 mt-auto">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="text-indigo">📌</span>
                                <span class="small fw-bold text-slate-700">Enlace de Referencia:</span>
                            </div>
                            <a href="https://www.unir.net/revista/ingenieria/modelos-simulacion/"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="small text-indigo fw-semibold text-break text-decoration-none">
                                UNIR · Modelos de Simulación en Ingeniería ↗
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </article>

    </div>
</section>

<!-- ================================================================= -->
<!-- MODAL DE VIDEOS: APARTADO 1 Y APARTADO 2                          -->
<!-- ================================================================= -->
<div class="modal fade" id="modalBlogVideos" tabindex="-1" aria-labelledby="modalBlogVideosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            <div class="modal-header bg-navy text-white px-4 py-3 border-0">
                <div class="d-flex align-items-center gap-3">
                    <span id="blogModalVideoBadge" class="badge bg-danger text-white fw-bold px-3 py-1">Video</span>
                    <div>
                        <h3 class="modal-title fs-5 fw-bold text-white mb-0" id="modalBlogVideosLabel">Reproductor de Video</h3>
                        <p id="blogModalVideoSubtitle" class="text-cyan small mb-0">Lógica Visual ABP</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar ventana de video"></button>
            </div>

            <!-- Selector de videos del apartado -->
            <div class="px-4 py-2 bg-slate-100 border-bottom d-flex align-items-center justify-content-between gap-2 overflow-auto">
                <span class="small fw-semibold text-slate-700 text-nowrap">Seleccionar video:</span>
                <div class="d-flex gap-2" id="blogVideoSelectorButtons"></div>
            </div>

            <!-- Video Player 16:9 -->
            <div class="modal-body p-0 bg-black">
                <div class="ratio ratio-16x9">
                    <iframe id="blogVideoIframe"
                            src=""
                            title="Reproductor de Video Blog ABP"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                </div>
            </div>

            <!-- Barra inferior con opción directa de YouTube y navegación -->
            <div class="modal-footer bg-white px-4 py-3 d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold" id="blogVideoPrevBtn">
                    ← Anterior
                </button>
                <div class="d-flex align-items-center gap-3">
                    <span class="small text-muted font-monospace d-none d-sm-inline" id="blogVideoCounterIndicator">Video 1 de 3</span>
                    <a id="blogVideoYoutubeBtn"
                       href="#"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="btn btn-danger btn-sm rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.022.26-.01.104c-.048.519-.119 1.023-.22 1.402a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"/>
                        </svg>
                        <span>Abrir en YouTube ↗</span>
                    </a>
                </div>
                <button type="button" class="btn btn-navy btn-sm rounded-pill px-4 fw-bold" id="blogVideoNextBtn">
                    Siguiente video →
                </button>
            </div>
        </div>
    </div>
</div>


<!-- ================================================================= -->
<!-- MODAL DE VISUALIZACIÓN DE PDFS                                    -->
<!-- ================================================================= -->
<div class="modal fade" id="modalBlogPdf" tabindex="-1" aria-labelledby="modalBlogPdfLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 94%;">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden" style="height: 90vh;">
            <div class="modal-header bg-navy text-white px-4 py-3 border-0 flex-shrink-0">
                <div class="d-flex align-items-center gap-3">
                    <span class="fs-4">📄</span>
                    <div>
                        <h3 class="modal-title fs-5 fw-bold text-white mb-0" id="modalBlogPdfLabel">Visualizador de Documentos PDF</h3>
                        <p id="blogPdfSubtitle" class="text-cyan small mb-0 font-monospace"></p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a id="blogPdfDownloadBtn" href="#" target="_blank" rel="noopener noreferrer" class="btn btn-cyan btn-sm rounded-pill px-3 fw-bold">
                        Abrir en nueva pestaña ↗
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar visor de PDF"></button>
                </div>
            </div>

            <!-- Selector de pestañas para cambiar de documento PDF dentro del visor -->
            <div class="px-4 py-2 bg-slate-100 border-bottom d-flex align-items-center gap-2 overflow-auto flex-shrink-0">
                <span class="small fw-semibold text-slate-700 text-nowrap me-2">Documentos disponibles:</span>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1" data-pdf-tab="abp">
                    📄 abp.pdf (ITESM)
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1" data-pdf-tab="cap21">
                    📕 Cap-21-Docencia.pdf (UNAM)
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1" data-pdf-tab="simulacion">
                    📘 Aprendizaje en Simulación.pdf (Espacios)
                </button>
            </div>

            <!-- Visor de PDF Integrado -->
            <div class="modal-body p-0 bg-slate-900 flex-grow-1 position-relative d-flex flex-column">
                <iframe id="blogPdfIframe"
                        src=""
                        style="width: 100%; height: 100%; flex: 1 1 auto; border: none;"
                        title="Visor de PDF"></iframe>
            </div>

            <div class="modal-footer bg-slate-50 py-2 px-4 d-flex justify-content-between align-items-center flex-shrink-0">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small" id="blogPdfFooterInfo">
                        Documento académico oficial del proyecto Lógica Visual ABP.
                    </span>
                    <a id="blogPdfFallbackLink" href="#" target="_blank" rel="noopener noreferrer" class="btn btn-link btn-sm text-cyan p-0 text-decoration-none">
                        (¿Problemas al visualizar? Abrir PDF directo)
                    </a>
                </div>
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">
                    Cerrar visor
                </button>
            </div>
        </div>
    </div>
</div>

<div id="blogPdfData"
     data-pdf-abp="<?= e($pdfAbp) ?>"
     data-pdf-cap21="<?= e($pdfCap21) ?>"
     data-pdf-simulacion="<?= e($pdfSimulacion) ?>"></div>
<script src="<?= asset('js/blog.js') ?>"></script>

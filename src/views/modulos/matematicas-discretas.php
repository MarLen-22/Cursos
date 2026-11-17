<?php
/**
 * Curso Interactivo MOOC: Matemáticas Discretas (Diseño Claro / Blanco)
 * Estilo Santander Open Academy / Coursera
 * Con Videos, PDFs incrustados/descargables, Examen por cada Módulo y Evaluación Final con Candado
 */
$moduloTitulo = $modulo->titulo ?? 'Matemáticas Discretas';
?>
<div class="mooc-white-theme bg-white min-vh-100 text-slate-900 pb-5" id="moocAppDisc">
    
    <!-- ENCABEZADO SUPERIOR ESTILO SANTANDER OPEN ACADEMY (FONDO BLANCO Y LIMPIO) -->
    <header class="border-bottom border-slate-200 bg-white py-4 shadow-xs">
        <div class="container-fluid max-w-7xl mx-auto px-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="<?= url('/modulos') ?>" class="text-primary text-decoration-none fw-semibold">← Volver a Cursos</a></li>
                        <li class="breadcrumb-item active text-slate-500" aria-current="page"><?= e($moduloTitulo) ?></li>
                    </ol>
                </nav>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-purple-50 text-purple border border-purple-200 px-3 py-2 rounded-pill fw-semibold" style="color: #6b21a8; background-color: #faf5ff; border-color: #e9d5ff;">
                        Nivel Inicial · MOOC
                    </span>
                    <span class="badge bg-slate-100 text-slate-700 border border-slate-200 px-3 py-2 rounded-pill">
                        5 Módulos + Certificado
                    </span>
                </div>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-12 col-lg-8">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-3 fs-3 px-3 py-2 fw-bold" style="background-color: #f3e8ff; color: #6b21a8; border: 1px solid #d8b4fe;">
                            ⛖
                        </div>
                        <div>
                            <span class="text-uppercase tracking-wider small fw-bold" style="color: #7e22ce;">Curso Oficial</span>
                            <h1 class="h2 fw-bold text-slate-900 mb-0"><?= e($moduloTitulo) ?></h1>
                        </div>
                    </div>
                    <p class="text-slate-600 mb-3 small max-w-2xl">
                        Comprende el lenguaje matemático de la informática: lógica proposicional, conjuntos, relaciones, grafos y circuitos booleanos mediante videos, guías PDF y comprobación por módulos.
                    </p>
                    
                    <!-- Navegación rápida por módulos -->
                    <div class="d-flex flex-wrap align-items-center gap-2 pt-1">
                        <span class="small text-slate-500 me-2 fw-semibold">Módulos:</span>
                        <button type="button" class="btn btn-sm btn-outline-purple rounded-circle px-2 py-1 mod-nav-pill active" onclick="loadLessonDisc('m1_video')">1</button>
                        <button type="button" class="btn btn-sm btn-outline-purple rounded-circle px-2 py-1 mod-nav-pill" onclick="loadLessonDisc('m2_video')">2</button>
                        <button type="button" class="btn btn-sm btn-outline-purple rounded-circle px-2 py-1 mod-nav-pill" onclick="loadLessonDisc('m3_video')">3</button>
                        <button type="button" class="btn btn-sm btn-outline-purple rounded-circle px-2 py-1 mod-nav-pill" onclick="loadLessonDisc('m4_video')">4</button>
                        <button type="button" class="btn btn-sm btn-outline-purple rounded-circle px-2 py-1 mod-nav-pill" onclick="loadLessonDisc('m5_video')">5</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle px-2 py-1 mod-nav-pill" onclick="openFinalEvaluationDisc()">🔒 Final</button>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="bg-slate-50 border border-slate-200 rounded-4 p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small text-slate-700 fw-bold">Progreso del Curso</span>
                            <span class="badge text-white fw-bold" id="progressPercentBadgeDisc" style="background-color: #7e22ce;">0%</span>
                        </div>
                        <div class="progress bg-slate-200 rounded-pill mb-2" style="height: 10px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressBarDisc" style="width: 0%; background-color: #7e22ce;"></div>
                        </div>
                        <div class="d-flex justify-content-between text-xs text-slate-500">
                            <span id="completedItemsCountDisc">0 de 5 módulos aprobados</span>
                            <span id="lockStatusNoticeDisc">🔒 Examen final bloqueado</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- CUERPO PRINCIPAL (DISEÑO BLANCO DE 2 COLUMNAS) -->
    <div class="container-fluid max-w-7xl mx-auto px-4 py-4">
        <div class="row g-4">
            
            <!-- COLUMNA IZQUIERDA: LISTA DE MÓDULOS (4 cols) -->
            <div class="col-12 col-lg-4">
                <div class="card border border-slate-200 rounded-4 shadow-sm bg-white overflow-hidden sticky-top" style="top: 1rem; z-index: 10;">
                    <div class="card-header bg-slate-50 border-bottom border-slate-200 py-3 px-4 d-flex justify-content-between align-items-center">
                        <h2 class="h6 fw-bold mb-0 text-slate-900">Estructura del Curso</h2>
                        <span class="badge bg-white border border-slate-300 text-slate-700 text-xs">5 Módulos</span>
                    </div>

                    <div class="accordion accordion-flush" id="courseAccordionDisc" style="max-height: 75vh; overflow-y: auto;">
                        
                        <!-- MÓDULO 1 -->
                        <div class="accordion-item border-bottom border-slate-100">
                            <h3 class="accordion-header" id="hDiscM1">
                                <button class="accordion-button py-3 px-4 fw-bold text-slate-800 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#cDiscM1">
                                    <div class="w-100 pe-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge text-xs font-semibold" style="background-color: #f3e8ff; color: #6b21a8;">Módulo 1</span>
                                            <span class="text-xs text-muted" id="badgeDisc-m1">Pendiente</span>
                                        </div>
                                        <div class="small fw-bold text-truncate">Lógica Proposicional y Conectivos</div>
                                    </div>
                                </button>
                            </h3>
                            <div id="cDiscM1" class="accordion-collapse collapse show">
                                <div class="accordion-body p-0">
                                    <div class="list-group list-group-flush small">
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn active py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLessonDisc('m1_video')" id="navDisc-m1_video">
                                            <span class="text-danger">📹</span>
                                            <span>1.1 Video Clase: Conectivos y Formalización</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLessonDisc('m1_pdf')" id="navDisc-m1_pdf">
                                            <span class="text-primary">📄</span>
                                            <span>1.2 Guía de Estudio y Leyes de Morgan (PDF)</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center justify-content-between bg-slate-50" onclick="loadLessonDisc('m1_quiz')" id="navDisc-m1_quiz">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-warning">📝</span>
                                                <span class="fw-bold">1.3 Examen del Módulo 1</span>
                                            </div>
                                            <span class="badge bg-white border border-slate-300 text-slate-600" id="quizStatusDisc-m1">10 preguntas</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MÓDULO 2 -->
                        <div class="accordion-item border-bottom border-slate-100">
                            <h3 class="accordion-header" id="hDiscM2">
                                <button class="accordion-button collapsed py-3 px-4 fw-bold text-slate-800 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#cDiscM2">
                                    <div class="w-100 pe-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge text-xs font-semibold" style="background-color: #f3e8ff; color: #6b21a8;">Módulo 2</span>
                                            <span class="text-xs text-muted" id="badgeDisc-m2">Pendiente</span>
                                        </div>
                                        <div class="small fw-bold text-truncate">Teoría de Conjuntos y Diagramas de Venn</div>
                                    </div>
                                </button>
                            </h3>
                            <div id="cDiscM2" class="accordion-collapse collapse">
                                <div class="accordion-body p-0">
                                    <div class="list-group list-group-flush small">
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLessonDisc('m2_video')" id="navDisc-m2_video">
                                            <span class="text-danger">📹</span>
                                            <span>2.1 Video Clase: Operaciones con Conjuntos</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLessonDisc('m2_pdf')" id="navDisc-m2_pdf">
                                            <span class="text-primary">📄</span>
                                            <span>2.2 Guía de Álgebra de Conjuntos (PDF)</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center justify-content-between bg-slate-50" onclick="loadLessonDisc('m2_quiz')" id="navDisc-m2_quiz">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-warning">📝</span>
                                                <span class="fw-bold">2.3 Examen del Módulo 2</span>
                                            </div>
                                            <span class="badge bg-white border border-slate-300 text-slate-600" id="quizStatusDisc-m2">10 preguntas</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MÓDULO 3 -->
                        <div class="accordion-item border-bottom border-slate-100">
                            <h3 class="accordion-header" id="hDiscM3">
                                <button class="accordion-button collapsed py-3 px-4 fw-bold text-slate-800 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#cDiscM3">
                                    <div class="w-100 pe-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge text-xs font-semibold" style="background-color: #f3e8ff; color: #6b21a8;">Módulo 3</span>
                                            <span class="text-xs text-muted" id="badgeDisc-m3">Pendiente</span>
                                        </div>
                                        <div class="small fw-bold text-truncate">Relaciones Binarias y Propiedades</div>
                                    </div>
                                </button>
                            </h3>
                            <div id="cDiscM3" class="accordion-collapse collapse">
                                <div class="accordion-body p-0">
                                    <div class="list-group list-group-flush small">
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLessonDisc('m3_video')" id="navDisc-m3_video">
                                            <span class="text-danger">📹</span>
                                            <span>3.1 Video Clase: Relaciones de Equivalencia</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLessonDisc('m3_pdf')" id="navDisc-m3_pdf">
                                            <span class="text-primary">📄</span>
                                            <span>3.2 Guía de Matrices de Relación (PDF)</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center justify-content-between bg-slate-50" onclick="loadLessonDisc('m3_quiz')" id="navDisc-m3_quiz">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-warning">📝</span>
                                                <span class="fw-bold">3.3 Examen del Módulo 3</span>
                                            </div>
                                            <span class="badge bg-white border border-slate-300 text-slate-600" id="quizStatusDisc-m3">10 preguntas</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MÓDULO 4 -->
                        <div class="accordion-item border-bottom border-slate-100">
                            <h3 class="accordion-header" id="hDiscM4">
                                <button class="accordion-button collapsed py-3 px-4 fw-bold text-slate-800 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#cDiscM4">
                                    <div class="w-100 pe-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge text-xs font-semibold" style="background-color: #f3e8ff; color: #6b21a8;">Módulo 4</span>
                                            <span class="text-xs text-muted" id="badgeDisc-m4">Pendiente</span>
                                        </div>
                                        <div class="small fw-bold text-truncate">Teoría de Grafos y Árboles</div>
                                    </div>
                                </button>
                            </h3>
                            <div id="cDiscM4" class="accordion-collapse collapse">
                                <div class="accordion-body p-0">
                                    <div class="list-group list-group-flush small">
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLessonDisc('m4_video')" id="navDisc-m4_video">
                                            <span class="text-danger">📹</span>
                                            <span>4.1 Video Clase: Vértices, Aristas y Conectividad</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLessonDisc('m4_pdf')" id="navDisc-m4_pdf">
                                            <span class="text-primary">📄</span>
                                            <span>4.2 Guía de Árboles y Caminos Mínimos (PDF)</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center justify-content-between bg-slate-50" onclick="loadLessonDisc('m4_quiz')" id="navDisc-m4_quiz">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-warning">📝</span>
                                                <span class="fw-bold">4.3 Examen del Módulo 4</span>
                                            </div>
                                            <span class="badge bg-white border border-slate-300 text-slate-600" id="quizStatusDisc-m4">10 preguntas</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MÓDULO 5 -->
                        <div class="accordion-item border-bottom border-slate-100">
                            <h3 class="accordion-header" id="hDiscM5">
                                <button class="accordion-button collapsed py-3 px-4 fw-bold text-slate-800 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#cDiscM5">
                                    <div class="w-100 pe-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge text-xs font-semibold" style="background-color: #f3e8ff; color: #6b21a8;">Módulo 5</span>
                                            <span class="text-xs text-muted" id="badgeDisc-m5">Pendiente</span>
                                        </div>
                                        <div class="small fw-bold text-truncate">Álgebra Booleana y Circuitos</div>
                                    </div>
                                </button>
                            </h3>
                            <div id="cDiscM5" class="accordion-collapse collapse">
                                <div class="accordion-body p-0">
                                    <div class="list-group list-group-flush small">
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLessonDisc('m5_video')" id="navDisc-m5_video">
                                            <span class="text-danger">📹</span>
                                            <span>5.1 Video Clase: Compuertas Lógicas</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLessonDisc('m5_pdf')" id="navDisc-m5_pdf">
                                            <span class="text-primary">📄</span>
                                            <span>5.2 Guía de Mapas de Karnaugh (PDF)</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center justify-content-between bg-slate-50" onclick="loadLessonDisc('m5_quiz')" id="navDisc-m5_quiz">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-warning">📝</span>
                                                <span class="fw-bold">5.3 Examen del Módulo 5</span>
                                            </div>
                                            <span class="badge bg-white border border-slate-300 text-slate-600" id="quizStatusDisc-m5">10 preguntas</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- EVALUACIÓN FINAL DEL CURSO (BLOQUEADA HASTA COMPLETAR LOS 5 MÓDULOS) -->
                        <div class="p-3 bg-slate-50 border-bottom border-slate-200">
                            <button type="button" class="btn btn-outline-purple btn-sm w-100 fw-bold d-flex justify-content-between align-items-center py-2.5" onclick="openFinalEvaluationDisc()" id="btnNavFinalQuizDisc">
                                <span class="d-flex align-items-center gap-2">
                                    <span id="finalLockIconDisc">🔒</span>
                                    <span>Evaluación Final del Curso</span>
                                </span>
                                <span class="badge bg-slate-200 text-slate-700" id="finalQuizBadgeDisc">Bloqueada</span>
                            </button>
                        </div>

                        <!-- CERTIFICADO FINAL (BLOQUEADO HASTA APROBAR LA EVALUACIÓN FINAL) -->
                        <div class="p-3 bg-white">
                            <button type="button" class="btn btn-purple btn-sm w-100 fw-bold d-flex justify-content-between align-items-center py-2.5 disabled" onclick="openCertificateDisc()" id="btnNavCertDisc" style="background-color: #7e22ce; color: white;">
                                <span class="d-flex align-items-center gap-2">
                                    <span>🏅</span>
                                    <span>Descargar Certificado Oficial</span>
                                </span>
                                <span class="badge bg-white text-purple border border-purple-200" id="finalCertBadgeDisc" style="color: #7e22ce;">Bloqueado</span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA: PANTALLA PRINCIPAL DE CONTENIDOS (8 cols) -->
            <div class="col-12 col-lg-8">
                
                <!-- 1. VISTA DE LECCIÓN ORDINARIA (VIDEO / PDF) -->
                <div class="card border border-slate-200 rounded-4 shadow-sm bg-white overflow-hidden" id="cardLessonViewDisc">
                    <div class="card-header bg-slate-50 border-bottom border-slate-200 p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                            <span class="badge fw-bold text-uppercase" id="lessonTagDisc" style="background-color: #f3e8ff; color: #6b21a8;">Módulo 1</span>
                            <span class="text-slate-500 small" id="lessonDurationDisc">⏱ Duración: 15 min</span>
                        </div>
                        <h2 class="h4 fw-bold text-slate-900 mb-1" id="lessonTitleDisc">1.1 Proposiciones y Conectivos Lógicos</h2>
                        <p class="text-slate-600 small mb-0" id="lessonSummaryDisc">Formalización matemática de enunciados del lenguaje natural con cálculo proposicional.</p>
                    </div>

                    <div class="card-body p-4">
                        <!-- Pestañas de la lección (Solo Video y Guía PDF) -->
                        <ul class="nav nav-tabs mb-4 border-bottom border-slate-200" id="lessonTabsDisc">
                            <li class="nav-item">
                                <button class="nav-link active fw-bold small text-slate-700" id="tab-video-link-disc" onclick="switchResourceTabDisc('video')">
                                    📹 Video de la Lección
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link fw-bold small text-slate-700" id="tab-pdf-link-disc" onclick="switchResourceTabDisc('pdf')">
                                    📄 Guía de Estudio y PDF Incrustado
                                </button>
                            </li>
                        </ul>

                        <!-- CONTENIDO PESTAÑA VIDEO -->
                        <div id="contentVideoBoxDisc">
                            <div class="ratio ratio-16x9 rounded-3 overflow-hidden bg-black mb-3 shadow-sm border border-slate-200">
                                <iframe id="videoPlayerDisc" src="https://www.youtube-nocookie.com/embed/pU6QhR54fcs?rel=0" allowfullscreen></iframe>
                            </div>
                            <div class="alert alert-light border border-slate-200 p-3 small text-slate-700 rounded-3">
                                <h6 class="fw-bold text-slate-900 mb-1">💡 Temas clave explicados en el video:</h6>
                                <p class="mb-0 text-slate-600" id="videoNotesDisc">Definición de proposiciones lógicas y tabla de verdad de conjunción, disyunción, condicional y bicondicional.</p>
                            </div>
                        </div>

                        <!-- CONTENIDO PESTAÑA VISOR DE PDF INCORPORADO -->
                        <div id="contentPdfBoxDisc" class="d-none">
                            <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-slate-50 border border-slate-200 rounded-3">
                                <div>
                                    <h6 class="fw-bold mb-0 text-slate-900" id="pdfHeadingTitleDisc">Guía Técnica de Matemáticas Discretas</h6>
                                    <span class="text-xs text-slate-500">Documento PDF oficial del curso</span>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm fw-bold shadow-sm" style="background-color: #7e22ce; border-color: #7e22ce;" onclick="downloadPdfDocDisc()">
                                    📥 Descargar PDF Oficial
                                </button>
                            </div>
                            
                            <!-- VISOR DE DOCUMENTO PDF EN FORMATO PAPEL ELEGANTE -->
                            <div class="p-4 bg-white border border-slate-200 rounded-3 shadow-xs" style="max-height: 480px; overflow-y: auto;">
                                <div class="border-bottom border-slate-200 pb-3 mb-3 text-center">
                                    <div class="text-xs text-uppercase fw-bold tracking-wider" style="color: #7e22ce;">TECNOLÓGICO NACIONAL DE MÉXICO · CIENCIAS BÁSICAS</div>
                                    <h3 class="h5 fw-bold text-slate-900 mt-1" id="pdfDocumentHeadingDisc">Manual Didáctico: Lógica Proposicional y Equivalencias</h3>
                                    <p class="text-muted small mb-0">Material de lectura y preparación para el examen del módulo</p>
                                </div>
                                <div id="pdfBodyHtmlDisc" class="small text-slate-700 leading-relaxed space-y-3">
                                    <!-- Contenido dinámico del PDF -->
                                </div>
                            </div>
                        </div>

                        <!-- Barra de navegación inferior -->
                        <div class="d-flex justify-content-between align-items-center pt-4 border-top border-slate-200 mt-4">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="navigateStepDisc(-1)">
                                ← Anterior
                            </button>
                            <button type="button" class="btn btn-primary btn-sm fw-bold px-4" style="background-color: #7e22ce; border-color: #7e22ce;" onclick="navigateStepDisc(1)">
                                Continuar al Examen del Módulo →
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 2. VISTA DE EXAMEN DE CADA MÓDULO -->
                <div class="card border border-slate-200 rounded-4 shadow-sm bg-white overflow-hidden d-none" id="cardModuleQuizViewDisc">
                    <div class="card-header bg-slate-50 border-bottom border-slate-200 p-4">
                        <span class="badge fw-bold text-uppercase mb-2" id="quizModuleBadgeDisc" style="background-color: #f3e8ff; color: #6b21a8;">Módulo 1</span>
                        <h2 class="h4 fw-bold text-slate-900 mb-1" id="quizModuleTitleDisc">Examen de Conocimientos: Lógica Proposicional</h2>
                        <p class="text-slate-600 small mb-0">Responde las 3 preguntas basadas en el video y la guía PDF para aprobar este módulo.</p>
                    </div>
                    <div class="card-body p-4">
                        <form id="formModuleQuizDisc" onsubmit="submitModuleQuizDisc(event)">
                            <div id="quizQuestionsContainerDisc" class="space-y-4">
                                <!-- Preguntas dinámicas por módulo -->
                            </div>

                            <div class="mt-4 pt-3 border-top border-slate-200 d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="backToLessonDisc()">← Volver a la guía/video</button>
                                <button type="submit" class="btn fw-bold px-4 text-white" style="background-color: #7e22ce;">Calificar Examen del Módulo ▷</button>
                            </div>
                        </form>

                        <div id="quizModuleResultDisc" class="mt-4 p-3 rounded-3 d-none text-center"></div>
                    </div>
                </div>

                <!-- 3. AVISO DE EVALUACIÓN FINAL BLOQUEADA -->
                <div class="card border border-slate-200 rounded-4 shadow-sm bg-white p-5 text-center d-none" id="cardLockedViewDisc">
                    <div class="py-4">
                        <div class="fs-1 mb-3">🔒</div>
                        <h3 class="h4 fw-bold text-slate-900 mb-2">Evaluación Final Bloqueada</h3>
                        <p class="text-slate-600 small max-w-lg mx-auto mb-4 leading-relaxed">
                            Para desbloquear la evaluación final de Matemáticas Discretas y obtener tu certificado oficial, primero debes <strong>aprobar los exámenes de los 5 módulos</strong>.
                        </p>
                        <div class="d-inline-block bg-slate-50 border border-slate-200 rounded-3 p-3 text-start small mb-4">
                            <div class="fw-bold text-slate-800 mb-2">Estado de tus módulos:</div>
                            <ul class="list-unstyled mb-0 space-y-1 text-slate-600">
                                <li>Módulo 1: <span id="lockDisc-stat-m1" class="fw-semibold text-danger">Pendiente</span></li>
                                <li>Módulo 2: <span id="lockDisc-stat-m2" class="fw-semibold text-danger">Pendiente</span></li>
                                <li>Módulo 3: <span id="lockDisc-stat-m3" class="fw-semibold text-danger">Pendiente</span></li>
                                <li>Módulo 4: <span id="lockDisc-stat-m4" class="fw-semibold text-danger">Pendiente</span></li>
                                <li>Módulo 5: <span id="lockDisc-stat-m5" class="fw-semibold text-danger">Pendiente</span></li>
                            </ul>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm fw-bold px-4 text-white" style="background-color: #7e22ce;" onclick="loadLessonDisc('m1_video')">
                                Continuar con Módulo 1 ▷
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 4. VISTA DE EVALUACIÓN FINAL DEL CURSO -->
                <div class="card border border-slate-200 rounded-4 shadow-sm bg-white overflow-hidden d-none" id="cardFinalQuizViewDisc">
                    <div class="card-header text-white p-4" style="background-color: #6b21a8;">
                        <span class="badge bg-white text-purple fw-bold text-uppercase mb-2" style="color: #6b21a8;">Evaluación Integral</span>
                        <h2 class="h4 fw-bold text-white mb-1">Examen Final de Matemáticas Discretas</h2>
                        <p class="text-purple-100 small mb-0">Has aprobado los 5 módulos. Demuestra tus competencias lógicas integradas para expedir tu certificado.</p>
                    </div>
                    <div class="card-body p-4">
                        <form id="formFinalQuizDisc" onsubmit="submitFinalQuizDisc(event)">
                            <div id="finalQuizQuestionsContainerDisc" class="space-y-4" style="max-height: 600px; overflow-y: auto; padding-right: 8px;">
                                <!-- Inyección de las 15 preguntas integrales -->
                            </div>

                            <!-- Encuesta de satisfacción -->
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-3 my-4">
                                <h6 class="fw-bold text-slate-900 mb-1">Encuesta de Experiencia del Curso:</h6>
                                <p class="text-xs text-slate-500 mb-2">¿Las explicaciones en video y lecturas PDF te permitieron comprender los conceptos discretos?</p>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="final_sat_disc" checked id="dsat1">
                                        <label class="form-check-label small" for="dsat1">Excelente ⭐⭐⭐⭐⭐</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="final_sat_disc" id="dsat2">
                                        <label class="form-check-label small" for="dsat2">Muy bueno ⭐⭐⭐⭐</label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn text-white fw-bold w-100 py-2.5 shadow-sm" style="background-color: #7e22ce;">
                                Calificar Examen Final (15 preguntas) y Expedir Certificado ▷
                            </button>
                        </form>
                    </div>
                </div>

                <!-- 5. VISTA DEL CERTIFICADO FINAL -->
                <div class="card border border-slate-200 rounded-4 shadow-sm bg-white overflow-hidden d-none" id="cardCertificateViewDisc">
                    <div class="card-header bg-slate-50 border-bottom border-slate-200 p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold text-uppercase mb-1">Acreditación Obtenida</span>
                            <h2 class="h4 fw-bold text-slate-900 mb-0">Certificado Oficial</h2>
                        </div>
                        <button type="button" class="btn btn-sm fw-bold shadow-sm text-white" style="background-color: #7e22ce;" onclick="window.print()">
                            📥 Imprimir / Guardar en PDF
                        </button>
                    </div>
                    
                    <div class="card-body p-4 bg-slate-100">
                        <div class="certificate-paper bg-white p-5 mx-auto border shadow-sm rounded-3 position-relative" id="certDiplomaDisc" style="max-width: 820px; border: 6px double #7e22ce !important; min-height: 500px;">
                            <div class="text-center mb-4">
                                <div class="text-xs text-uppercase tracking-widest text-muted fw-bold mb-1">TECNOLÓGICO NACIONAL DE MÉXICO · IT CIUDAD VICTORIA</div>
                                <h3 class="fw-bold text-slate-900" style="font-family: Georgia, serif; font-size: 1.8rem;">CERTIFICADO DE APROBACIÓN</h3>
                                <p class="text-muted text-xs">Se otorga la presente constancia con valor curricular a:</p>
                            </div>

                            <div class="text-center my-4">
                                <h2 class="fw-bold display-6 border-bottom border-slate-300 d-inline-block pb-2 px-4" id="certStudentNameDisc" style="color: #7e22ce;">
                                    Alejandro (Estudiante)
                                </h2>
                                <p class="text-slate-600 small mt-2">
                                    Por haber completado y aprobado satisfactoriamente los 5 módulos del programa:
                                </p>
                                <h4 class="fw-bold text-slate-900 text-uppercase tracking-wide">
                                    Matemáticas Discretas y Estructuras Lógicas
                                </h4>
                                <p class="small text-muted mb-0">Comprendiendo lógica proposicional, teoría de conjuntos, relaciones binarias, grafos y circuitos booleanos con evaluaciones de 10 y 15 reactivos.</p>
                            </div>

                            <div class="row align-items-end mt-5 pt-4 text-center">
                                <div class="col-4">
                                    <div class="border-top border-slate-400 pt-1 small fw-bold">Dra. Marlene / Coordinación</div>
                                    <div class="text-xs text-muted">Comité Académico Tec</div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 border rounded bg-slate-50 d-inline-block">
                                        <div class="fw-bold text-slate-800 text-xs">SELLO DIGITAL</div>
                                        <div class="font-monospace text-xs text-muted">FOLIO: LV-DISC-2026-4412</div>
                                    </div>
                                    <div class="text-xs text-muted mt-1">Fecha: 08/11/2026</div>
                                </div>
                                <div class="col-4">
                                    <div class="border-top border-slate-400 pt-1 small fw-bold">Dirección Académica</div>
                                    <div class="text-xs text-muted">Lógica Visual ABP</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
.btn-outline-purple {
    border-color: #d8b4fe;
    color: #7e22ce;
}
.btn-outline-purple:hover {
    background-color: #7e22ce;
    color: #ffffff;
}
.mod-nav-pill.active {
    background-color: #7e22ce !important;
    color: #ffffff !important;
    border-color: #7e22ce !important;
    font-weight: bold;
}
@media print {
    body * { visibility: hidden; }
    #certDiplomaDisc, #certDiplomaDisc * { visibility: visible; }
    #certDiplomaDisc {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        box-shadow: none !important;
    }
}
</style>

<script>
// DATOS DEL CURSO DE MATEMÁTICAS DISCRETAS (10 PREGUNTAS POR MÓDULO + 15 PREGUNTAS FINALES)
const modulesDataDisc = {
    'm1': {
        title: 'Módulo 1: Lógica Proposicional y Conectivos',
        tag: 'Módulo 1',
        dur: '15 min',
        videoUrl: 'https://www.youtube-nocookie.com/embed/pU6QhR54fcs?rel=0',
        videoNotes: 'Concepto formal de proposición y comportamiento de los operadores lógicos AND, OR, NOT, condicional y bicondicional.',
        pdfHeading: 'Manual de Lógica Proposicional y Tablas de Verdad',
        pdfText: `
            <h6>1. Proposiciones y Conectivos Lógicos</h6>
            <p>Una proposición es un enunciado declarativo que toma exclusivamente el valor de Verdadero (V) o Falso (F).</p>
            <h6>2. Tabla de Conectivos Fundamentales:</h6>
            <ul>
                <li><strong>Conjunción (p ∧ q):</strong> Verdadera únicamente si ambas proposiciones son verdaderas.</li>
                <li><strong>Disyunción (p ∨ q):</strong> Falsa únicamente si ambas proposiciones son falsas.</li>
                <li><strong>Condicional (p → q):</strong> Falsa únicamente cuando el antecedente es verdadero y el consecuente falso.</li>
                <li><strong>Bicondicional (p ↔ q):</strong> Verdadera cuando ambas proposiciones tienen el mismo valor de verdad.</li>
            </ul>
        `,
        questions: [
            { q: "1. ¿Cuál de los siguientes enunciados constituye una proposición lógica válida?", options: ["El número 7 es un número primo.", "¿Qué hora es?", "¡Cierra la puerta por favor!"], correct: 0 },
            { q: "2. En lógica formal, la conjunción (p ∧ q) es VERDADERA únicamente cuando:", options: ["Ambas proposiciones p y q son verdaderas.", "Al menos una de las dos es verdadera.", "p es falsa y q es verdadera."], correct: 0 },
            { q: "3. La disyunción inclusiva (p ∨ q) resulta FALSA en el caso exclusivo de que:", options: ["Tanto p como q sean falsas.", "p sea verdadera y q sea falsa.", "Ambas sean verdaderas."], correct: 0 },
            { q: "4. ¿En qué única combinación de valores la condicional (p → q) es FALSA?", options: ["Cuando el antecedente p es V y el consecuente q es F.", "Cuando p es F y q es V.", "Cuando ambas proposiciones son falsas."], correct: 0 },
            { q: "5. La proposición bicondicional (p ↔ q) es verdadera si y solo si:", options: ["Ambas proposiciones comparten el mismo valor de verdad (ambas V o ambas F).", "El antecedente es necesariamente falso.", "Una es V y la otra es F."], correct: 0 },
            { q: "6. Según las Leyes de De Morgan, la negación de una conjunción ¬(p ∧ q) equivale lógicamente a:", options: ["¬p ∨ ¬q", "¬p ∧ ¬q", "p ∨ q"], correct: 0 },
            { q: "7. Según las Leyes de De Morgan, la negación de una disyunción ¬(p ∨ q) equivale lógicamente a:", options: ["¬p ∧ ¬q", "¬p ∨ ¬q", "¬p → ¬q"], correct: 0 },
            { q: "8. Una forma proposicional cuya columna final en la tabla de verdad contiene únicamente valores Verdaderos (V) se denomina:", options: ["Tautología.", "Contradicción.", "Contingencia."], correct: 0 },
            { q: "9. Si una tabla de verdad produce únicamente valores Falsos (F) para todas las combinaciones posibles, representa una:", options: ["Contradicción o falacia.", "Tautología.", "Bicondicional."], correct: 0 },
            { q: "10. La disyunción exclusiva (p ⊕ q o XOR) es verdadera cuando:", options: ["Exactamente una de las dos proposiciones es verdadera, pero no ambas.", "Ambas proposiciones son simultáneamente verdaderas.", "Ambas proposiciones son falsas."], correct: 0 }
        ]
    },
    'm2': {
        title: 'Módulo 2: Teoría de Conjuntos y Diagramas de Venn',
        tag: 'Módulo 2',
        dur: '20 min',
        videoUrl: 'https://www.youtube-nocookie.com/embed/7R780U8oNkc?rel=0',
        videoNotes: 'Aprende notación de pertenencia, subconjuntos, unión, intersección, diferencia y complemento con visualización gráfica.',
        pdfHeading: 'Álgebra de Conjuntos y Reglas de Cardinalidad',
        pdfText: `
            <h6>1. Operaciones Básicas entre Conjuntos</h6>
            <p><strong>Unión (A ∪ B):</strong> Elementos que pertenecen a A o a B.<br>
            <strong>Intersección (A ∩ B):</strong> Elementos compartidos por ambos.<br>
            <strong>Diferencia (A - B):</strong> Elementos de A que no están en B.<br>
            <strong>Complemento (A'):</strong> Elementos del Universo U que no están en A.</p>
        `,
        questions: [
            { q: "1. Si un elemento 'x' forma parte del conjunto A, la notación matemática correcta es:", options: ["x ∈ A", "x ⊂ A", "x ⊆ A"], correct: 0 },
            { q: "2. Decimos que A es un subconjunto de B (A ⊆ B) si y solo si:", options: ["Todo elemento de A pertenece también a B.", "A y B tienen exactamente la misma cantidad de elementos.", "B es un conjunto vacío."], correct: 0 },
            { q: "3. ¿Cuál es la cardinalidad |∅| del conjunto vacío?", options: ["0 elementos.", "1 elemento.", "Infinitos elementos."], correct: 0 },
            { q: "4. Si un conjunto A posee n = 3 elementos, ¿cuántos subconjuntos contiene su conjunto potencia P(A)?", options: ["8 subconjuntos (2^3).", "6 subconjuntos.", "9 subconjuntos."], correct: 0 },
            { q: "5. Dados los conjuntos A = {1, 2, 3, 4} y B = {3, 4, 5, 6}, la unión A ∪ B es:", options: ["{1, 2, 3, 4, 5, 6}", "{3, 4}", "{1, 2, 5, 6}"], correct: 0 },
            { q: "6. Dados los conjuntos A = {1, 2, 3, 4} y B = {3, 4, 5, 6}, la intersección A ∩ B es:", options: ["{3, 4}", "{1, 2}", "{5, 6}"], correct: 0 },
            { q: "7. Si dos conjuntos A y B no comparten ningún elemento (A ∩ B = ∅), se denominan:", options: ["Conjuntos disjuntos o ajenos.", "Conjuntos equivalentes.", "Conjuntos universales."], correct: 0 },
            { q: "8. La diferencia de conjuntos A - B representa el conjunto de elementos que:", options: ["Pertenecen a A pero no pertenecen a B.", "Pertenecen a B pero no a A.", "Pertenecen a ambos al mismo tiempo."], correct: 0 },
            { q: "9. El complemento de un conjunto A (denotado A' o A^c) respecto al universo U consiste en:", options: ["Todos los elementos de U que no pertenecen a A.", "Todos los elementos compartidos entre U y A.", "El conjunto vacío."], correct: 0 },
            { q: "10. Según el Principio de Inclusión-Exclusión para dos conjuntos finitos, |A ∪ B| es igual a:", options: ["|A| + |B| - |A ∩ B|", "|A| + |B| + |A ∩ B|", "|A| * |B|"], correct: 0 }
        ]
    },
    'm3': {
        title: 'Módulo 3: Relaciones Binarias y Propiedades',
        tag: 'Módulo 3',
        dur: '20 min',
        videoUrl: 'https://www.youtube-nocookie.com/embed/4C5-eF_9w7s?rel=0',
        videoNotes: 'Propiedades reflexiva, simétrica, transitiva y antisimétrica en relaciones sobre conjuntos.',
        pdfHeading: 'Clasificación de Relaciones de Equivalencia y Orden',
        pdfText: `
            <h6>1. Relación de Equivalencia</h6>
            <p>Una relación binaria R sobre un conjunto A es de equivalencia si cumple de forma simultánea con ser <strong>Reflexiva</strong>, <strong>Simétrica</strong> y <strong>Transitiva</strong>.</p>
            <h6>2. Relación de Orden Parcial</h6>
            <p>Es una relación que cumple con ser <strong>Reflexiva</strong>, <strong>Antisimétrica</strong> y <strong>Transitiva</strong> (Poset).</p>
        `,
        questions: [
            { q: "1. El producto cartesiano A × B entre A = {1, 2} y B = {x, y} genera pares ordenados. ¿Cuántos pares se obtienen?", options: ["4 pares ordenados: (1,x), (1,y), (2,x), (2,y).", "2 pares ordenados.", "8 pares ordenados."], correct: 0 },
            { q: "2. Una relación binaria R de A en B se define formalmente como:", options: ["Cualquier subconjunto del producto cartesiano A × B.", "Una función inyectiva obligatoria.", "La unión de A y B."], correct: 0 },
            { q: "3. Una relación R sobre el conjunto A es REFLEXIVA si:", options: ["Para todo a ∈ A, el par (a, a) ∈ R.", "Si (a, b) ∈ R entonces (b, a) ∈ R.", "Nunca contiene pares idénticos."], correct: 0 },
            { q: "4. Una relación R sobre A es SIMÉTRICA si:", options: ["Siempre que (a, b) ∈ R, también se cumple que (b, a) ∈ R.", "Para todo a, (a, a) ∈ R.", "Si (a, b) y (b, c) ∈ R, entonces (a, c) ∈ R."], correct: 0 },
            { q: "5. Una relación R sobre A es TRANSITIVA si:", options: ["Si (a, b) ∈ R y (b, c) ∈ R, entonces necesariamente (a, c) ∈ R.", "Si (a, b) ∈ R entonces a = b.", "No tiene ciclos."], correct: 0 },
            { q: "6. Una relación R sobre A es ANTISIMÉTRICA si:", options: ["Si (a, b) ∈ R y (b, a) ∈ R, entonces forzosamente a = b.", "Nunca tiene elementos simétricos.", "Todos los pares son reflexivos."], correct: 0 },
            { q: "7. Para que una relación binaria sea clasificada como RELACIÓN DE EQUIVALENCIA, debe cumplir:", options: ["Ser reflexiva, simétrica y transitiva simultáneamente.", "Ser reflexiva, antisimétrica y transitiva.", "Ser solo simétrica y reflexiva."], correct: 0 },
            { q: "8. Las clases de equivalencia inducidas por una relación de equivalencia sobre A generan:", options: ["Una partición del conjunto A en subconjuntos disjuntos no vacíos.", "El producto cartesiano total.", "Un grafo inconexo."], correct: 0 },
            { q: "9. Una relación binaria que es reflexiva, ANTISIMÉTRICA y transitiva define una relación de:", options: ["Orden parcial (conjunto parcialmente ordenado / Poset).", "Equivalencia.", "Función biyectiva."], correct: 0 },
            { q: "10. La relación 'menor o igual que' (≤) en el conjunto de los números enteros es un ejemplo de:", options: ["Relación de orden parcial.", "Relación de equivalencia.", "Relación no transitiva."], correct: 0 }
        ]
    },
    'm4': {
        title: 'Módulo 4: Teoría de Grafos y Árboles',
        tag: 'Módulo 4',
        dur: '20 min',
        videoUrl: 'https://www.youtube-nocookie.com/embed/qZ8i_51f0qE?rel=0',
        videoNotes: 'Estructuras de datos en red formadas por vértices y aristas, cálculo de grado y árboles generadores mínimos.',
        pdfHeading: 'Grafos, Caminos y Algoritmos de Árboles (Kruskal/Prim)',
        pdfText: `
            <h6>1. Teorema Fundamental del Apretón de Manos</h6>
            <p>La suma de los grados de todos los vértices en un grafo es exactamente el doble del número de aristas: ∑ deg(v) = 2|E|.</p>
            <h6>2. Árboles</h6>
            <p>Un árbol es un grafo conexo y acíclico. En todo árbol con 'n' vértices existen exactamente 'n - 1' aristas.</p>
        `,
        questions: [
            { q: "1. Un grafo G = (V, E) está compuesto formalmente por:", options: ["Un conjunto de vértices (nodos) V y un conjunto de aristas (enlaces) E.", "Una matriz cuadrada de números primos.", "Un conjunto de funciones."], correct: 0 },
            { q: "2. En un grafo no dirigido, el 'grado' (o valencia) de un vértice se define como:", options: ["El número de aristas incidentes que tocan a dicho vértice.", "La cantidad total de vértices en el grafo.", "El peso de su arista más larga."], correct: 0 },
            { q: "3. Según el Teorema del Apretón de Manos (Handshaking Lemma), la suma de los grados de todos los vértices es igual a:", options: ["El doble del número de aristas (2|E|).", "El número de aristas al cuadrado.", "El número de vértices |V|."], correct: 0 },
            { q: "4. Una consecuencia directa del Lema del Apretón de Manos es que en cualquier grafo:", options: ["El número de vértices de grado impar siempre es par.", "Todos los vértices deben tener grado par.", "No pueden existir bucles."], correct: 0 },
            { q: "5. ¿Qué es un 'camino simple' en un grafo?", options: ["Una sucesión de vértices adyacentes donde ningún vértice se repite.", "Un camino que pasa por todas las aristas dos veces.", "Un grafo con un solo vértice."], correct: 0 },
            { q: "6. Un grafo se dice 'conexo' si:", options: ["Existe al menos un camino entre cualquier par de vértices del grafo.", "Todos los vértices tienen exactamente el mismo grado.", "No contiene ciclos."], correct: 0 },
            { q: "7. ¿Cuál es la definición matemática formal de un ÁRBOL?", options: ["Un grafo no dirigido, conexo y sin ciclos (acíclico).", "Cualquier grafo dirigido.", "Un grafo con ciclos eulerianos."], correct: 0 },
            { q: "8. Si un árbol conexo posee n vértices, ¿cuántas aristas contiene exactamente?", options: ["Exactamente n - 1 aristas.", "Exactamente n aristas.", "Exactamente n + 1 aristas."], correct: 0 },
            { q: "9. En un árbol con raíz, un vértice que no tiene ningún descendiente o hijo se conoce como:", options: ["Hoja (nodo terminal).", "Nodo raíz.", "Nodo rama."], correct: 0 },
            { q: "10. Un 'árbol generador' (spanning tree) de un grafo conexo G es:", options: ["Un subgrafo que contiene todos los vértices de G y es un árbol.", "Un grafo que duplica las aristas de G.", "Un árbol binario completo."], correct: 0 }
        ]
    },
    'm5': {
        title: 'Módulo 5: Álgebra Booleana y Circuitos Lógicos',
        tag: 'Módulo 5',
        dur: '20 min',
        videoUrl: 'https://www.youtube-nocookie.com/embed/8eWl_j8yDkQ?rel=0',
        videoNotes: 'Compuertas lógicas fundamentales AND, OR, NOT, XOR y minimización de funciones booleanas.',
        pdfHeading: 'Postulados de Boole y Mapas de Karnaugh',
        pdfText: `
            <h6>1. Compuertas Lógicas</h6>
            <p><strong>AND:</strong> Salida 1 solo si todas las entradas son 1.<br>
            <strong>OR:</strong> Salida 1 si al menos una entrada es 1.<br>
            <strong>NOT:</strong> Invierte el bit (0 a 1, 1 a 0).<br>
            <strong>XOR:</strong> Salida 1 si las entradas son diferentes.</p>
        `,
        questions: [
            { q: "1. El álgebra booleana opera sobre un conjunto fundamental que consta únicamente de dos valores discretos:", options: ["{0, 1} (Falso y Verdadero).", "Números enteros infinitos.", "Números reales entre 0 y 1."], correct: 0 },
            { q: "2. En compuertas lógicas digitales, la compuerta AND produce una salida en nivel alto (1) si y solo si:", options: ["Todas sus entradas se encuentran en nivel alto (1).", "Al menos una entrada está en 1.", "Todas sus entradas están en 0."], correct: 0 },
            { q: "3. La compuerta lógica OR produce una salida en nivel bajo (0) únicamente cuando:", options: ["Todas sus entradas están en nivel bajo (0).", "Cualquiera de sus entradas está en 1.", "La entrada es par."], correct: 0 },
            { q: "4. La compuerta NOT (inversor) realiza la siguiente función lógica:", options: ["Invierte el valor de entrada: si entra 1 sale 0, y si entra 0 sale 1.", "Multiplica por 2 la entrada.", "Mantiene la señal idéntica."], correct: 0 },
            { q: "5. ¿Por qué las compuertas NAND y NOR se denominan 'compuertas lógicas universales'?", options: ["Porque cualquier función o circuito booleano puede implementarse exclusivamente con ellas.", "Porque son las más caras de fabricar.", "Porque solo funcionan en microprocesadores cuánticos."], correct: 0 },
            { q: "6. La compuerta XOR (OR exclusiva) genera una salida de 1 cuando:", options: ["Las dos entradas tienen valores lógicos distintos entre sí.", "Ambas entradas son idénticas a 1.", "Ambas entradas son 0."], correct: 0 },
            { q: "7. Según el postulado de Idempotencia en álgebra de Boole:", options: ["A + A = A y A · A = A", "A + A = 2A", "A · A = 1"], correct: 0 },
            { q: "8. La Ley de Absorción en álgebra booleana establece que A + (A · B) es igual a:", options: ["A", "B", "A · B"], correct: 0 },
            { q: "9. Aplicando la Ley de De Morgan al circuito lógico, la compuerta equivalente a (A · B)' es:", options: ["A' + B' (una suma booleana de entradas negadas).", "A' · B'", "A + B"], correct: 0 },
            { q: "10. En el diseño de circuitos digitales, ¿cuál es el objetivo principal de los Mapas de Karnaugh?", options: ["Simplificar expresiones booleanas para minimizar la cantidad de compuertas lógicas.", "Aumentar el consumo de energía.", "Contar el número de cables físicos."], correct: 0 }
        ]
    }
};

// 15 PREGUNTAS INTEGRADORAS PARA EL EXAMEN FINAL DE MATEMÁTICAS DISCRETAS
const finalQuestionsDisc = [
    { q: "1. ¿Cuál de las siguientes expresiones representa una proposición lógica unívoca?", options: ["5 es mayor que 2.", "¿Qué día es hoy?", "¡Viva México!"], correct: 0 },
    { q: "2. ¿En qué situación la condicional (p → q) es falsa?", options: ["p es verdadera y q es falsa.", "p es falsa y q es verdadera.", "Ambas son falsas."], correct: 0 },
    { q: "3. Según las leyes de De Morgan, ¬(p ∧ q) equivale lógicamente a:", options: ["¬p ∨ ¬q", "¬p ∧ ¬q", "p ∧ ¬q"], correct: 0 },
    { q: "4. Una proposición compuesta que resulta verdadera para cualquier asignación de verdad se llama:", options: ["Tautología.", "Contradicción.", "Contingencia."], correct: 0 },
    { q: "5. Si el conjunto A tiene 4 elementos, ¿cuántos subconjuntos tiene su conjunto potencia P(A)?", options: ["16 subconjuntos (2^4).", "8 subconjuntos.", "12 subconjuntos."], correct: 0 },
    { q: "6. Si A = {2, 4, 6} y B = {4, 6, 8}, ¿cuál es la intersección A ∩ B?", options: ["{4, 6}", "{2, 8}", "{2, 4, 6, 8}"], correct: 0 },
    { q: "7. Si dos conjuntos A y B cumplen que A ∩ B = ∅, se denominan:", options: ["Disjuntos o ajenos.", "Equipotentes.", "Universales."], correct: 0 },
    { q: "8. Una relación binaria R es reflexiva sobre A si:", options: ["(a, a) ∈ R para todo elemento a ∈ A.", "Si (a, b) ∈ R entonces (b, a) ∈ R.", "No contiene pares repetidos."], correct: 0 },
    { q: "9. Una relación de equivalencia satisface obligatoriamente:", options: ["Reflexividad, Simetría y Transitividad.", "Reflexividad, Antimetría y Transitividad.", "Solo Simetría y Antimetría."], correct: 0 },
    { q: "10. Una relación que es reflexiva, antisimétrica y transitiva define:", options: ["Un orden parcial (Poset).", "Una equivalencia estricta.", "Una función continua."], correct: 0 },
    { q: "11. En un grafo no dirigido con 10 vértices y 14 aristas, ¿cuánto vale la suma de los grados de todos los vértices?", options: ["28 (el doble de aristas: 2 * 14).", "14.", "20."], correct: 0 },
    { q: "12. ¿Cuántas aristas tiene un árbol conexo no dirigido con 9 vértices?", options: ["8 aristas (n - 1).", "9 aristas.", "10 aristas."], correct: 0 },
    { q: "13. En un árbol con raíz, un vértice sin hijos recibe el nombre de:", options: ["Hoja.", "Raíz.", "Arista generadora."], correct: 0 },
    { q: "14. En compuertas lógicas digitales, ¿cuál produce salida 1 solo cuando todas sus entradas son 1?", options: ["Compuerta AND.", "Compuerta OR.", "Compuerta XOR."], correct: 0 },
    { q: "15. Las compuertas NAND y NOR se llaman universales porque:", options: ["Permiten implementar cualquier función o circuito booleano sin necesidad de otras compuertas.", "Soportan mayor voltaje.", "Solo se usan en telecomunicaciones."], correct: 0 }
];

let currentModKeyDisc = 'm1';
let passedModulesDisc = JSON.parse(localStorage.getItem('passed_modules_disc_bank') || '[]');

function initDisc() {
    updateProgressStateDisc();
    loadLessonDisc('m1_video');
}

function loadLessonDisc(targetKey) {
    const parts = targetKey.split('_');
    const modKey = parts[0];
    const resource = parts[1];

    currentModKeyDisc = modKey;
    const data = modulesDataDisc[modKey];
    if (!data) return;

    if (resource === 'quiz') {
        showModuleQuizDisc(modKey);
        return;
    }

    document.getElementById('cardLessonViewDisc').classList.remove('d-none');
    document.getElementById('cardModuleQuizViewDisc').classList.add('d-none');
    document.getElementById('cardLockedViewDisc').classList.add('d-none');
    document.getElementById('cardFinalQuizViewDisc').classList.add('d-none');
    document.getElementById('cardCertificateViewDisc').classList.add('d-none');

    document.getElementById('lessonTagDisc').innerText = data.tag;
    document.getElementById('lessonDurationDisc').innerText = '⏱ ' + data.dur;
    document.getElementById('lessonTitleDisc').innerText = data.title;
    document.getElementById('videoPlayerDisc').src = data.videoUrl;
    document.getElementById('videoNotesDisc').innerText = data.videoNotes;
    document.getElementById('pdfHeadingTitleDisc').innerText = data.pdfHeading;
    document.getElementById('pdfDocumentHeadingDisc').innerText = data.pdfHeading;
    document.getElementById('pdfBodyHtmlDisc').innerHTML = data.pdfText;

    switchResourceTabDisc(resource === 'pdf' ? 'pdf' : 'video');
    highlightNavBtnDisc('navDisc-' + targetKey);
}

function switchResourceTabDisc(type) {
    const tabVid = document.getElementById('tab-video-link-disc');
    const tabPdf = document.getElementById('tab-pdf-link-disc');
    const boxVid = document.getElementById('contentVideoBoxDisc');
    const boxPdf = document.getElementById('contentPdfBoxDisc');

    if (type === 'video') {
        tabVid.classList.add('active');
        tabPdf.classList.remove('active');
        boxVid.classList.remove('d-none');
        boxPdf.classList.add('d-none');
    } else {
        tabPdf.classList.add('active');
        tabVid.classList.remove('active');
        boxPdf.classList.remove('d-none');
        boxVid.classList.add('d-none');
    }
}

function highlightNavBtnDisc(btnId) {
    document.querySelectorAll('#courseAccordionDisc .lesson-btn').forEach(b => b.classList.remove('active'));
    const btn = document.getElementById(btnId);
    if (btn) btn.classList.add('active');
}

function showModuleQuizDisc(modKey) {
    const data = modulesDataDisc[modKey];
    document.getElementById('cardLessonViewDisc').classList.add('d-none');
    document.getElementById('cardModuleQuizViewDisc').classList.remove('d-none');
    document.getElementById('cardLockedViewDisc').classList.add('d-none');
    document.getElementById('cardFinalQuizViewDisc').classList.add('d-none');
    document.getElementById('cardCertificateViewDisc').classList.add('d-none');

    document.getElementById('quizModuleBadgeDisc').innerText = data.tag;
    document.getElementById('quizModuleTitleDisc').innerText = 'Examen del ' + data.title + ' (10 preguntas)';
    document.getElementById('quizModuleResultDisc').classList.add('d-none');

    const container = document.getElementById('quizQuestionsContainerDisc');
    container.innerHTML = '';
    data.questions.forEach((qObj, idx) => {
        let optsHtml = '';
        qObj.options.forEach((opt, optIdx) => {
            optsHtml += `
                <div class="form-check mb-1">
                    <input class="form-check-input" type="radio" name="mdq_${idx}" value="${optIdx}" id="mdq_${idx}_${optIdx}" required>
                    <label class="form-check-label small text-slate-700" for="mdq_${idx}_${optIdx}">${opt}</label>
                </div>
            `;
        });

        container.innerHTML += `
            <div class="p-3 rounded-3 border border-slate-200 bg-slate-50 mb-3">
                <label class="fw-bold small text-slate-900 d-block mb-2">${qObj.q}</label>
                ${optsHtml}
            </div>
        `;
    });

    highlightNavBtnDisc('navDisc-' + modKey + '_quiz');
}

function submitModuleQuizDisc(e) {
    e.preventDefault();
    const data = modulesDataDisc[currentModKeyDisc];
    let correctCount = 0;

    data.questions.forEach((qObj, idx) => {
        const selected = document.querySelector(`input[name="mdq_${idx}"]:checked`);
        if (selected && parseInt(selected.value) === qObj.correct) {
            correctCount++;
        }
    });

    const score = Math.round((correctCount / data.questions.length) * 100);
    const resBox = document.getElementById('quizModuleResultDisc');
    resBox.classList.remove('d-none');

    if (score >= 70) {
        if (!passedModulesDisc.includes(currentModKeyDisc)) {
            passedModulesDisc.push(currentModKeyDisc);
            localStorage.setItem('passed_modules_disc_bank', JSON.stringify(passedModulesDisc));
        }
        resBox.className = 'mt-4 p-3 rounded-3 text-center bg-success-subtle text-success border border-success-subtle';
        resBox.innerHTML = `
            <h5 class="fw-bold mb-1">¡Aprobado con éxito! Calificación: ${score} / 100 (${correctCount} de 10 aciertos)</h5>
            <p class="small mb-2">Has superado la evaluación parcial de 10 preguntas del ${data.tag}.</p>
            <button type="button" class="btn btn-sm fw-bold text-white" style="background-color: #7e22ce;" onclick="goToNextModuleDisc('${currentModKeyDisc}')">Continuar al Siguiente Módulo ▷</button>
        `;
        updateProgressStateDisc();
    } else {
        resBox.className = 'mt-4 p-3 rounded-3 text-center bg-warning-subtle text-dark border border-warning-subtle';
        resBox.innerHTML = `
            <h5 class="fw-bold mb-1">Calificación: ${score} / 100 (${correctCount} de 10 aciertos)</h5>
            <p class="small mb-2">Se requiere un mínimo de 70% (7 aciertos) para acreditar el módulo. Repasa el video y la guía PDF antes de reintentar.</p>
            <button type="button" class="btn btn-outline-dark btn-sm fw-bold" onclick="backToLessonDisc()">Repasar Video y Guía PDF</button>
        `;
    }
}

function goToNextModuleDisc(currentKey) {
    const keys = ['m1', 'm2', 'm3', 'm4', 'm5'];
    const idx = keys.indexOf(currentKey);
    if (idx < keys.length - 1) {
        const nextKey = keys[idx + 1];
        loadLessonDisc(nextKey + '_video');
    } else {
        openFinalEvaluationDisc();
    }
}

function backToLessonDisc() {
    loadLessonDisc(currentModKeyDisc + '_video');
}

function openFinalEvaluationDisc() {
    const allPassed = ['m1', 'm2', 'm3', 'm4', 'm5'].every(k => passedModulesDisc.includes(k));
    if (!allPassed) {
        document.getElementById('cardLessonViewDisc').classList.add('d-none');
        document.getElementById('cardModuleQuizViewDisc').classList.add('d-none');
        document.getElementById('cardFinalQuizViewDisc').classList.add('d-none');
        document.getElementById('cardCertificateViewDisc').classList.add('d-none');
        document.getElementById('cardLockedViewDisc').classList.remove('d-none');
        return;
    }

    document.getElementById('cardLessonViewDisc').classList.add('d-none');
    document.getElementById('cardModuleQuizViewDisc').classList.add('d-none');
    document.getElementById('cardLockedViewDisc').classList.add('d-none');
    document.getElementById('cardCertificateViewDisc').classList.add('d-none');
    document.getElementById('cardFinalQuizViewDisc').classList.remove('d-none');

    // Generar las 15 preguntas dinámicas
    const fContainer = document.getElementById('finalQuizQuestionsContainerDisc');
    fContainer.innerHTML = '';
    finalQuestionsDisc.forEach((qObj, idx) => {
        let opts = '';
        qObj.options.forEach((opt, oIdx) => {
            opts += `
                <div class="form-check mb-1">
                    <input class="form-check-input" type="radio" name="fdq_${idx}" value="${oIdx}" id="fdq_${idx}_${oIdx}" required>
                    <label class="form-check-label small text-slate-700" for="fdq_${idx}_${oIdx}">${opt}</label>
                </div>
            `;
        });
        fContainer.innerHTML += `
            <div class="p-3 bg-slate-50 border border-slate-200 rounded-3 mb-2">
                <label class="fw-bold small text-slate-900 d-block mb-2">${qObj.q}</label>
                ${opts}
            </div>
        `;
    });
}

function submitFinalQuizDisc(e) {
    e.preventDefault();
    let correct = 0;
    finalQuestionsDisc.forEach((qObj, idx) => {
        const sel = document.querySelector(`input[name="fdq_${idx}"]:checked`);
        if (sel && parseInt(sel.value) === qObj.correct) {
            correct++;
        }
    });

    const score = Math.round((correct / finalQuestionsDisc.length) * 100);
    if (score >= 70) {
        alert(`¡Felicidades! Calificación en examen final: ${score}/100 (${correct} de 15 aciertos). Tu certificado oficial ha sido emitido.`);
        openCertificateDisc();
    } else {
        alert(`Calificación final: ${score}/100 (${correct} de 15 aciertos). Necesitas al menos 11 aciertos (70%) para acreditar.`);
    }
}

function openCertificateDisc() {
    document.getElementById('cardLessonViewDisc').classList.add('d-none');
    document.getElementById('cardModuleQuizViewDisc').classList.add('d-none');
    document.getElementById('cardLockedViewDisc').classList.add('d-none');
    document.getElementById('cardFinalQuizViewDisc').classList.add('d-none');
    document.getElementById('cardCertificateViewDisc').classList.remove('d-none');
}

function updateProgressStateDisc() {
    const total = 5;
    const done = passedModulesDisc.length;
    const pct = Math.round((done / total) * 100);

    const pBar = document.getElementById('progressBarDisc');
    if (pBar) pBar.style.width = pct + '%';
    const bText = document.getElementById('progressPercentBadgeDisc');
    if (bText) bText.innerText = pct + '%';
    const cCount = document.getElementById('completedItemsCountDisc');
    if (cCount) cCount.innerText = `${done} de ${total} módulos aprobados`;

    ['m1', 'm2', 'm3', 'm4', 'm5'].forEach(k => {
        const isPassed = passedModulesDisc.includes(k);
        const b = document.getElementById('badgeDisc-' + k);
        const qb = document.getElementById('quizStatusDisc-' + k);
        const lk = document.getElementById('lockDisc-stat-' + k);

        if (isPassed) {
            if (b) { b.className = 'text-xs text-success fw-bold'; b.innerText = '✓ Aprobado'; }
            if (qb) { qb.className = 'badge bg-success-subtle text-success'; qb.innerText = 'Aprobado'; }
            if (lk) { lk.className = 'fw-semibold text-success'; lk.innerText = 'Aprobado ✓'; }
        } else {
            if (b) { b.className = 'text-xs text-muted'; b.innerText = 'Pendiente'; }
            if (qb) { qb.className = 'badge bg-white border border-slate-300 text-slate-600'; qb.innerText = '10 preguntas'; }
            if (lk) { lk.className = 'fw-semibold text-danger'; lk.innerText = 'Pendiente'; }
        }
    });

    const isAll = done === 5;
    const notice = document.getElementById('lockStatusNoticeDisc');
    const fLockIcon = document.getElementById('finalLockIconDisc');
    const fBadge = document.getElementById('finalQuizBadgeDisc');
    const certBtn = document.getElementById('btnNavCertDisc');
    const certBadge = document.getElementById('finalCertBadgeDisc');

    if (isAll) {
        if (notice) notice.innerText = '🔓 Examen final desbloqueado';
        if (fLockIcon) fLockIcon.innerText = '📝';
        if (fBadge) { fBadge.className = 'badge text-white'; fBadge.style.backgroundColor = '#7e22ce'; fBadge.innerText = 'Desbloqueada'; }
        if (certBtn) certBtn.classList.remove('disabled');
        if (certBadge) { certBadge.innerText = 'Disponible'; }
    } else {
        if (notice) notice.innerText = '🔒 Examen final bloqueado';
        if (fLockIcon) fLockIcon.innerText = '🔒';
        if (fBadge) { fBadge.className = 'badge bg-slate-200 text-slate-700'; fBadge.innerText = 'Bloqueada'; }
    }
}

function navigateStepDisc(delta) {
    if (delta > 0) {
        showModuleQuizDisc(currentModKeyDisc);
    } else {
        const keys = ['m1', 'm2', 'm3', 'm4', 'm5'];
        const idx = keys.indexOf(currentModKeyDisc);
        if (idx > 0) {
            loadLessonDisc(keys[idx - 1] + '_video');
        }
    }
}

const pdfFileMapDisc = {
    'm1': '<?= asset("pdf/discretas-modulo-1-logica.pdf") ?>',
    'm2': '<?= asset("pdf/discretas-modulo-2-conjuntos.pdf") ?>',
    'm3': '<?= asset("pdf/discretas-modulo-3-relaciones.pdf") ?>',
    'm4': '<?= asset("pdf/discretas-modulo-4-grafos.pdf") ?>',
    'm5': '<?= asset("pdf/discretas-modulo-5-algebra-booleana.pdf") ?>'
};

function downloadPdfDocDisc() {
    const fileUrl = pdfFileMapDisc[currentModKeyDisc] || '<?= asset("pdf/discretas-modulo-1-logica.pdf") ?>';
    const link = document.createElement('a');
    link.href = fileUrl;
    link.download = fileUrl.split('/').pop();
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

document.addEventListener('DOMContentLoaded', initDisc);
</script>

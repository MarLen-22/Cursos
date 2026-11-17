<?php
/**
 * Curso Interactivo MOOC: Fundamentos de Programación (Diseño Claro / Blanco)
 * Estilo Santander Open Academy / Coursera
 * Con Videos, PDFs incrustados/descargables, 10 preguntas por cada módulo y Evaluación Final de 15 preguntas con Candado
 */
$moduloTitulo = $modulo->titulo ?? 'Fundamentos de Programación';
?>
<div class="mooc-white-theme bg-white min-vh-100 text-slate-900 pb-5" id="moocAppProg">
    
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
                    <span class="badge bg-blue-50 text-primary border border-blue-200 px-3 py-2 rounded-pill fw-semibold">
                        Nivel Inicial · MOOC
                    </span>
                    <span class="badge bg-slate-100 text-slate-700 border border-slate-200 px-3 py-2 rounded-pill">
                        5 Módulos · 10 preguntas c/u + Examen Final 15 preguntas
                    </span>
                </div>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-12 col-lg-8">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-3 bg-blue-50 text-primary fs-3 px-3 py-2 border border-blue-200 fw-bold">
                            &lt;/&gt;
                        </div>
                        <div>
                            <span class="text-uppercase tracking-wider small fw-bold text-primary">Curso Oficial</span>
                            <h1 class="h2 fw-bold text-slate-900 mb-0"><?= e($moduloTitulo) ?></h1>
                        </div>
                    </div>
                    <p class="text-slate-600 mb-3 small max-w-2xl">
                        Aprende a formular algoritmos estructurados, variables, condicionales, ciclos y funciones mediante videos explicativos, guías descargables en PDF y exámenes rigurosos de 10 preguntas por módulo.
                    </p>
                    
                    <!-- Navegación rápida por módulos -->
                    <div class="d-flex flex-wrap align-items-center gap-2 pt-1">
                        <span class="small text-slate-500 me-2 fw-semibold">Módulos:</span>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-circle px-2 py-1 mod-nav-pill active" onclick="loadLesson('m1_video')">1</button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-circle px-2 py-1 mod-nav-pill" onclick="loadLesson('m2_video')">2</button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-circle px-2 py-1 mod-nav-pill" onclick="loadLesson('m3_video')">3</button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-circle px-2 py-1 mod-nav-pill" onclick="loadLesson('m4_video')">4</button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-circle px-2 py-1 mod-nav-pill" onclick="loadLesson('m5_video')">5</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle px-2 py-1 mod-nav-pill" onclick="openFinalEvaluation()">🔒 Final (15 preguntas)</button>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="bg-slate-50 border border-slate-200 rounded-4 p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small text-slate-700 fw-bold">Progreso del Curso</span>
                            <span class="badge bg-primary text-white fw-bold" id="progressPercentBadge">0%</span>
                        </div>
                        <div class="progress bg-slate-200 rounded-pill mb-2" style="height: 10px;">
                            <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" id="progressBar" style="width: 0%"></div>
                        </div>
                        <div class="d-flex justify-content-between text-xs text-slate-500">
                            <span id="completedItemsCount">0 de 5 módulos aprobados</span>
                            <span id="lockStatusNotice">🔒 Examen final bloqueado</span>
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

                    <div class="accordion accordion-flush" id="courseAccordion" style="max-height: 75vh; overflow-y: auto;">
                        
                        <!-- MÓDULO 1 -->
                        <div class="accordion-item border-bottom border-slate-100">
                            <h3 class="accordion-header" id="hM1">
                                <button class="accordion-button py-3 px-4 fw-bold text-slate-800 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#cM1">
                                    <div class="w-100 pe-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge bg-blue-100 text-blue-800 text-xs font-semibold">Módulo 1</span>
                                            <span class="text-xs text-muted" id="badge-m1">Pendiente</span>
                                        </div>
                                        <div class="small fw-bold text-truncate">Algoritmos y Lógica de Solución</div>
                                    </div>
                                </button>
                            </h3>
                            <div id="cM1" class="accordion-collapse collapse show">
                                <div class="accordion-body p-0">
                                    <div class="list-group list-group-flush small">
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn active py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLesson('m1_video')" id="nav-m1_video">
                                            <span class="text-danger">📹</span>
                                            <span>1.1 Video Clase: ¿Qué es un Algoritmo?</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLesson('m1_pdf')" id="nav-m1_pdf">
                                            <span class="text-primary">📄</span>
                                            <span>1.2 Guía de Estudio y Diagramas (PDF)</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center justify-content-between bg-slate-50" onclick="loadLesson('m1_quiz')" id="nav-m1_quiz">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-warning">📝</span>
                                                <span class="fw-bold">1.3 Examen del Módulo 1</span>
                                            </div>
                                            <span class="badge bg-white border border-slate-300 text-slate-600" id="quiz-status-m1">10 preguntas</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MÓDULO 2 -->
                        <div class="accordion-item border-bottom border-slate-100">
                            <h3 class="accordion-header" id="hM2">
                                <button class="accordion-button collapsed py-3 px-4 fw-bold text-slate-800 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#cM2">
                                    <div class="w-100 pe-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge bg-blue-100 text-blue-800 text-xs font-semibold">Módulo 2</span>
                                            <span class="text-xs text-muted" id="badge-m2">Pendiente</span>
                                        </div>
                                        <div class="small fw-bold text-truncate">Variables, Tipos de Datos y Operadores</div>
                                    </div>
                                </button>
                            </h3>
                            <div id="cM2" class="accordion-collapse collapse">
                                <div class="accordion-body p-0">
                                    <div class="list-group list-group-flush small">
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLesson('m2_video')" id="nav-m2_video">
                                            <span class="text-danger">📹</span>
                                            <span>2.1 Video Clase: Tipos de Datos Primitivos</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLesson('m2_pdf')" id="nav-m2_pdf">
                                            <span class="text-primary">📄</span>
                                            <span>2.2 Guía de Operadores y Jerarquía (PDF)</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center justify-content-between bg-slate-50" onclick="loadLesson('m2_quiz')" id="nav-m2_quiz">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-warning">📝</span>
                                                <span class="fw-bold">2.3 Examen del Módulo 2</span>
                                            </div>
                                            <span class="badge bg-white border border-slate-300 text-slate-600" id="quiz-status-m2">10 preguntas</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MÓDULO 3 -->
                        <div class="accordion-item border-bottom border-slate-100">
                            <h3 class="accordion-header" id="hM3">
                                <button class="accordion-button collapsed py-3 px-4 fw-bold text-slate-800 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#cM3">
                                    <div class="w-100 pe-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge bg-blue-100 text-blue-800 text-xs font-semibold">Módulo 3</span>
                                            <span class="text-xs text-muted" id="badge-m3">Pendiente</span>
                                        </div>
                                        <div class="small fw-bold text-truncate">Estructuras Condicionales (if / else)</div>
                                    </div>
                                </button>
                            </h3>
                            <div id="cM3" class="accordion-collapse collapse">
                                <div class="accordion-body p-0">
                                    <div class="list-group list-group-flush small">
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLesson('m3_video')" id="nav-m3_video">
                                            <span class="text-danger">📹</span>
                                            <span>3.1 Video Clase: Toma de Decisiones</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLesson('m3_pdf')" id="nav-m3_pdf">
                                            <span class="text-primary">📄</span>
                                            <span>3.2 Guía de Casos Condicionales (PDF)</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center justify-content-between bg-slate-50" onclick="loadLesson('m3_quiz')" id="nav-m3_quiz">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-warning">📝</span>
                                                <span class="fw-bold">3.3 Examen del Módulo 3</span>
                                            </div>
                                            <span class="badge bg-white border border-slate-300 text-slate-600" id="quiz-status-m3">10 preguntas</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MÓDULO 4 -->
                        <div class="accordion-item border-bottom border-slate-100">
                            <h3 class="accordion-header" id="hM4">
                                <button class="accordion-button collapsed py-3 px-4 fw-bold text-slate-800 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#cM4">
                                    <div class="w-100 pe-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge bg-blue-100 text-blue-800 text-xs font-semibold">Módulo 4</span>
                                            <span class="text-xs text-muted" id="badge-m4">Pendiente</span>
                                        </div>
                                        <div class="small fw-bold text-truncate">Estructuras Cíclicas (while / for)</div>
                                    </div>
                                </button>
                            </h3>
                            <div id="cM4" class="accordion-collapse collapse">
                                <div class="accordion-body p-0">
                                    <div class="list-group list-group-flush small">
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLesson('m4_video')" id="nav-m4_video">
                                            <span class="text-danger">📹</span>
                                            <span>4.1 Video Clase: Bucles while y for</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLesson('m4_pdf')" id="nav-m4_pdf">
                                            <span class="text-primary">📄</span>
                                            <span>4.2 Guía de Patrones Iterativos (PDF)</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center justify-content-between bg-slate-50" onclick="loadLesson('m4_quiz')" id="nav-m4_quiz">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-warning">📝</span>
                                                <span class="fw-bold">4.3 Examen del Módulo 4</span>
                                            </div>
                                            <span class="badge bg-white border border-slate-300 text-slate-600" id="quiz-status-m4">10 preguntas</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MÓDULO 5 -->
                        <div class="accordion-item border-bottom border-slate-100">
                            <h3 class="accordion-header" id="hM5">
                                <button class="accordion-button collapsed py-3 px-4 fw-bold text-slate-800 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#cM5">
                                    <div class="w-100 pe-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge bg-blue-100 text-blue-800 text-xs font-semibold">Módulo 5</span>
                                            <span class="text-xs text-muted" id="badge-m5">Pendiente</span>
                                        </div>
                                        <div class="small fw-bold text-truncate">Funciones y Arreglos (Listas)</div>
                                    </div>
                                </button>
                            </h3>
                            <div id="cM5" class="accordion-collapse collapse">
                                <div class="accordion-body p-0">
                                    <div class="list-group list-group-flush small">
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLesson('m5_video')" id="nav-m5_video">
                                            <span class="text-danger">📹</span>
                                            <span>5.1 Video Clase: Funciones y Parámetros</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center gap-2" onclick="loadLesson('m5_pdf')" id="nav-m5_pdf">
                                            <span class="text-primary">📄</span>
                                            <span>5.2 Guía de Arreglos y Listas (PDF)</span>
                                        </button>
                                        <button type="button" class="list-group-item list-group-item-action lesson-btn py-2.5 px-4 d-flex align-items-center justify-content-between bg-slate-50" onclick="loadLesson('m5_quiz')" id="nav-m5_quiz">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-warning">📝</span>
                                                <span class="fw-bold">5.3 Examen del Módulo 5</span>
                                            </div>
                                            <span class="badge bg-white border border-slate-300 text-slate-600" id="quiz-status-m5">10 preguntas</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- EVALUACIÓN FINAL DEL CURSO (15 PREGUNTAS) -->
                        <div class="p-3 bg-slate-50 border-bottom border-slate-200">
                            <button type="button" class="btn btn-outline-primary btn-sm w-100 fw-bold d-flex justify-content-between align-items-center py-2.5" onclick="openFinalEvaluation()" id="btnNavFinalQuiz">
                                <span class="d-flex align-items-center gap-2">
                                    <span id="finalLockIcon">🔒</span>
                                    <span>Evaluación Final (15 preguntas)</span>
                                </span>
                                <span class="badge bg-slate-200 text-slate-700" id="finalQuizBadge">Bloqueada</span>
                            </button>
                        </div>

                        <!-- CERTIFICADO FINAL -->
                        <div class="p-3 bg-white">
                            <button type="button" class="btn btn-success btn-sm w-100 fw-bold d-flex justify-content-between align-items-center py-2.5 disabled" onclick="openCertificate()" id="btnNavCert">
                                <span class="d-flex align-items-center gap-2">
                                    <span>🏅</span>
                                    <span>Descargar Certificado Oficial</span>
                                </span>
                                <span class="badge bg-white text-success border border-success-subtle" id="finalCertBadge">Bloqueado</span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA: PANTALLA PRINCIPAL DE CONTENIDOS (8 cols) -->
            <div class="col-12 col-lg-8">
                
                <!-- 1. VISTA DE LECCIÓN ORDINARIA (VIDEO / PDF) -->
                <div class="card border border-slate-200 rounded-4 shadow-sm bg-white overflow-hidden" id="cardLessonView">
                    <div class="card-header bg-slate-50 border-bottom border-slate-200 p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                            <span class="badge bg-blue-100 text-blue-800 fw-bold text-uppercase" id="lessonTag">Módulo 1</span>
                            <span class="text-slate-500 small" id="lessonDuration">⏱ Duración: 15 min</span>
                        </div>
                        <h2 class="h4 fw-bold text-slate-900 mb-1" id="lessonTitle">1.1 ¿Qué es un Algoritmo? Pensamiento Computacional</h2>
                        <p class="text-slate-600 small mb-0" id="lessonSummary">Conceptos fundamentales de entrada, proceso y salida para resolver problemas.</p>
                    </div>

                    <div class="card-body p-4">
                        <ul class="nav nav-tabs mb-4 border-bottom border-slate-200" id="lessonTabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active fw-bold small text-slate-700" id="tab-video-link" onclick="switchResourceTab('video')">
                                    📹 Video de la Lección
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link fw-bold small text-slate-700" id="tab-pdf-link" onclick="switchResourceTab('pdf')">
                                    📄 Guía de Estudio y PDF Incrustado
                                </button>
                            </li>
                        </ul>

                        <!-- CONTENIDO PESTAÑA VIDEO -->
                        <div id="contentVideoBox">
                            <div class="ratio ratio-16x9 rounded-3 overflow-hidden bg-black mb-3 shadow-sm border border-slate-200">
                                <iframe id="videoPlayer" src="https://www.youtube-nocookie.com/embed/chPhlsydl30?rel=0" allowfullscreen></iframe>
                            </div>
                            <div class="alert alert-light border border-slate-200 p-3 small text-slate-700 rounded-3">
                                <h6 class="fw-bold text-slate-900 mb-1">💡 Temas clave explicados en el video:</h6>
                                <p class="mb-0 text-slate-600" id="videoNotes">Definición de algoritmos y características fundamentales para el desarrollo de soluciones computacionales.</p>
                            </div>
                        </div>

                        <!-- CONTENIDO PESTAÑA VISOR DE PDF INCORPORADO -->
                        <div id="contentPdfBox" class="d-none">
                            <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-slate-50 border border-slate-200 rounded-3">
                                <div>
                                    <h6 class="fw-bold mb-0 text-slate-900" id="pdfHeadingTitle">Guía Técnica de Fundamentos de Algoritmos</h6>
                                    <span class="text-xs text-slate-500">Documento PDF oficial del curso</span>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm fw-bold shadow-sm" onclick="downloadPdfDoc()">
                                    📥 Descargar PDF Oficial
                                </button>
                            </div>
                            
                            <div class="p-4 bg-white border border-slate-200 rounded-3 shadow-xs" style="max-height: 480px; overflow-y: auto;">
                                <div class="border-bottom border-slate-200 pb-3 mb-3 text-center">
                                    <div class="text-xs text-uppercase fw-bold text-primary tracking-wider">TECNOLÓGICO NACIONAL DE MÉXICO · CIENCIAS BÁSICAS</div>
                                    <h3 class="h5 fw-bold text-slate-900 mt-1" id="pdfDocumentHeading">Manual Didáctico: Algorítmica y Diagramación ANSI</h3>
                                    <p class="text-muted small mb-0">Material de lectura y preparación para el examen del módulo</p>
                                </div>
                                <div id="pdfBodyHtml" class="small text-slate-700 leading-relaxed space-y-3">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-4 border-top border-slate-200 mt-4">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="navigateStep(-1)">
                                ← Anterior
                            </button>
                            <button type="button" class="btn btn-primary btn-sm fw-bold px-4" onclick="navigateStep(1)">
                                Continuar al Examen del Módulo (10 preguntas) →
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 2. VISTA DE EXAMEN DE CADA MÓDULO (10 PREGUNTAS) -->
                <div class="card border border-slate-200 rounded-4 shadow-sm bg-white overflow-hidden d-none" id="cardModuleQuizView">
                    <div class="card-header bg-slate-50 border-bottom border-slate-200 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark fw-bold text-uppercase" id="quizModuleBadge">Módulo 1</span>
                            <span class="badge bg-white border border-slate-300 text-slate-700 text-xs">10 Preguntas · Aprobatorio: 70%</span>
                        </div>
                        <h2 class="h4 fw-bold text-slate-900 mb-1" id="quizModuleTitle">Examen de Conocimientos: Algoritmos</h2>
                        <p class="text-slate-600 small mb-0">Responde las 10 preguntas extraídas directamente de los conceptos de la video clase y la guía PDF.</p>
                    </div>
                    <div class="card-body p-4">
                        <form id="formModuleQuiz" onsubmit="submitModuleQuiz(event)">
                            <div id="quizQuestionsContainer" class="space-y-4" style="max-height: 600px; overflow-y: auto; padding-right: 8px;">
                                <!-- 10 preguntas dinámicas por módulo -->
                            </div>

                            <div class="mt-4 pt-3 border-top border-slate-200 d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="backToLesson()">← Volver a la guía/video</button>
                                <button type="submit" class="btn btn-primary fw-bold px-4">Calificar Examen (10 preguntas) ▷</button>
                            </div>
                        </form>

                        <div id="quizModuleResult" class="mt-4 p-3 rounded-3 d-none text-center"></div>
                    </div>
                </div>

                <!-- 3. AVISO DE EVALUACIÓN FINAL BLOQUEADA -->
                <div class="card border border-slate-200 rounded-4 shadow-sm bg-white p-5 text-center d-none" id="cardLockedView">
                    <div class="py-4">
                        <div class="fs-1 mb-3">🔒</div>
                        <h3 class="h4 fw-bold text-slate-900 mb-2">Evaluación Final Bloqueada</h3>
                        <p class="text-slate-600 small max-w-lg mx-auto mb-4 leading-relaxed">
                            Para desbloquear la evaluación final y obtener tu certificado, primero debes <strong>aprobar los exámenes de los 5 módulos (10 preguntas c/u)</strong>.
                        </p>
                        <div class="d-inline-block bg-slate-50 border border-slate-200 rounded-3 p-3 text-start small mb-4">
                            <div class="fw-bold text-slate-800 mb-2">Estado de tus módulos:</div>
                            <ul class="list-unstyled mb-0 space-y-1 text-slate-600" id="lockedModulesList">
                                <li>Módulo 1: <span id="lock-stat-m1" class="fw-semibold text-danger">Pendiente</span></li>
                                <li>Módulo 2: <span id="lock-stat-m2" class="fw-semibold text-danger">Pendiente</span></li>
                                <li>Módulo 3: <span id="lock-stat-m3" class="fw-semibold text-danger">Pendiente</span></li>
                                <li>Módulo 4: <span id="lock-stat-m4" class="fw-semibold text-danger">Pendiente</span></li>
                                <li>Módulo 5: <span id="lock-stat-m5" class="fw-semibold text-danger">Pendiente</span></li>
                            </ul>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary btn-sm fw-bold px-4" onclick="loadLesson('m1_video')">
                                Continuar con Módulo 1 ▷
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 4. VISTA DE EVALUACIÓN FINAL DEL CURSO (15 PREGUNTAS) -->
                <div class="card border border-slate-200 rounded-4 shadow-sm bg-white overflow-hidden d-none" id="cardFinalQuizView">
                    <div class="card-header bg-primary text-white p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-white text-primary fw-bold text-uppercase">Evaluación Integral</span>
                            <span class="badge bg-blue-700 text-white text-xs">15 Preguntas · Aprobatorio: 70%</span>
                        </div>
                        <h2 class="h4 fw-bold text-white mb-1">Examen Final del Curso</h2>
                        <p class="text-blue-100 small mb-0">Has aprobado los 5 módulos. Responde esta prueba completa de 15 reactivos para expedir tu certificado oficial.</p>
                    </div>
                    <div class="card-body p-4">
                        <form id="formFinalQuiz" onsubmit="submitFinalQuiz(event)">
                            <div id="finalQuizQuestionsContainer" class="space-y-4" style="max-height: 600px; overflow-y: auto; padding-right: 8px;">
                                <!-- Inyección de las 15 preguntas integrales -->
                            </div>

                            <!-- Encuesta de satisfacción -->
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-3 my-4">
                                <h6 class="fw-bold text-slate-900 mb-1">Encuesta de Satisfacción del Curso:</h6>
                                <p class="text-xs text-slate-500 mb-2">¿Los videos y las guías PDF te ayudaron a comprender los temas?</p>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="final_sat" checked id="sat1">
                                        <label class="form-check-label small" for="sat1">Excelente ⭐⭐⭐⭐⭐</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="final_sat" id="sat2">
                                        <label class="form-check-label small" for="sat2">Muy bueno ⭐⭐⭐⭐</label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success fw-bold w-100 py-2.5">
                                Calificar Examen Final (15 preguntas) y Expedir Certificado ▷
                            </button>
                        </form>
                    </div>
                </div>

                <!-- 5. VISTA DEL CERTIFICADO FINAL -->
                <div class="card border border-slate-200 rounded-4 shadow-sm bg-white overflow-hidden d-none" id="cardCertificateView">
                    <div class="card-header bg-slate-50 border-bottom border-slate-200 p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold text-uppercase mb-1">Acreditación Obtenida</span>
                            <h2 class="h4 fw-bold text-slate-900 mb-0">Certificado Oficial</h2>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm fw-bold shadow-sm" onclick="window.print()">
                            📥 Imprimir / Guardar en PDF
                        </button>
                    </div>
                    
                    <div class="card-body p-4 bg-slate-100">
                        <div class="certificate-paper bg-white p-5 mx-auto border shadow-sm rounded-3 position-relative" id="certDiploma" style="max-width: 820px; border: 6px double #0284c7 !important; min-height: 500px;">
                            <div class="text-center mb-4">
                                <div class="text-xs text-uppercase tracking-widest text-muted fw-bold mb-1">TECNOLÓGICO NACIONAL DE MÉXICO · IT CIUDAD VICTORIA</div>
                                <h3 class="fw-bold text-slate-900" style="font-family: Georgia, serif; font-size: 1.8rem;">CERTIFICADO DE APROBACIÓN</h3>
                                <p class="text-muted text-xs">Se otorga la presente constancia con valor curricular a:</p>
                            </div>

                            <div class="text-center my-4">
                                <h2 class="fw-bold text-primary display-6 border-bottom border-slate-300 d-inline-block pb-2 px-4" id="certStudentName">
                                    Alejandro (Estudiante)
                                </h2>
                                <p class="text-slate-600 small mt-2">
                                    Por haber completado y aprobado satisfactoriamente los 5 módulos del programa:
                                </p>
                                <h4 class="fw-bold text-slate-900 text-uppercase tracking-wide">
                                    Fundamentos de Programación y Pensamiento Computacional
                                </h4>
                                <p class="small text-muted mb-0">Acreditando evaluaciones parciales de 10 preguntas por módulo y el examen final integral de 15 reactivos.</p>
                            </div>

                            <div class="row align-items-end mt-5 pt-4 text-center">
                                <div class="col-4">
                                    <div class="border-top border-slate-400 pt-1 small fw-bold">Dra. Marlene / Coordinación</div>
                                    <div class="text-xs text-muted">Comité Académico Tec</div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 border rounded bg-slate-50 d-inline-block">
                                        <div class="fw-bold text-slate-800 text-xs">SELLO DIGITAL</div>
                                        <div class="font-monospace text-xs text-muted">FOLIO: LV-PROG-2026-9921</div>
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
.mooc-white-theme {
    font-family: 'Inter', system-ui, sans-serif;
    background-color: #ffffff;
}
.lesson-btn {
    border: none;
    border-left: 3px solid transparent;
    transition: all 0.15s ease;
    background: transparent;
    color: #334155;
}
.lesson-btn:hover {
    background-color: #f8fafc;
    color: #0284c7;
}
.lesson-btn.active {
    background-color: #f0f9ff !important;
    border-left-color: #0284c7 !important;
    color: #0369a1 !important;
    font-weight: 600;
}
.mod-nav-pill.active {
    background-color: #0284c7 !important;
    color: #ffffff !important;
    font-weight: bold;
}
@media print {
    body * { visibility: hidden; }
    #certDiploma, #certDiploma * { visibility: visible; }
    #certDiploma {
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
// BANCO COMPLETO DE PREGUNTAS (10 POR MÓDULO + 15 PARA EL EXAMEN FINAL)
const modulesDataProg = {
    'm1': {
        title: 'Módulo 1: Algoritmos y Lógica de Solución',
        tag: 'Módulo 1',
        dur: '15 min',
        videoUrl: 'https://www.youtube-nocookie.com/embed/chPhlsydl30?rel=0',
        videoNotes: 'En este video se detalla cómo desglosar un problema cotidiano en entradas, procesos estructurados y resultados reproducibles.',
        pdfHeading: 'Manual de Algorítmica y Diagramas de Flujo ANSI',
        pdfText: `
            <h6>1. Definición Formal de un Algoritmo</h6>
            <p>Un algoritmo es una secuencia lógica, ordenada y finita de instrucciones que transforman datos de entrada en una solución correcta.</p>
            <h6>2. Simbología Estándar en Diagramas de Flujo:</h6>
            <ul>
                <li><strong>Óvalo:</strong> Inicio o terminación del proceso.</li>
                <li><strong>Rectángulo:</strong> Proceso u operación matemática interna (ej. resultado = a + b).</li>
                <li><strong>Paralelogramo:</strong> Entrada o lectura de datos del usuario, y salida de información.</li>
                <li><strong>Rombo:</strong> Toma de decisiones condicionales (ej. ¿es x mayor a cero?).</li>
            </ul>
        `,
        questions: [
            { q: "1. ¿Cuál de las siguientes es una propiedad indispensable de todo algoritmo?", options: ["Debe ser infinito.", "Debe ser finito, preciso y determinista.", "Solo puede ejecutarse en binario."], correct: 1 },
            { q: "2. En diagramas de flujo ANSI, ¿qué figura geométrica representa una decisión condicional?", options: ["El rectángulo.", "El rombo.", "El óvalo."], correct: 1 },
            { q: "3. Si ante las mismas entradas el algoritmo siempre genera la misma salida, cumple con:", options: ["Determinismo.", "Finitud.", "Ambigüedad."], correct: 0 },
            { q: "4. ¿Qué bloque gráfico se utiliza para representar la lectura de un dato por teclado?", options: ["Rectángulo.", "Paralelogramo.", "Círculo."], correct: 1 },
            { q: "5. ¿Qué significa que un algoritmo sea 'preciso'?", options: ["Que cada instrucción es unívoca y no ambigua.", "Que termina rápido.", "Que ocupa poca memoria."], correct: 0 },
            { q: "6. El término 'finitud' implica que:", options: ["El algoritmo debe detenerse tras un número contable de pasos.", "Solo procesa números pequeños.", "No tiene condición de salida."], correct: 0 },
            { q: "7. ¿Para qué se utiliza el pseudocódigo en el diseño algorítmico?", options: ["Para compilar directamente en el procesador.", "Para expresar la lógica en lenguaje estructurado independiente del lenguaje final.", "Para diseñar interfaces gráficas."], correct: 1 },
            { q: "8. ¿Qué representa la figura de un rectángulo en un diagrama de flujo?", options: ["Una instrucción de cálculo o asignación de proceso.", "El fin del algoritmo.", "Una decisión de si o no."], correct: 0 },
            { q: "9. ¿Cuál es el propósito principal de una prueba de escritorio (traza)?", options: ["Verificar paso a paso los valores en memoria para detectar fallas lógicas.", "Comprobar la velocidad de internet.", "Pintar los diagramas."], correct: 0 },
            { q: "10. La primera etapa metodológica al formular un algoritmo según el enfoque ABP es:", options: ["Escribir código de inmediato.", "Analizar el problema, identificar datos de entrada y resultados esperados.", "Descargar un compilador."], correct: 1 }
        ]
    },
    'm2': {
        title: 'Módulo 2: Variables, Tipos de Datos y Operadores',
        tag: 'Módulo 2',
        dur: '20 min',
        videoUrl: 'https://www.youtube-nocookie.com/embed/G2FCfQj-9ig?rel=0',
        videoNotes: 'Aprende cómo las computadoras almacenan enteros (int), decimales (float), cadenas de texto (string) y booleanos en memoria RAM.',
        pdfHeading: 'Guía de Tipos Primitivos y Tabla de Precedencia',
        pdfText: `
            <h6>1. Tipos Primitivos en Computación</h6>
            <p><strong>int:</strong> Números enteros. <strong>float:</strong> Números reales con coma decimal. <strong>string:</strong> Cadenas de caracteres. <strong>bool:</strong> Valores lógicos (true/false).</p>
            <h6>2. Jerarquía de Operadores:</h6>
            <p>1. Paréntesis () | 2. Potencias ** | 3. Multiplicación/División (*, /, %) | 4. Suma/Resta (+, -) | 5. Operadores relacionales (==, !=, >, <) | 6. Operadores lógicos (NOT, AND, OR).</p>
        `,
        questions: [
            { q: "1. ¿Qué tipo de dato almacena valores de verdad como verdadero o falso?", options: ["String.", "Float.", "Boolean."], correct: 2 },
            { q: "2. Al evaluar la expresión matemática (4 + 2) * 3, ¿cuál es el resultado según la jerarquía?", options: ["10.", "18.", "12."], correct: 1 },
            { q: "3. ¿Qué operador se utiliza para comparar si dos variables son iguales?", options: ["= (un igual).", "== (doble igual).", "=>."], correct: 1 },
            { q: "4. ¿Qué devuelve el operador módulo (%) en una operación como 10 % 3?", options: ["El cociente (3).", "El residuo entero de la división (1).", "El porcentaje (30%)."], correct: 1 },
            { q: "5. ¿Cuál es el tipo de dato del valor 'Hola Mundo'?", options: ["Char.", "String (cadena de texto).", "Int."], correct: 1 },
            { q: "6. Si una variable tiene el valor 3.1416, ¿qué tipo primitivo le corresponde?", options: ["Int.", "Float / Double (punto flotante).", "Boolean."], correct: 1 },
            { q: "7. ¿Qué operador evalúa si dos condiciones se cumplen simultáneamente?", options: ["OR.", "AND (conjunción lógica).", "NOT."], correct: 1 },
            { q: "8. ¿Cuál es el resultado de la expresión: 5 + 3 * 2?", options: ["16.", "11 (la multiplicación tiene mayor precedencia).", "13."], correct: 1 },
            { q: "9. ¿Qué operador relacional verifica si un número es estrictamente mayor que otro?", options: [">.", ">=.", "<."], correct: 0 },
            { q: "10. ¿Cuál es la diferencia entre declarar una constante y una variable?", options: ["El valor de la constante no cambia durante la ejecución del programa.", "Las constantes ocupan más memoria.", "No hay diferencia."], correct: 0 }
        ]
    },
    'm3': {
        title: 'Módulo 3: Estructuras Condicionales (if / else)',
        tag: 'Módulo 3',
        dur: '20 min',
        videoUrl: 'https://www.youtube-nocookie.com/embed/tQWyNZ2mzsM?rel=0',
        videoNotes: 'Muestra cómo controlar el flujo del código bifurcando las instrucciones con sentencias condicionales simples y compuestas.',
        pdfHeading: 'Manual de Estructuras Selectivas y Bifurcación',
        pdfText: `
            <h6>1. Sentencias if / else</h6>
            <p>Evalúan una condición booleana. Si es verdadera se ejecuta el bloque if; si es falsa se pasa al bloque alternativo else.</p>
            <h6>2. Operadores Lógicos Compuestos</h6>
            <p><strong>AND:</strong> Ambas condiciones deben cumplirse. <strong>OR:</strong> Basta con que una se cumpla.</p>
        `,
        questions: [
            { q: "1. Si (edad >= 18) AND (tieneID == true) se evalúa con edad = 20 y tieneID = false, el resultado es:", options: ["Verdadero.", "Falso (ambas deben ser verdaderas).", "Indeterminado."], correct: 1 },
            { q: "2. ¿Qué palabra clave permite evaluar múltiples condiciones alternativas en cascada?", options: ["elif / else if.", "then.", "loop."], correct: 0 },
            { q: "3. Si ninguna de las condiciones de un if o elif se cumple y existe un else final:", options: ["El programa genera un error.", "Se ejecuta obligatoriamente el bloque dentro del else.", "Se repite la condición."], correct: 1 },
            { q: "4. ¿Cuál es el resultado de la expresión lógica: NOT(true)?", options: ["True.", "False.", "Null."], correct: 1 },
            { q: "5. ¿En qué consiste el cortocircuito en una evaluación con operador OR?", options: ["Si la primera condición es verdadera, no evalúa la segunda ya que el resultado es verdadero.", "Se desconecta el servidor.", "Siempre evalúa todas las condiciones."], correct: 0 },
            { q: "6. Para comprobar si una calificación 'n' está entre 70 y 100 inclusive, la condición correcta es:", options: ["n >= 70 AND n <= 100.", "n >= 70 OR n <= 100.", "n == 70 AND 100."], correct: 0 },
            { q: "7. ¿Qué sucede si se omite el bloque 'else' en una estructura condicional?", options: ["El código no compila.", "Si la condición es falsa, simplemente continúa con la siguiente instrucción.", "Ocurre un ciclo infinito."], correct: 1 },
            { q: "8. Las estructuras condicionales anidadas consisten en:", options: ["Colocar una sentencia 'if' dentro del cuerpo de otra sentencia 'if'.", "Usar más de tres variables.", "Crear funciones dentro de bucles."], correct: 0 },
            { q: "9. ¿Qué estructura selectiva es ideal cuando se desea comparar una variable contra múltiples valores constantes discretos?", options: ["Bucle for.", "Switch / Case / Match.", "Recursión."], correct: 1 },
            { q: "10. La sangría o indentación en estructuras condicionales sirve principalmente para:", options: ["Adornar el código.", "Delimitar el bloque de instrucciones que pertenece a esa condición.", "Hacer que corra más rápido."], correct: 1 }
        ]
    },
    'm4': {
        title: 'Módulo 4: Estructuras Cíclicas e Iterativas (while / for)',
        tag: 'Módulo 4',
        dur: '20 min',
        videoUrl: 'https://www.youtube-nocookie.com/embed/Kp4Mvapo5kc?rel=0',
        videoNotes: 'Aprende a ejecutar tareas repetitivas mediante bucles for (determinados) y bucles while (controlados por condición).',
        pdfHeading: 'Patrones de Iteración, Contadores y Acumuladores',
        pdfText: `
            <h6>1. Bucle While</h6>
            <p>Se repite mientras una condición sea verdadera. Es crucial modificar la variable de control dentro del cuerpo del bucle para evitar bucles infinitos.</p>
            <h6>2. Bucle For</h6>
            <p>Ideal cuando se conoce de antemano el número exacto de iteraciones o cuando se recorre una colección finita.</p>
        `,
        questions: [
            { q: "1. ¿Qué sucede si la variable de control de un bucle while nunca se modifica dentro del bucle?", options: ["Ocurre un bucle infinito que bloquea la ejecución.", "Termina automáticamente tras 10 vueltas.", "Se convierte en un for."], correct: 0 },
            { q: "2. Si ejecutamos un bucle for desde i = 1 hasta 5 inclusive, ¿cuántas iteraciones se realizan?", options: ["4 vueltas.", "5 vueltas.", "6 vueltas."], correct: 1 },
            { q: "3. Una variable que suma valores variables en cada iteración (ej. total += venta) es un:", options: ["Contador.", "Acumulador.", "Bandera."], correct: 1 },
            { q: "4. Una variable que incrementa en un valor constante (ej. c = c + 1) en cada vuelta es un:", options: ["Acumulador.", "Contador.", "Arreglo."], correct: 1 },
            { q: "5. ¿Qué instrucción interrumpe y sale de forma inmediata de un bucle?", options: ["continue.", "break.", "return null."], correct: 1 },
            { q: "6. ¿Qué instrucción omite el resto del cuerpo del bucle actual y salta a la siguiente iteración?", options: ["break.", "continue.", "pass."], correct: 1 },
            { q: "7. ¿Cuándo es más recomendable usar un bucle 'for' en lugar de un 'while'?", options: ["Cuando conocemos de antemano el número de iteraciones o el tamaño de la lista.", "Cuando esperamos un evento impredecible del usuario.", "Nunca."], correct: 0 },
            { q: "8. ¿Cuántas veces se ejecuta el cuerpo de un bucle while si su condición inicial es falsa desde el principio?", options: ["1 vez.", "0 veces (nunca ingresa).", "Infinitas veces."], correct: 1 },
            { q: "9. Si un bucle exterior itera 3 veces y un bucle interior anidado itera 4 veces, ¿cuántas veces se ejecuta el código más interno?", options: ["7 veces.", "12 veces (3 * 4).", "4 veces."], correct: 1 },
            { q: "10. La condición de parada en un bucle tiene como objetivo:", options: ["Garantizar la finitud y convergencia del algoritmo.", "Aumentar la memoria consumida.", "Hacer más lento el procesador."], correct: 0 }
        ]
    },
    'm5': {
        title: 'Módulo 5: Funciones y Arreglos (Listas)',
        tag: 'Módulo 5',
        dur: '20 min',
        videoUrl: 'https://www.youtube-nocookie.com/embed/yOXr2sC3WfM?rel=0',
        videoNotes: 'Comprende el principio de modularidad: cómo agrupar instrucciones en funciones con parámetros y cómo manejar colecciones con listas.',
        pdfHeading: 'Modularidad, Parámetros y Colecciones Indexadas',
        pdfText: `
            <h6>1. Principio de Modularidad</h6>
            <p>Permite dividir un problema grande en sub-problemas independientes llamados funciones, reduciendo la redundancia de código.</p>
            <h6>2. Arreglos y Listas</h6>
            <p>Colecciones secuenciales donde los elementos se acceden por su índice posicional comenzando en 0.</p>
        `,
        questions: [
            { q: "1. ¿Qué instrucción se utiliza dentro de una función para devolver un valor calculado?", options: ["send.", "return.", "echo."], correct: 1 },
            { q: "2. En casi todos los lenguajes de programación, ¿en qué índice numérico comienza un arreglo?", options: ["Índice 0.", "Índice 1.", "Índice -1."], correct: 0 },
            { q: "3. La principal ventaja de dividir un programa en funciones modulares es:", options: ["Reutilizar código, facilitar mantenimiento y reducir errores.", "Que no requiere compilación.", "Evitar usar variables."], correct: 0 },
            { q: "4. Si un arreglo tiene una longitud de 6 elementos, ¿cuál es el índice del último elemento?", options: ["Índice 6.", "Índice 5 (longitud - 1).", "Índice 4."], correct: 1 },
            { q: "5. ¿Qué es un parámetro en una función?", options: ["Una variable que recibe el dato enviado a la función para procesarlo.", "El nombre del archivo.", "El resultado final."], correct: 0 },
            { q: "6. ¿Qué ocurre si intentamos acceder a un índice que no existe en el arreglo (ej. arreglo[10] en lista de 3 elementos)?", options: ["Devuelve 0 automáticamente.", "Se produce un error de desbordamiento (Index Out of Bounds).", "Crea el elemento."], correct: 1 },
            { q: "7. ¿Qué es el 'ámbito' o 'alcance' (scope) de una variable local declarada dentro de una función?", options: ["Solo es accesible y existe dentro de esa función.", "Se puede modificar desde cualquier archivo de la computadora.", "Dura para siempre."], correct: 0 },
            { q: "8. ¿Cómo se conoce al proceso mediante el cual una función se llama a sí misma para resolver un problema?", options: ["Iteración lineal.", "Recursividad.", "Polimorfismo."], correct: 1 },
            { q: "9. ¿Qué función o método se usa habitualmente para conocer la cantidad de elementos en una lista?", options: ["count() / length / len().", "size_of_ram().", "total_elements()."], correct: 0 },
            { q: "10. ¿Cuál es la forma más común y eficiente de recorrer todos los elementos de un arreglo secuencialmente?", options: ["Con un bucle for o foreach.", "Repitiendo 50 líneas a mano.", "Con un condicional if."], correct: 0 }
        ]
    }
};

// 15 PREGUNTAS INTEGRADORAS PARA EL EXAMEN FINAL
const finalQuestionsProg = [
    { q: "1. Un algoritmo se define formalmente como:", options: ["Un conjunto desordenado de fórmulas matemáticas.", "Una secuencia lógica, finita, precisa y determinista de instrucciones.", "Un lenguaje de programación compilado."], correct: 1 },
    { q: "2. En un diagrama de flujo, la figura que representa una entrada o salida de datos es:", options: ["El paralelogramo.", "El rombo.", "El óvalo."], correct: 0 },
    { q: "3. ¿Cuál es el resultado de la expresión: (10 - 2 * 3) + 4?", options: ["28.", "8 (primero la multiplicación 2*3=6, luego 10-6=4, y 4+4=8).", "12."], correct: 1 },
    { q: "4. Si una variable almacena el valor true, su tipo de dato es:", options: ["Integer.", "Boolean.", "String."], correct: 1 },
    { q: "5. ¿Qué operador relacional se emplea para verificar si dos valores son diferentes?", options: ["<> o !=.", "==.", "><."], correct: 0 },
    { q: "6. En la condición: (A OR B), ¿cuándo es verdadera la expresión?", options: ["Solo cuando ambas son verdaderas.", "Basta con que al menos una de las dos sea verdadera.", "Únicamente cuando ambas son falsas."], correct: 1 },
    { q: "7. Si en un ciclo 'while' la condición nunca cambia a falso, se genera un:", options: ["Bucle infinito que no concluye.", "Error de sintaxis en compilación.", "Bucle for equivalente."], correct: 0 },
    { q: "8. En una prueba de escritorio, ¿qué componente se monitorea prioritariamente?", options: ["El valor de las variables tras cada paso de ejecución.", "La resolución del monitor.", "La velocidad del ventilador."], correct: 0 },
    { q: "9. ¿Cuál es la función de la sentencia 'break' en una estructura iterativa?", options: ["Continuar con la siguiente vuelta.", "Forzar la salida inmediata del bucle.", "Reiniciar el sistema operativo."], correct: 1 },
    { q: "10. Si declaramos una lista con 8 elementos, ¿cuál es el índice del primer elemento?", options: ["0.", "1.", "-1."], correct: 0 },
    { q: "11. ¿Cuál es el índice del último elemento en un arreglo de tamaño 8?", options: ["8.", "7 (tamaño menos 1).", "6."], correct: 1 },
    { q: "12. En programación estructurada, la modularidad permite:", options: ["Separar el código en funciones con responsabilidades específicas.", "Eliminar la necesidad de probar el código.", "Programar sin usar memoria."], correct: 0 },
    { q: "13. La instrucción 'return' en una función tiene como objetivo:", options: ["Imprimir en consola.", "Devolver el resultado calculado al invocador de la función.", "Reiniciar el bucle for."], correct: 1 },
    { q: "14. Una variable que cuenta cuántas veces ha ocurrido un suceso en un bucle (c = c + 1) es:", options: ["Un acumulador.", "Un contador.", "Un vector."], correct: 1 },
    { q: "15. ¿Qué ocurre si un programa intenta acceder al índice 10 de un arreglo de 5 elementos?", options: ["Devuelve 0 sin problema.", "Genera una excepción de índice fuera de rango (Index out of range).", "Amplía el arreglo solo."], correct: 1 }
];

let currentModKey = 'm1';
let passedModules = JSON.parse(localStorage.getItem('passed_modules_prog_bank') || '[]');

function initProg() {
    updateProgressState();
    loadLesson('m1_video');
}

function loadLesson(targetKey) {
    const parts = targetKey.split('_');
    const modKey = parts[0];
    const resource = parts[1];

    currentModKey = modKey;
    const data = modulesDataProg[modKey];
    if (!data) return;

    if (resource === 'quiz') {
        showModuleQuiz(modKey);
        return;
    }

    document.getElementById('cardLessonView').classList.remove('d-none');
    document.getElementById('cardModuleQuizView').classList.add('d-none');
    document.getElementById('cardLockedView').classList.add('d-none');
    document.getElementById('cardFinalQuizView').classList.add('d-none');
    document.getElementById('cardCertificateView').classList.add('d-none');

    document.getElementById('lessonTag').innerText = data.tag;
    document.getElementById('lessonDuration').innerText = '⏱ ' + data.dur;
    document.getElementById('lessonTitle').innerText = data.title;
    document.getElementById('videoPlayer').src = data.videoUrl;
    document.getElementById('videoNotes').innerText = data.videoNotes;
    document.getElementById('pdfHeadingTitle').innerText = data.pdfHeading;
    document.getElementById('pdfDocumentHeading').innerText = data.pdfHeading;
    document.getElementById('pdfBodyHtml').innerHTML = data.pdfText;

    switchResourceTab(resource === 'pdf' ? 'pdf' : 'video');
    highlightNavBtn('nav-' + targetKey);
}

function switchResourceTab(type) {
    const tabVid = document.getElementById('tab-video-link');
    const tabPdf = document.getElementById('tab-pdf-link');
    const boxVid = document.getElementById('contentVideoBox');
    const boxPdf = document.getElementById('contentPdfBox');

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

function highlightNavBtn(btnId) {
    document.querySelectorAll('.lesson-btn').forEach(b => b.classList.remove('active'));
    const btn = document.getElementById(btnId);
    if (btn) btn.classList.add('active');
}

function showModuleQuiz(modKey) {
    const data = modulesDataProg[modKey];
    document.getElementById('cardLessonView').classList.add('d-none');
    document.getElementById('cardModuleQuizView').classList.remove('d-none');
    document.getElementById('cardLockedView').classList.add('d-none');
    document.getElementById('cardFinalQuizView').classList.add('d-none');
    document.getElementById('cardCertificateView').classList.add('d-none');

    document.getElementById('quizModuleBadge').innerText = data.tag;
    document.getElementById('quizModuleTitle').innerText = 'Examen del ' + data.title + ' (10 preguntas)';
    document.getElementById('quizModuleResult').classList.add('d-none');

    const container = document.getElementById('quizQuestionsContainer');
    container.innerHTML = '';
    data.questions.forEach((qObj, idx) => {
        let optsHtml = '';
        qObj.options.forEach((opt, optIdx) => {
            optsHtml += `
                <div class="form-check mb-1">
                    <input class="form-check-input" type="radio" name="mq_${idx}" value="${optIdx}" id="mq_${idx}_${optIdx}" required>
                    <label class="form-check-label small text-slate-700" for="mq_${idx}_${optIdx}">${opt}</label>
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

    highlightNavBtn('nav-' + modKey + '_quiz');
}

function submitModuleQuiz(e) {
    e.preventDefault();
    const data = modulesDataProg[currentModKey];
    let correctCount = 0;

    data.questions.forEach((qObj, idx) => {
        const selected = document.querySelector(`input[name="mq_${idx}"]:checked`);
        if (selected && parseInt(selected.value) === qObj.correct) {
            correctCount++;
        }
    });

    const score = Math.round((correctCount / data.questions.length) * 100);
    const resBox = document.getElementById('quizModuleResult');
    resBox.classList.remove('d-none');

    if (score >= 70) {
        if (!passedModules.includes(currentModKey)) {
            passedModules.push(currentModKey);
            localStorage.setItem('passed_modules_prog_bank', JSON.stringify(passedModules));
        }
        resBox.className = 'mt-4 p-3 rounded-3 text-center bg-success-subtle text-success border border-success-subtle';
        resBox.innerHTML = `
            <h5 class="fw-bold mb-1">¡Aprobado con éxito! Calificación: ${score} / 100 (${correctCount} de 10 aciertos)</h5>
            <p class="small mb-2">Has superado la evaluación parcial de 10 preguntas del ${data.tag}.</p>
            <button type="button" class="btn btn-success btn-sm fw-bold" onclick="goToNextModule('${currentModKey}')">Continuar al Siguiente Módulo ▷</button>
        `;
        updateProgressState();
    } else {
        resBox.className = 'mt-4 p-3 rounded-3 text-center bg-warning-subtle text-dark border border-warning-subtle';
        resBox.innerHTML = `
            <h5 class="fw-bold mb-1">Calificación: ${score} / 100 (${correctCount} de 10 aciertos)</h5>
            <p class="small mb-2">Se requiere un mínimo de 70% (7 aciertos) para acreditar el módulo. Repasa el video y el PDF antes de reintentar.</p>
            <button type="button" class="btn btn-outline-dark btn-sm fw-bold" onclick="backToLesson()">Repasar Video y Guía PDF</button>
        `;
    }
}

function goToNextModule(currentKey) {
    const keys = ['m1', 'm2', 'm3', 'm4', 'm5'];
    const idx = keys.indexOf(currentKey);
    if (idx < keys.length - 1) {
        const nextKey = keys[idx + 1];
        loadLesson(nextKey + '_video');
    } else {
        openFinalEvaluation();
    }
}

function backToLesson() {
    loadLesson(currentModKey + '_video');
}

function openFinalEvaluation() {
    const allPassed = ['m1', 'm2', 'm3', 'm4', 'm5'].every(k => passedModules.includes(k));
    if (!allPassed) {
        document.getElementById('cardLessonView').classList.add('d-none');
        document.getElementById('cardModuleQuizView').classList.add('d-none');
        document.getElementById('cardFinalQuizView').classList.add('d-none');
        document.getElementById('cardCertificateView').classList.add('d-none');
        document.getElementById('cardLockedView').classList.remove('d-none');
        return;
    }

    document.getElementById('cardLessonView').classList.add('d-none');
    document.getElementById('cardModuleQuizView').classList.add('d-none');
    document.getElementById('cardLockedView').classList.add('d-none');
    document.getElementById('cardCertificateView').classList.add('d-none');
    document.getElementById('cardFinalQuizView').classList.remove('d-none');

    // Generar las 15 preguntas
    const fContainer = document.getElementById('finalQuizQuestionsContainer');
    fContainer.innerHTML = '';
    finalQuestionsProg.forEach((qObj, idx) => {
        let opts = '';
        qObj.options.forEach((opt, oIdx) => {
            opts += `
                <div class="form-check mb-1">
                    <input class="form-check-input" type="radio" name="fq_${idx}" value="${oIdx}" id="fq_${idx}_${oIdx}" required>
                    <label class="form-check-label small text-slate-700" for="fq_${idx}_${oIdx}">${opt}</label>
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

function submitFinalQuiz(e) {
    e.preventDefault();
    let correct = 0;
    finalQuestionsProg.forEach((qObj, idx) => {
        const sel = document.querySelector(`input[name="fq_${idx}"]:checked`);
        if (sel && parseInt(sel.value) === qObj.correct) {
            correct++;
        }
    });

    const score = Math.round((correct / finalQuestionsProg.length) * 100);
    if (score >= 70) {
        alert(`¡Felicidades! Calificación en examen final: ${score}/100 (${correct} de 15 aciertos). Tu certificado oficial ha sido emitido.`);
        openCertificate();
    } else {
        alert(`Calificación final: ${score}/100 (${correct} de 15 aciertos). Necesitas al menos 11 aciertos (70%) para acreditar.`);
    }
}

function openCertificate() {
    document.getElementById('cardLessonView').classList.add('d-none');
    document.getElementById('cardModuleQuizView').classList.add('d-none');
    document.getElementById('cardLockedView').classList.add('d-none');
    document.getElementById('cardFinalQuizView').classList.add('d-none');
    document.getElementById('cardCertificateView').classList.remove('d-none');
}

function updateProgressState() {
    const total = 5;
    const done = passedModules.length;
    const pct = Math.round((done / total) * 100);

    const pBar = document.getElementById('progressBar');
    if (pBar) pBar.style.width = pct + '%';
    const bText = document.getElementById('progressPercentBadge');
    if (bText) bText.innerText = pct + '%';
    const cCount = document.getElementById('completedItemsCount');
    if (cCount) cCount.innerText = `${done} de ${total} módulos aprobados`;

    ['m1', 'm2', 'm3', 'm4', 'm5'].forEach(k => {
        const isPassed = passedModules.includes(k);
        const b = document.getElementById('badge-' + k);
        const qb = document.getElementById('quiz-status-' + k);
        const lk = document.getElementById('lock-stat-' + k);

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
    const notice = document.getElementById('lockStatusNotice');
    const fLockIcon = document.getElementById('finalLockIcon');
    const fBadge = document.getElementById('finalQuizBadge');
    const certBtn = document.getElementById('btnNavCert');
    const certBadge = document.getElementById('finalCertBadge');

    if (isAll) {
        if (notice) notice.innerText = '🔓 Examen final desbloqueado (15 preguntas)';
        if (fLockIcon) fLockIcon.innerText = '📝';
        if (fBadge) { fBadge.className = 'badge bg-success text-white'; fBadge.innerText = 'Desbloqueada'; }
        if (certBtn) certBtn.classList.remove('disabled');
        if (certBadge) { certBadge.innerText = 'Disponible'; }
    } else {
        if (notice) notice.innerText = '🔒 Examen final bloqueado (requiere 5 módulos)';
        if (fLockIcon) fLockIcon.innerText = '🔒';
        if (fBadge) { fBadge.className = 'badge bg-slate-200 text-slate-700'; fBadge.innerText = 'Bloqueada'; }
    }
}

function navigateStep(delta) {
    if (delta > 0) {
        showModuleQuiz(currentModKey);
    } else {
        const keys = ['m1', 'm2', 'm3', 'm4', 'm5'];
        const idx = keys.indexOf(currentModKey);
        if (idx > 0) {
            loadLesson(keys[idx - 1] + '_video');
        }
    }
}

const pdfFileMap = {
    'm1': '<?= asset("pdf/fundamentos-modulo-1-algoritmos.pdf") ?>',
    'm2': '<?= asset("pdf/fundamentos-modulo-2-variables.pdf") ?>',
    'm3': '<?= asset("pdf/fundamentos-modulo-3-condicionales.pdf") ?>',
    'm4': '<?= asset("pdf/fundamentos-modulo-4-ciclos.pdf") ?>',
    'm5': '<?= asset("pdf/fundamentos-modulo-5-funciones.pdf") ?>'
};

function downloadPdfDoc() {
    const fileUrl = pdfFileMap[currentModKey] || '<?= asset("pdf/fundamentos-modulo-1-algoritmos.pdf") ?>';
    const link = document.createElement('a');
    link.href = fileUrl;
    link.download = fileUrl.split('/').pop();
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

document.addEventListener('DOMContentLoaded', initProg);
</script>

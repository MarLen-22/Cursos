<section id="inicio" class="hero-section bg-navy text-white position-relative overflow-hidden py-5">
    <div class="bg-grid position-absolute top-0 start-0 w-100 h-100 pointer-events-none"></div>
    <div class="container-fluid max-w-7xl mx-auto px-4 py-4 py-lg-5 position-relative z-1">
        <div class="row align-items-center g-5">
            <!-- Columna Izquierda: Introducción y CTA -->
            <div class="col-12 col-lg-6">
                <div class="feature-tag mb-4">
                    <span class="text-cyan fw-bold fs-5">#</span>
                </div>
                <h1 class="display-5 fw-bold lh-tight mb-4 text-white">
                    Desarrolla el razonamiento lógico, no la memorización mecánica
                </h1>
                <p class="text-slate-400 fs-5 mb-4 leading-relaxed">
                    Nuestros simuladores te permiten visualizar conceptos abstractos, experimentar con decisiones y
                    resolver problemas paso a paso para construir una comprensión que sí puedes aplicar.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-5">
                    <a href="<?= url('/modulos') ?>" class="btn btn-cyan btn-lg px-4 py-3 fw-bold shadow-cyan">
                        Explorar Simuladores
                    </a>
                    <a href="<?= url('/metodologia') ?>" class="btn btn-outline-light btn-lg px-4 py-3 fw-semibold">
                        Conocer la metodología
                    </a>
                </div>
                <div class="d-flex flex-wrap gap-4 text-sm text-slate-400 font-medium">
                    <span class="d-flex align-items-center gap-2">
                        <span class="text-cyan">⊙</span> Aprende haciendo
                    </span>
                    <span class="d-flex align-items-center gap-2">
                        <span class="text-cyan">⊙</span> Razonamiento visible
                    </span>
                    <span class="d-flex align-items-center gap-2">
                        <span class="text-cyan">⊙</span> Aprendizaje Basado en Problemas
                    </span>
                </div>
            </div>

            <!-- Columna Derecha: Tarjeta Simulador Visual -->
            <div class="col-12 col-lg-6">
                <div class="simulador-card border border-slate-700 rounded-4 overflow-hidden shadow-2xl">
                    <div class="simulador-header d-flex align-items-center gap-2 px-4 py-3 border-bottom border-slate-700 bg-navy-card">
                        <span class="dot bg-danger"></span>
                        <span class="dot bg-warning"></span>
                        <span class="dot bg-cyan"></span>
                        <span class="ms-auto small text-slate-400 font-monospace">simulador.logica</span>
                    </div>
                    <div class="p-4 bg-navy-body">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <div class="p-3 rounded-3 bg-navy-card border border-slate-700 h-100 d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="small text-slate-300 fw-medium">Algoritmo</span>
                                        <span class="text-cyan small">▷</span>
                                    </div>
                                    <div class="space-y-2 mb-4">
                                        <div class="progress progress-bar-glow mb-2" style="height: 6px;">
                                            <div class="progress-bar bg-cyan" style="width: 100%"></div>
                                        </div>
                                        <div class="progress mb-2" style="height: 6px;">
                                            <div class="progress-bar bg-slate-600" style="width: 80%"></div>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-slate-600" style="width: 65%"></div>
                                        </div>
                                    </div>
                                    <div class="mt-auto p-2 rounded bg-navy-dark small text-cyan font-monospace">
                                        if problema → hipótesis
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="p-3 rounded-3 bg-navy-card border border-slate-700 h-100 position-relative min-h-140 d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <span class="small text-slate-300 fw-medium">Red de ideas</span>
                                        <span class="text-cyan small">☍</span>
                                    </div>
                                    <div class="flex-grow-1 position-relative d-flex align-items-center justify-content-center my-3">
                                        <svg class="position-absolute w-100 h-100" style="pointer-events: none;">
                                            <line x1="25%" y1="25%" x2="50%" y2="50%" stroke="#475569" stroke-width="1.5" />
                                            <line x1="75%" y1="25%" x2="50%" y2="50%" stroke="#475569" stroke-width="1.5" />
                                            <line x1="50%" y1="50%" x2="65%" y2="75%" stroke="#475569" stroke-width="1.5" />
                                        </svg>
                                        <div class="node-circle node-1"></div>
                                        <div class="node-circle node-2"></div>
                                        <div class="node-circle node-3"></div>
                                        <div class="node-circle node-center"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 p-3 rounded-3 bg-navy-card border border-slate-700 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="font-monospace small text-cyan mb-1">∫ f(x) dx = comprensión</div>
                                <div class="small text-slate-400">Observa el cambio, prueba una decisión y explica tu resultado.</div>
                            </div>
                            <div class="fs-2 text-cyan font-serif ms-2">Σ</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección 3 Pilares -->
<section class="py-5 bg-white border-bottom border-slate-200">
    <div class="container-fluid max-w-7xl mx-auto px-4 py-3">
        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="p-4 border border-slate-100 rounded-4 shadow-xs h-100 hover-card">
                    <div class="icon-circle bg-cyan-subtle text-cyan mb-3">◎</div>
                    <h3 class="h5 fw-bold mb-2">Conceptos que se ven</h3>
                    <p class="small text-slate-500 mb-0">Convierte variables, algoritmos y modelos matemáticos en experiencias interactivas explorables.</p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="p-4 border border-slate-100 rounded-4 shadow-xs h-100 hover-card">
                    <div class="icon-circle bg-cyan-subtle text-cyan mb-3">☍</div>
                    <h3 class="h5 fw-bold mb-2">Decisiones con sentido</h3>
                    <p class="small text-slate-500 mb-0">Construye estrategias paso a paso, comprueba hipótesis y aprende de cada iteración.</p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="p-4 border border-slate-100 rounded-4 shadow-xs h-100 hover-card">
                    <div class="icon-circle bg-cyan-subtle text-cyan mb-3">🎓</div>
                    <h3 class="h5 fw-bold mb-2">Progreso acompañado</h3>
                    <p class="small text-slate-500 mb-0">Recibe pistas y retroalimentación para argumentar mejor tus soluciones en el aula y en la nube.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Módulos Destacados -->
<section class="py-5 bg-slate-50">
    <div class="container-fluid max-w-7xl mx-auto px-4 py-3">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <p class="text-cyan text-uppercase fw-bold small tracking-wider mb-1">Rutas de Aprendizaje</p>
                <h2 class="h3 fw-bold mb-0">Módulos para pensar y resolver</h2>
            </div>
            <a href="<?= url('/modulos') ?>" class="btn btn-outline-primary btn-sm d-none d-sm-inline-flex align-items-center gap-1">
                Ver todos los módulos ↗
            </a>
        </div>

        <div class="row g-4">
            <?php foreach (array_slice($modulos ?? [], 0, 4) as $m): ?>
                <div class="col-12 col-md-6">
                    <a href="<?= url('/modulos/' . e($m->slug)) ?>" class="text-decoration-none text-reset">
                        <div class="card h-100 border-slate-200 rounded-4 shadow-xs hover-card p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="module-icon-box bg-<?= e($m->color->value) ?>-subtle text-<?= e($m->color->value) ?> fs-4">
                                    <?= e($m->icono) ?>
                                </div>
                                <span class="badge bg-<?= e($m->color->value) ?>-subtle text-<?= e($m->color->value) ?> rounded-pill px-3 py-1">
                                    <?= e($m->nivel->etiqueta()) ?>
                                </span>
                            </div>
                            <h3 class="h5 fw-bold mb-2"><?= e($m->titulo) ?></h3>
                            <p class="text-slate-500 small mb-4 flex-grow-1"><?= e($m->descripcion) ?></p>
                            <div class="d-flex justify-content-between align-items-center text-cyan fw-semibold small">
                                <span>Explorar simulador</span>
                                <span>↗</span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

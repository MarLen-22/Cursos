<section class="py-5 bg-slate-50 min-vh-75">
    <div class="container-fluid max-w-7xl mx-auto px-4">
        <div class="row g-5 align-items-start">
            <!-- Columna Izquierda: Introducción y acción -->
            <div class="col-12 col-lg-4">
                <p class="text-cyan text-uppercase fw-bold small tracking-wider mb-2">Evaluación para aprender</p>
                <h1 class="h2 fw-bold mb-3 leading-tight">Evalúa tu proceso, no solo tu respuesta final</h1>
                <p class="text-slate-500 mb-4">
                    La plataforma valora la argumentación, las decisiones, la mejora continua y la aplicación
                    de tus conocimientos en retos significativos.
                </p>
                <div class="p-4 bg-white border border-slate-200 rounded-4 shadow-xs">
                    <h2 class="h6 fw-bold mb-2">¿Listo para comenzar?</h2>
                    <p class="small text-slate-500 mb-3">La evaluación diagnóstica te permite identificar qué conceptos dominas y en cuáles requieres práctica interactiva.</p>
                    <a href="<?= url('/modulos') ?>" class="btn btn-cyan fw-bold w-100 shadow-cyan">
                        Comenzar evaluación diagnóstica
                    </a>
                </div>
            </div>

            <!-- Columna Derecha: Tarjetas de Tipos de Evaluación -->
            <div class="col-12 col-lg-8">
                <div class="d-flex flex-column gap-3 mb-4">
                    <?php foreach ($evaluaciones as $ev): ?>
                        <div class="card border-0 rounded-4 shadow-xs p-4 hover-card">
                            <div class="d-flex align-items-start gap-3">
                                <div class="icon-circle bg-cyan-subtle text-cyan fs-4 flex-shrink-0">
                                    <?= e($ev->icono) ?>
                                </div>
                                <div>
                                    <h2 class="h5 fw-bold mb-1"><?= e($ev->titulo) ?></h2>
                                    <span class="badge bg-slate-100 text-slate-600 mb-2"><?= e($ev->etapa) ?></span>
                                    <p class="text-slate-500 small mb-0"><?= e($ev->descripcion) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Indicadores de Progreso Dinámico -->
                <div class="card border-0 rounded-4 shadow-xs p-4 bg-white">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h2 class="h5 fw-bold mb-1">Indicadores de Progreso en Lógica</h2>
                            <p class="text-slate-500 small mb-0">Visualiza avances y recibe señales claras para decidir tu siguiente paso.</p>
                        </div>
                        <span class="badge bg-cyan-subtle text-cyan fw-semibold px-3 py-2 rounded-pill">Progreso guiado</span>
                    </div>

                    <div class="row g-4 mt-2">
                        <div class="col-12 col-md-4">
                            <div class="d-flex justify-content-between text-sm font-semibold mb-1">
                                <span>Comprensión</span>
                                <span class="text-cyan"><?= (int) ($progreso['comprension'] ?? 78) ?>%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-cyan" style="width: <?= (int) ($progreso['comprension'] ?? 78) ?>%"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="d-flex justify-content-between text-sm font-semibold mb-1">
                                <span>Argumentación</span>
                                <span class="text-indigo"><?= (int) ($progreso['argumentacion'] ?? 64) ?>%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-indigo" style="width: <?= (int) ($progreso['argumentacion'] ?? 64) ?>%"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="d-flex justify-content-between text-sm font-semibold mb-1">
                                <span>Aplicación</span>
                                <span class="text-purple"><?= (int) ($progreso['aplicacion'] ?? 71) ?>%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-purple" style="width: <?= (int) ($progreso['aplicacion'] ?? 71) ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-slate-50 min-vh-75">
    <div class="container-fluid max-w-7xl mx-auto px-3 px-md-4">
        <div class="mb-4">
            <h1 class="h3 fw-bold mb-1">Resultados de búsqueda</h1>
            <p class="text-muted small">
                <?php if ($q !== ''): ?>
                    Mostrando coincidencias para <strong class="text-navy">«<?= e($q) ?>»</strong>
                <?php else: ?>
                    Ingresa un término para buscar módulos interactivos, recursos y páginas.
                <?php endif; ?>
            </p>
        </div>

        <?php if ($q !== '' && empty($modulos) && empty($paginas)): ?>
            <div class="card border-0 rounded-4 shadow-xs p-5 text-center bg-white">
                <div class="fs-1 text-muted mb-2">🔍</div>
                <h2 class="h5 fw-bold mb-2">No encontramos resultados para tu búsqueda</h2>
                <p class="text-muted small mb-3">Intenta con otras palabras clave como «programación», «cálculo», «física» o «evaluación».</p>
                <div>
                    <a href="<?= url('/modulos') ?>" class="btn btn-cyan btn-sm fw-bold px-4">Ver todos los módulos</a>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($modulos)): ?>
            <div class="mb-5">
                <h2 class="h5 fw-bold mb-3 d-flex align-items-center gap-2">
                    <span>Módulos y cursos</span>
                    <span class="badge bg-navy text-cyan"><?= count($modulos) ?></span>
                </h2>
                <div class="row g-4">
                    <?php foreach ($modulos as $m): ?>
                        <div class="col-12 col-md-6">
                            <div class="card h-100 border-0 rounded-4 shadow-xs p-4 bg-white hover-card d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="fs-3"><?= e($m->icono) ?></span>
                                    <span class="badge bg-<?= e($m->color->value) ?>-subtle text-<?= e($m->color->value) ?> rounded-pill">
                                        <?= e($m->nivel->etiqueta()) ?>
                                    </span>
                                </div>
                                <h3 class="h6 fw-bold mb-2">
                                    <a href="<?= url('/modulos/' . $m->slug) ?>" class="text-decoration-none text-navy hover-cyan">
                                        <?= e($m->titulo) ?>
                                    </a>
                                </h3>
                                <p class="text-muted small mb-4 flex-grow-1"><?= e($m->descripcion) ?></p>
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <span class="text-muted fs-7">Simulador ABP</span>
                                    <a href="<?= url('/modulos/' . $m->slug) ?>" class="btn btn-sm btn-outline-cyan fw-bold">
                                        Explorar ▷
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($paginas)): ?>
            <div>
                <h2 class="h5 fw-bold mb-3 d-flex align-items-center gap-2">
                    <span>Secciones y páginas</span>
                    <span class="badge bg-slate-200 text-slate-700"><?= count($paginas) ?></span>
                </h2>
                <div class="list-group shadow-xs rounded-4 border-0 overflow-hidden">
                    <?php foreach ($paginas as $p): ?>
                        <a href="<?= url($p['ruta']) ?>" class="list-group-item list-group-item-action p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold text-navy"><?= e($p['titulo']) ?></div>
                                <div class="text-muted small"><?= e($p['descripcion']) ?></div>
                            </div>
                            <span class="text-cyan"><?= icon('chevron-right') ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

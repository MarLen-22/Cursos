<section class="py-5 bg-slate-50 min-vh-75">
    <div class="container-fluid max-w-7xl mx-auto px-4">
        <div class="mb-5">
            <p class="text-cyan text-uppercase fw-bold small tracking-wider mb-1">Rutas de Aprendizaje</p>
            <h1 class="h2 fw-bold mb-3">Aprende desde la exploración interactiva</h1>
            <p class="text-slate-500 mb-0 max-w-2xl">
                Elige un módulo, manipula modelos interactivos y conecta cada reto con una estrategia
                de solución fundamentada en el Aprendizaje Basado en Problemas.
            </p>
        </div>

        <div class="row g-4">
            <?php if (empty($modulos)): ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No hay módulos disponibles en este momento.</p>
                </div>
            <?php endif; ?>

            <?php foreach ($modulos as $modulo): ?>
                <div class="col-12 col-md-6">
                    <div class="card h-100 border-slate-200 rounded-4 shadow-xs hover-card p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="module-icon-box bg-<?= e($modulo->color->value) ?>-subtle text-<?= e($modulo->color->value) ?> fs-4">
                                <?= e($modulo->icono) ?>
                            </div>
                            <span class="badge bg-<?= e($modulo->color->value) ?>-subtle text-<?= e($modulo->color->value) ?> rounded-pill px-3 py-1">
                                <?= e($modulo->nivel->etiqueta()) ?>
                            </span>
                        </div>
                        <h2 class="h5 fw-bold mb-2"><?= e($modulo->titulo) ?></h2>
                        <p class="text-slate-500 small mb-4 flex-grow-1"><?= e($modulo->descripcion) ?></p>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top border-slate-100">
                            <span class="text-muted small">Módulo interactivo</span>
                            <a href="<?= url('/modulos/' . e($modulo->slug)) ?>" class="btn btn-sm btn-outline-cyan fw-bold px-3">
                                Explorar módulo ↗
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

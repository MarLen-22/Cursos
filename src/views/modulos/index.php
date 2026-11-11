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
                <?php
                    $esCalculo = in_array($modulo->slug, ['calculo-diferencial', 'calculo-diferencial-e-integral'], true)
                        || str_contains(mb_strtolower($modulo->titulo), 'cálculo')
                        || str_contains(mb_strtolower($modulo->titulo), 'calculo');
                    $esFisica = in_array($modulo->slug, ['fisica', 'fisica-causa-efecto-y-simulacion'], true)
                        || str_contains(mb_strtolower($modulo->titulo), 'física')
                        || str_contains(mb_strtolower($modulo->titulo), 'fisica');
                    $tituloMostrar = $esCalculo
                        ? 'Cálculo Diferencial'
                        : ($esFisica ? 'Física: Causa, Efecto y Simulación' : $modulo->titulo);
                ?>
                <div class="col-12 col-md-6">
                    <div class="card h-100 border-slate-200 rounded-4 shadow-xs hover-card p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="module-icon-box bg-<?= e($modulo->color->value) ?>-subtle text-<?= e($modulo->color->value) ?> fs-4">
                                <?= e($modulo->icono) ?>
                            </div>
                            <div class="d-flex flex-wrap gap-1 justify-content-end">
                                <?php if ($esCalculo): ?>
                                    <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1">
                                        Enfoque Lógico-Visual · 5 Lecciones
                                    </span>
                                <?php elseif ($esFisica): ?>
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">
                                        Causa, Efecto y Simulación · 5 Lecciones
                                    </span>
                                <?php endif; ?>
                                <span class="badge bg-<?= e($modulo->color->value) ?>-subtle text-<?= e($modulo->color->value) ?> rounded-pill px-3 py-1">
                                    <?= e($modulo->nivel->etiqueta()) ?>
                                </span>
                            </div>
                        </div>
                        <h2 class="h5 fw-bold mb-2"><?= e($tituloMostrar) ?></h2>
                        <p class="text-slate-500 small mb-3 flex-grow-1"><?= e($modulo->descripcion) ?></p>
                        <?php if ($esCalculo): ?>
                            <div class="d-flex flex-wrap gap-1 mb-3">
                                <span class="badge bg-light text-secondary border">1. Funciones (Cajas Negras)</span>
                                <span class="badge bg-light text-secondary border">2. Límites (Iteraciones)</span>
                                <span class="badge bg-light text-secondary border">3. La Derivada</span>
                                <span class="badge bg-light text-secondary border">4. Reglas (Patrones)</span>
                                <span class="badge bg-light text-secondary border">5. Optimización</span>
                                <span class="badge bg-light text-success border">🎓 Encuesta y Certificado</span>
                            </div>
                        <?php elseif ($esFisica): ?>
                            <div class="d-flex flex-wrap gap-1 mb-3">
                                <span class="badge bg-light text-secondary border">1. Vectores ($x, y$)</span>
                                <span class="badge bg-light text-secondary border">2. Cinemática (El Cañón)</span>
                                <span class="badge bg-light text-secondary border">3. Leyes de Newton (DCL)</span>
                                <span class="badge bg-light text-secondary border">4. Trabajo y Energía</span>
                                <span class="badge bg-light text-secondary border">5. Colisiones (Billar)</span>
                                <span class="badge bg-light text-success border">🎓 Encuesta y Certificado</span>
                            </div>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top border-slate-100">
                            <span class="text-muted small"><?= ($esCalculo || $esFisica) ? 'Split-Screen · Simuladores + Guías Visuales' : 'Módulo interactivo' ?></span>
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

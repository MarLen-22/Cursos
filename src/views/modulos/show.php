<section class="py-5 bg-navy text-white position-relative">
    <div class="bg-grid position-absolute top-0 start-0 w-100 h-100 pointer-events-none"></div>
    <div class="container-fluid max-w-7xl mx-auto px-4 position-relative z-1">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= url('/modulos') ?>" class="text-slate-400 text-decoration-none">Módulos</a></li>
                <li class="breadcrumb-item active text-cyan" aria-current="page"><?= e($modulo->titulo) ?></li>
            </ol>
        </nav>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="module-icon-box bg-<?= e($modulo->color->value) ?>-subtle text-<?= e($modulo->color->value) ?> fs-3">
                    <?= e($modulo->icono) ?>
                </div>
                <div>
                    <h1 class="h2 fw-bold text-white mb-1"><?= e($modulo->titulo) ?></h1>
                    <span class="badge bg-cyan text-navy fw-bold"><?= e($modulo->nivel->etiqueta()) ?></span>
                </div>
            </div>
            <a href="<?= url('/modulos') ?>" class="btn btn-outline-light btn-sm">← Volver a módulos</a>
        </div>
    </div>
</section>

<section class="py-5 bg-slate-50">
    <div class="container-fluid max-w-7xl mx-auto px-4">
        <div class="row g-4">
            <!-- Columna Principal: Simulador y Reto ABP -->
            <div class="col-12 col-lg-8">
                <!-- Simulador Interactivo de Fundamentos -->
                <div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-4">
                    <div class="card-header bg-navy text-white px-4 py-3 d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">⚡ Simulador Interactivo de Lógica</span>
                        <span class="badge bg-success-subtle text-success">En línea</span>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <div class="alert alert-info py-2 px-3 small mb-3">
                            <strong>Reto ABP:</strong> Determina la hipótesis para la condición de paro del algoritmo y verifica cómo cambian los estados.
                        </div>

                        <!-- Editor / Visor de Código Interactivo -->
                        <div class="bg-navy rounded-3 p-3 text-white font-monospace small mb-3" id="simulatorConsole">
                            <div class="text-slate-400 mb-2">// Código del modelo lógico</div>
                            <div class="line"><span class="text-purple">function</span> <span class="text-cyan">evaluarPaso</span>(problema, paso) {</div>
                            <div class="line ps-3"><span class="text-purple">let</span> estado = { resolucion: <span class="text-warning">"en_progreso"</span>, iteraciones: paso };</div>
                            <div class="line ps-3"><span class="text-purple">if</span> (paso &gt;= <span class="text-cyan" id="limiteDisplay">4</span>) {</div>
                            <div class="line ps-4">estado.resolucion = <span class="text-success">"completado"</span>;</div>
                            <div class="line ps-3">}</div>
                            <div class="line ps-3"><span class="text-purple">return</span> estado;</div>
                            <div class="line">}</div>
                        </div>

                        <!-- Controles de simulación -->
                        <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between p-3 bg-slate-100 rounded-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="small fw-semibold">Paso de ejecución:</span>
                                <span class="badge bg-navy text-cyan fs-6" id="simStepBadge">0 / 4</span>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="simResetBtn">Reiniciar</button>
                                <button type="button" class="btn btn-sm btn-cyan fw-bold" id="simNextBtn">Ejecutar siguiente paso ▷</button>
                            </div>
                        </div>

                        <!-- Consola de salida de resultados -->
                        <div class="mt-3 p-3 bg-slate-900 rounded-3 font-monospace text-xs text-slate-300" id="simOutputLog">
                            <span class="text-cyan">&gt;</span> Simulador listo. Haz clic en "Ejecutar siguiente paso".
                        </div>
                    </div>
                </div>

                <!-- Contenido detallado del módulo -->
                <div class="card border-0 rounded-4 shadow-sm p-4">
                    <h2 class="h4 fw-bold mb-3">Descripción y Objetivos de Aprendizaje</h2>
                    <p class="text-slate-600 leading-relaxed mb-4"><?= e($modulo->descripcion) ?></p>

                    <?php if ($modulo->contenido): ?>
                        <div class="p-4 bg-slate-50 border rounded-3 text-slate-700 leading-relaxed whitespace-pre-line mb-4">
                            <?= nl2br(e($modulo->contenido)) ?>
                        </div>
                    <?php endif; ?>

                    <h3 class="h5 fw-bold mb-2">Metodología ABP aplicada en este módulo</h3>
                    <ul class="list-unstyled space-y-2 text-slate-600 small">
                        <li class="d-flex align-items-start gap-2 mb-2">
                            <span class="badge bg-cyan text-navy">1</span>
                            <span><strong>Explora:</strong> Identifica el problema y las variables de entrada.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-2">
                            <span class="badge bg-cyan text-navy">2</span>
                            <span><strong>Formula:</strong> Plantea una hipótesis antes de realizar pruebas.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-2">
                            <span class="badge bg-cyan text-navy">3</span>
                            <span><strong>Experimenta:</strong> Modifica parámetros y observa el comportamiento en tiempo real.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <span class="badge bg-cyan text-navy">4</span>
                            <span><strong>Reflexiona:</strong> Justifica por qué el resultado concuerda con la lógica establecida.</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Columna Lateral: Estado y Progreso -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm p-4 mb-4">
                    <h3 class="h6 text-uppercase fw-bold text-slate-400 tracking-wider mb-3">Información del Módulo</h3>
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-slate-500">Nivel académico:</span>
                            <span class="fw-semibold"><?= e($modulo->nivel->etiqueta()) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-slate-500">Última actualización:</span>
                            <span class="fw-semibold"><?= fecha($modulo->actualizadoEn, 'd/m/Y') ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-slate-500">Metodología:</span>
                            <span class="fw-semibold">Aprendizaje Basado en Problemas</span>
                        </li>
                    </ul>
                    <?php if (App\Core\Auth::check()): ?>
                        <?php if ($inscripcion !== null): ?>
                            <div class="mt-3 p-3 bg-light rounded-3 border">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small fw-bold">Tu estado:</span>
                                    <span class="badge-status-outline status-<?= e($inscripcion->estado->value) ?>">
                                        <?= e(strtoupper($inscripcion->estado->etiqueta())) ?>
                                    </span>
                                </div>
                                <div class="progress mb-3" style="height: 6px;">
                                    <div class="progress-bar bg-cyan" style="width: <?= (int) $inscripcion->avance ?>%"></div>
                                </div>
                                <?php if ($inscripcion->finalizado()): ?>
                                    <a href="<?= url('/mi-aprendizaje/' . $inscripcion->id . '/constancia') ?>" class="btn btn-sm btn-cyan w-100 fw-bold" target="_blank">
                                        Ver mi constancia 🏅
                                    </a>
                                <?php else: ?>
                                    <form action="<?= url('/modulos/' . $modulo->slug . '/avance') ?>" method="POST" class="m-0">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="accion" value="finalizar">
                                        <button type="submit" class="btn btn-sm btn-outline-success w-100 fw-bold">
                                            Completar reto y finalizar ✓
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <form action="<?= url('/modulos/' . $modulo->slug . '/inscripcion') ?>" method="POST" class="mt-3">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-cyan btn-sm fw-bold w-100 py-2 shadow-cyan">
                                    Inscribirme en este módulo ▷
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?= url('/login?next=' . rawurlencode('/modulos/' . $modulo->slug)) ?>" class="btn btn-outline-cyan btn-sm mt-3 w-100 fw-bold">
                            Inicia sesión para guardar tu avance
                        </a>
                    <?php endif; ?>
                    <a href="<?= url('/evaluacion') ?>" class="btn btn-outline-primary btn-sm mt-2 w-100">
                        Ir a la Evaluación del Módulo ↗
                    </a>
                </div>

                <div class="card border-0 rounded-4 shadow-sm p-4 bg-navy text-white">
                    <h3 class="h6 fw-bold text-cyan mb-2">¿Tienes una propuesta o solución?</h3>
                    <p class="small text-slate-300 mb-3">
                        Comparte tus resultados y argumentos para la resolución de este problema con tu docente y grupo.
                    </p>
                    <a href="mailto:hola@logicavisual.edu.mx" class="btn btn-cyan btn-sm fw-bold">Enviar retroalimentación</a>
                </div>
            </div>
        </div>
    </div>
</section>

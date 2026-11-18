<!-- KPIs de la Nueva Pestaña Administrativa: Aprendizaje, Becas y Configuración ABP -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 rounded-4 shadow-sm p-3 bg-white h-100">
            <div class="text-uppercase text-xs fw-bold text-slate-400">Total Inscripciones</div>
            <div class="fs-3 fw-bold text-navy mt-1"><?= (int) ($stats['total'] ?? 0) ?></div>
            <div class="text-xs text-slate-500">📚 Cursos en Mi Aprendizaje</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 rounded-4 shadow-sm p-3 bg-white h-100">
            <div class="text-uppercase text-xs fw-bold text-slate-400">En Curso / Inscritos</div>
            <div class="fs-3 fw-bold text-indigo mt-1"><?= (int) (($stats['en_curso'] ?? 0) + ($stats['inscrito'] ?? 0)) ?></div>
            <div class="text-xs text-slate-500">⏳ <?= (int) ($stats['en_curso'] ?? 0) ?> en curso · <?= (int) ($stats['inscrito'] ?? 0) ?> inscritos</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 rounded-4 shadow-sm p-3 bg-white h-100">
            <div class="text-uppercase text-xs fw-bold text-slate-400">Finalizados y Constancias</div>
            <div class="fs-3 fw-bold text-success mt-1"><?= (int) ($stats['finalizado'] ?? 0) ?></div>
            <div class="text-xs text-success fw-semibold">🏅 Certificados disponibles</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 rounded-4 shadow-sm p-3 bg-white h-100">
            <div class="text-uppercase text-xs fw-bold text-slate-400">Becas Institucionales</div>
            <div class="fs-3 fw-bold text-navy mt-1"><?= count($becas) ?></div>
            <div class="text-xs text-slate-500">🎓 TecVictoria y TECNM</div>
        </div>
    </div>
</div>

<!-- =====================================================================
     SECCIÓN 1: GESTIÓN DE BECAS E INICIATIVAS (TECVICTORIA Y TECNM)
     ===================================================================== -->
<div class="card border-0 rounded-4 shadow-sm p-4 bg-white mb-4" id="seccion-becas">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 pb-3 border-bottom">
        <div>
            <span class="badge bg-cyan text-navy fw-bold mb-1">Apartado de Becas · Mi Aprendizaje</span>
            <h1 class="h4 fw-bold mb-1">Gestión de Becas e Iniciativas Institucionales (TecVictoria y TECNM)</h1>
            <p class="text-slate-500 small mb-0">
                Administra los enlaces oficiales y convocatorias de becas que visualizan tanto los alumnos como los administradores en <strong>Mi Aprendizaje</strong>.
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="https://cdvictoria.tecnm.mx/alumnos/becas.html" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary fw-semibold">
                🎓 Ir a Becas TecVictoria ↗
            </a>
            <a href="https://www.tecnm.mx/?vista=Becas" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary fw-semibold">
                🏛️ Ir a Becas TECNM ↗
            </a>
            <button type="button" class="btn btn-sm btn-cyan fw-bold shadow-xs" data-bs-toggle="collapse" data-bs-target="#collapseNuevaBeca">
                ➕ Añadir Convocatoria / Beca
            </button>
        </div>
    </div>

    <!-- Formulario colapsable para añadir nueva beca o iniciativa -->
    <div class="collapse mb-4" id="collapseNuevaBeca">
        <form action="<?= url('/admin/aprendizaje/becas') ?>" method="POST" class="p-3 p-md-4 rounded-4 bg-slate-50 border border-slate-200">
            <?= csrf_field() ?>
            <h3 class="h6 fw-bold text-navy mb-3">➕ Registrar Nueva Beca o Iniciativa en Mi Aprendizaje</h3>
            <div class="row g-3">
                <div class="col-12 col-md-5">
                    <label class="form-label small fw-semibold">Título de la Beca / Convocatoria *</label>
                    <input type="text" name="titulo" class="form-control form-control-sm" required placeholder="Ej. Beca de Manutención y Excelencia Académica">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-semibold">Siglas *</label>
                    <input type="text" name="siglas" class="form-control form-control-sm" value="TecVictoria" required placeholder="TecVictoria / TECNM">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small fw-semibold">Institución *</label>
                    <input type="text" name="institucion" class="form-control form-control-sm" value="Instituto Tecnológico de Ciudad Victoria" required>
                </div>
                <div class="col-6 col-md-1">
                    <label class="form-label small fw-semibold">Ícono</label>
                    <input type="text" name="icono" class="form-control form-control-sm text-center" value="🎓">
                </div>
                <div class="col-6 col-md-1">
                    <label class="form-label small fw-semibold">Orden</label>
                    <input type="number" name="orden" class="form-control form-control-sm" value="3" min="1">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-semibold">URL Oficial de Destino *</label>
                    <input type="url" name="url" class="form-control form-control-sm" required placeholder="https://cdvictoria.tecnm.mx/alumnos/becas.html">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold">Estado de Convocatoria</label>
                    <input type="text" name="estado" class="form-control form-control-sm" value="Convocatoria activa">
                </div>
                <div class="col-12 col-md-3 d-flex align-items-end">
                    <div class="form-check form-switch mb-1">
                        <input type="hidden" name="activo" value="0">
                        <input class="form-check-input" type="checkbox" name="activo" value="1" id="nuevaBecaActivo" checked>
                        <label class="form-check-label small fw-semibold" for="nuevaBecaActivo">Visible en Mi Aprendizaje</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Descripción breve</label>
                    <input type="text" name="descripcion" class="form-control form-control-sm" placeholder="Resumen de los requisitos o beneficios de la beca...">
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-sm btn-cyan fw-bold px-4">Guardar Beca</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabla de Becas configuradas -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small">
                <tr>
                    <th>Ord.</th>
                    <th>Portal / Convocatoria de Beca</th>
                    <th>Institución</th>
                    <th>Enlace Oficial</th>
                    <th>Estado</th>
                    <th>Visibilidad</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody class="small">
                <?php foreach ($becas as $b): ?>
                    <tr>
                        <td class="fw-bold text-slate-400"><?= $b->orden ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="module-icon-box-sm bg-cyan-subtle text-navy"><?= e($b->icono) ?></span>
                                <div>
                                    <div class="fw-semibold text-navy"><?= e($b->titulo) ?></div>
                                    <div class="text-slate-400 text-xs text-truncate max-w-250"><?= e($b->descripcion) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-navy text-cyan"><?= e($b->siglas) ?></span>
                            <div class="text-xs text-slate-500 mt-1"><?= e($b->institucion) ?></div>
                        </td>
                        <td>
                            <a href="<?= e($b->url) ?>" target="_blank" rel="noopener" class="text-xs text-decoration-none fw-semibold">
                                <?= e($b->url) ?> ↗
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info"><?= e($b->estado) ?></span>
                        </td>
                        <td>
                            <span class="badge <?= $b->activo ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?>">
                                <?= $b->activo ? 'Visible' : 'Oculta' ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="<?= e($b->url) ?>" target="_blank" rel="noopener" class="btn btn-xs btn-outline-primary" title="Abrir página oficial">
                                    Ir al sitio ↗
                                </a>
                                <form action="<?= url('/admin/aprendizaje/becas/' . $b->id . '/estado') ?>" method="POST" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-xs <?= $b->activo ? 'btn-outline-warning' : 'btn-outline-success' ?>">
                                        <?= $b->activo ? 'Ocultar' : 'Activar' ?>
                                    </button>
                                </form>
                                <form action="<?= url('/admin/aprendizaje/becas/' . $b->id . '/eliminar') ?>" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar la beca «<?= e(addslashes($b->titulo)) ?>»?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-xs btn-outline-danger">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- =====================================================================
     SECCIÓN 2: CONTROL DE INSCRIPCIONES, AVANCES Y CONSTANCIAS
     ===================================================================== -->
<div class="card border-0 rounded-4 shadow-sm p-4 bg-white mb-4" id="seccion-inscripciones">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 pb-3 border-bottom">
        <div>
            <span class="badge bg-indigo-subtle text-indigo fw-bold mb-1">Seguimiento Académico</span>
            <h2 class="h4 fw-bold mb-1">Control de Inscripciones, Avances y Constancias de Usuarios</h2>
            <p class="text-slate-500 small mb-0">
                Inscribe alumnos o administradores a cualquier módulo interactivo, actualiza su porcentaje de avance o libera su certificado de finalización.
            </p>
        </div>
    </div>

    <!-- Formulario rápido para inscribir o asignar avance -->
    <form action="<?= url('/admin/aprendizaje/inscripciones') ?>" method="POST" class="p-3 rounded-4 bg-slate-50 border border-slate-200 mb-4">
        <?= csrf_field() ?>
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label small fw-semibold mb-1">Usuario (Estudiante / Jefe / Admin) *</label>
                <select name="id_usuario" class="form-select form-select-sm" required>
                    <option value="">Seleccionar usuario…</option>
                    <?php foreach ($usuarios as $u): ?>
                        <option value="<?= $u->id ?>">
                            #<?= $u->id ?> · <?= e($u->nombre) ?> (<?= e($u->email) ?> · <?= e($u->etiquetaRol()) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label small fw-semibold mb-1">Módulo Interactivo *</label>
                <select name="id_modulo" class="form-select form-select-sm" required>
                    <option value="">Seleccionar módulo…</option>
                    <?php foreach ($modulos as $m): ?>
                        <option value="<?= $m->id ?>"><?= e($m->icono . ' ' . $m->titulo) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold mb-1">Estado</label>
                <select name="estado" class="form-select form-select-sm">
                    <option value="inscrito">Inscrito</option>
                    <option value="en_curso" selected>En curso</option>
                    <option value="finalizado">Finalizado (Con Constancia)</option>
                </select>
            </div>
            <div class="col-6 col-md-1">
                <label class="form-label small fw-semibold mb-1">Avance %</label>
                <input type="number" name="avance" class="form-control form-control-sm" value="50" min="0" max="100">
            </div>
            <div class="col-12 col-md-2">
                <button type="submit" class="btn btn-sm btn-cyan fw-bold w-100">
                    ➕ Asignar / Actualizar
                </button>
            </div>
        </div>
    </form>

    <!-- Tabla de Inscripciones -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small">
                <tr>
                    <th>ID</th>
                    <th>Alumno / Usuario</th>
                    <th>Módulo Interactivo</th>
                    <th>Estado y Avance</th>
                    <th>Fecha Inscripción</th>
                    <th class="text-end">Acciones y Constancia</th>
                </tr>
            </thead>
            <tbody class="small">
                <?php foreach ($inscripciones as $ins): ?>
                    <?php
                        $idInsc = (int) $ins['id_inscripcion'];
                        $estadoActual = (string) $ins['estado'];
                        $avanceActual = (int) $ins['avance'];
                        $esFinalizado = ($estadoActual === 'finalizado');
                    ?>
                    <tr>
                        <td class="text-slate-400">#<?= $idInsc ?></td>
                        <td>
                            <div class="fw-semibold text-navy"><?= e((string) $ins['usuario_nombre']) ?></div>
                            <div class="text-xs text-slate-500"><?= e((string) $ins['usuario_email']) ?></div>
                        </td>
                        <td>
                            <a href="<?= url('/modulos/' . e((string) $ins['modulo_slug'])) ?>" class="text-decoration-none fw-semibold text-navy">
                                <?= e((string) $ins['modulo_icono'] . ' ' . (string) $ins['modulo_titulo']) ?>
                            </a>
                        </td>
                        <td>
                            <form action="<?= url('/admin/aprendizaje/inscripciones/' . $idInsc . '/avance') ?>" method="POST" class="d-flex flex-wrap align-items-center gap-2">
                                <?= csrf_field() ?>
                                <select name="estado" class="form-select form-select-sm py-0 px-2 text-xs" style="width: auto; height: 26px;">
                                    <option value="inscrito" <?= $estadoActual === 'inscrito' ? 'selected' : '' ?>>Inscrito</option>
                                    <option value="en_curso" <?= $estadoActual === 'en_curso' ? 'selected' : '' ?>>En curso</option>
                                    <option value="finalizado" <?= $estadoActual === 'finalizado' ? 'selected' : '' ?>>Finalizado</option>
                                </select>
                                <div class="input-group input-group-sm" style="width: 85px;">
                                    <input type="number" name="avance" class="form-control py-0 px-1 text-center text-xs" style="height: 26px;"
                                           value="<?= $avanceActual ?>" min="0" max="100">
                                    <span class="input-group-text py-0 px-1 text-xs" style="height: 26px;">%</span>
                                </div>
                                <button type="submit" class="btn btn-xs btn-outline-primary">Guardar</button>
                            </form>
                        </td>
                        <td class="text-xs text-slate-500"><?= fecha((string) $ins['inscrito_en']) ?></td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <?php if ($esFinalizado): ?>
                                    <a href="<?= url('/mi-aprendizaje/' . $idInsc . '/constancia') ?>" target="_blank" class="btn btn-xs btn-outline-success fw-semibold">
                                        📜 Constancia
                                    </a>
                                <?php endif; ?>
                                <form action="<?= url('/admin/aprendizaje/inscripciones/' . $idInsc . '/eliminar') ?>" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Dar de baja esta inscripción?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-xs btn-outline-danger" title="Dar de baja">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- =====================================================================
     SECCIÓN 3: CONFIGURACIÓN PEDAGÓGICA (ETAPAS ABP Y EVALUACIONES)
     ===================================================================== -->
<div class="row g-4" id="seccion-pedagogia">
    <!-- Etapas de la Metodología ABP -->
    <div class="col-12 col-lg-6">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
            <h2 class="h5 fw-bold mb-1">🔄 Etapas de la Metodología ABP</h2>
            <p class="text-slate-500 small mb-3">Edita los pasos metodológicos que se muestran en la pestaña pública de Metodología.</p>

            <div class="d-flex flex-column gap-3">
                <?php foreach ($etapasAbp as $etapa): ?>
                    <form action="<?= url('/admin/aprendizaje/etapas/' . $etapa->id) ?>" method="POST" class="p-3 rounded-3 bg-slate-50 border">
                        <?= csrf_field() ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-navy text-cyan">Paso #<?= $etapa->orden ?></span>
                            <button type="submit" class="btn btn-xs btn-cyan fw-bold">Actualizar Etapa</button>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="nombre" class="form-control form-control-sm fw-bold" value="<?= e($etapa->nombre) ?>" required>
                        </div>
                        <div>
                            <input type="text" name="descripcion" class="form-control form-control-sm" value="<?= e($etapa->descripcion) ?>" required>
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Tipos de Evaluación -->
    <div class="col-12 col-lg-6">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
            <h2 class="h5 fw-bold mb-1">📊 Criterios y Tipos de Evaluación</h2>
            <p class="text-slate-500 small mb-3">Ajusta los títulos y descripciones de las evaluaciones diagnóstica, formativa y de desempeño.</p>

            <div class="d-flex flex-column gap-3">
                <?php foreach ($evaluaciones as $eval): ?>
                    <form action="<?= url('/admin/aprendizaje/evaluaciones/' . $eval->id) ?>" method="POST" class="p-3 rounded-3 bg-slate-50 border">
                        <?= csrf_field() ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-indigo-subtle text-indigo text-uppercase"><?= e($eval->tipo) ?></span>
                            <button type="submit" class="btn btn-xs btn-cyan fw-bold">Actualizar Evaluación</button>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-2">
                                <input type="text" name="icono" class="form-control form-control-sm text-center" value="<?= e($eval->icono) ?>">
                            </div>
                            <div class="col-6">
                                <input type="text" name="titulo" class="form-control form-control-sm fw-bold" value="<?= e($eval->titulo) ?>" required>
                            </div>
                            <div class="col-4">
                                <input type="text" name="etapa" class="form-control form-control-sm" value="<?= e($eval->etapa) ?>" required>
                            </div>
                        </div>
                        <div>
                            <input type="text" name="descripcion" class="form-control form-control-sm" value="<?= e($eval->descripcion) ?>" required>
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>


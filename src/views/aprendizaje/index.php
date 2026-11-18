<section class="learning-section">
    <div class="container-fluid max-w-7xl mx-auto px-3 px-md-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h1 class="learning-title mb-1">Mi aprendizaje</h1>
                <p class="text-muted small mb-0">
                    Gestiona tus módulos interactivos inscritos, descarga tus constancias y accede al apartado oficial de <strong>Becas TecVictoria y TECNM</strong>.
                </p>
            </div>
            <?php if (!empty($esAdmin)): ?>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= url('/admin/aprendizaje') ?>" class="btn btn-sm btn-outline-primary fw-semibold">
                        ⚙️ Gestionar Aprendizaje y Becas en Panel Admin
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- APARTADO DESTACADO DE BECAS INSTITUCIONALES (TECVICTORIA Y TECNM) -->
        <div class="card border-0 rounded-4 shadow-xs p-3 p-md-4 bg-white mb-4 border-start border-4 border-info" id="apartadoBecasInstitucionales">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="fs-4">🎓</span>
                    <div>
                        <h2 class="h6 fw-bold text-navy mb-0">Apartado de Becas e Iniciativas Oficiales (TecVictoria y TECNM)</h2>
                        <p class="text-muted text-xs mb-0">Accede directamente a las convocatorias vigentes tanto para alumnos como para personal administrativo.</p>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="https://cdvictoria.tecnm.mx/alumnos/becas.html" target="_blank" rel="noopener noreferrer"
                       class="btn btn-sm btn-cyan fw-bold px-3 shadow-xs">
                        🎓 Becas TecVictoria ↗
                    </a>
                    <a href="https://www.tecnm.mx/?vista=Becas" target="_blank" rel="noopener noreferrer"
                       class="btn btn-sm btn-navy bg-navy text-white fw-bold px-3">
                        🏛️ Becas TECNM ↗
                    </a>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="p-3 rounded-3 bg-slate-50 border border-slate-200 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <span class="badge bg-cyan text-navy fw-bold">TecVictoria · ITCV</span>
                                <span class="badge bg-success-subtle text-success">Portal Oficial</span>
                            </div>
                            <h3 class="h6 fw-bold text-navy mb-1">Becas Instituto Tecnológico de Ciudad Victoria</h3>
                            <p class="text-muted text-xs mb-2">
                                Consulta convocatorias internas, becas alimenticias, descuentos e iniciativas estudiantiles del campus Cd. Victoria.
                            </p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <code class="text-xs text-truncate me-2">https://cdvictoria.tecnm.mx/alumnos/becas.html</code>
                            <a href="https://cdvictoria.tecnm.mx/alumnos/becas.html" target="_blank" rel="noopener noreferrer"
                               class="btn btn-xs btn-outline-primary fw-bold text-nowrap">
                                Ir a TecVictoria ↗
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="p-3 rounded-3 bg-slate-50 border border-slate-200 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <span class="badge bg-navy text-cyan fw-bold">TECNM · Nacional</span>
                                <span class="badge bg-success-subtle text-success">Portal Oficial</span>
                            </div>
                            <h3 class="h6 fw-bold text-navy mb-1">Becas Tecnológico Nacional de México (TECNM)</h3>
                            <p class="text-muted text-xs mb-2">
                                Explora programas nacionales de becas académicas, movilidad, servicio social, titulación e investigación del TECNM.
                            </p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <code class="text-xs text-truncate me-2">https://www.tecnm.mx/?vista=Becas</code>
                            <a href="https://www.tecnm.mx/?vista=Becas" target="_blank" rel="noopener noreferrer"
                               class="btn btn-xs btn-outline-primary fw-bold text-nowrap">
                                Ir a TECNM ↗
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros superiores estilo Santander Open Academy -->
        <div class="learning-filter-bar">
            <div>
                <label for="filterTipoContenido" class="visually-hidden">Tipo de contenido</label>
                <select id="filterTipoContenido" class="form-select learning-select">
                    <option value="">Tipo de contenido (Todos)</option>
                    <option value="curso">Cursos y módulos</option>
                    <option value="beca">Becas e iniciativas (TecVictoria / TECNM)</option>
                    <option value="directo">Curso acceso directo</option>
                </select>
            </div>

            <div>
                <label for="filterEstado" class="visually-hidden">Estado</label>
                <select id="filterEstado" class="form-select learning-select">
                    <option value="">Estado</option>
                    <option value="inscrito">Inscrito</option>
                    <option value="en_curso">En curso</option>
                    <option value="finalizado">Finalizado</option>
                </select>
            </div>

            <div class="ms-md-auto">
                <div class="input-group" style="max-width: 280px;">
                    <span class="input-group-text bg-transparent border-end-0 text-muted"><?= icon('search', 'icon-sm') ?></span>
                    <input type="text" id="filterBusqueda" class="form-control border-start-0 ps-0" placeholder="Filtrar por nombre…" aria-label="Filtrar aprendizaje">
                </div>
            </div>
        </div>

        <!-- Tabla interactiva con cursos inscritos y becas oficiales -->
        <div class="table-responsive bg-white rounded-3 p-1 p-md-2 shadow-xs mb-5 border border-slate-100">
            <table class="table table-learning align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col" style="width: 20%;">Tipo de contenido</th>
                        <th scope="col" style="width: 35%;">Nombre</th>
                        <th scope="col" style="width: 15%;">Estado</th>
                        <th scope="col" style="width: 22%;">Acciones pendientes</th>
                        <th scope="col" style="width: 8%;" class="text-end pe-4"><span class="visually-hidden">Opciones</span></th>
                    </tr>
                </thead>
                <tbody id="learningTableBody">
                    <?php foreach ($inscripciones as $i): ?>
                        <?php
                            $esBeca = str_contains(mb_strtolower($i->titulo), 'beca') || str_contains(mb_strtolower($i->titulo), 'manutenci');
                            $tipoEtiqueta = $esBeca ? 'Beca' : 'Curso acceso directo';

                            $proveedor = match(true) {
                                str_contains(mb_strtolower($i->titulo), 'programaci') => 'Depto. Sistemas y Computación · IT Victoria',
                                str_contains(mb_strtolower($i->titulo), 'cálculo') || str_contains(mb_strtolower($i->titulo), 'calculo') => 'Depto. Ciencias Básicas · IT Victoria',
                                str_contains(mb_strtolower($i->titulo), 'discretas') => 'Depto. Sistemas y Computación · IT Victoria',
                                str_contains(mb_strtolower($i->titulo), 'física') || str_contains(mb_strtolower($i->titulo), 'fisica') => 'Depto. Ciencias Básicas · IT Victoria',
                                default => 'Instituto Tecnológico de Ciudad Victoria'
                            };
                        ?>
                        <tr data-learning-row
                            data-tipo="<?= e(mb_strtolower('curso ' . $tipoEtiqueta . ' ' . $i->titulo)) ?>"
                            data-estado="<?= e($i->estado->value) ?>">
                            <td class="text-secondary fw-normal">
                                <?= e($tipoEtiqueta) ?>
                            </td>
                            <td>
                                <div>
                                    <a href="<?= url('/modulos/' . $i->slug) ?>" class="module-link-title">
                                        <?= e($i->titulo) ?>
                                    </a>
                                    <div class="module-provider-subtitle">
                                        <?= e($proveedor) ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-status-outline status-<?= e($i->estado->value) ?>">
                                    <?= e(strtoupper($i->estado->etiqueta())) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($i->finalizado()): ?>
                                    <a href="<?= url('/mi-aprendizaje/' . $i->id . '/constancia') ?>" class="action-link-certificate" target="_blank" rel="noopener">
                                        Ver certificado
                                    </a>
                                <?php elseif ($i->estado->value === 'en_curso'): ?>
                                    <a href="<?= url('/modulos/' . $i->slug) ?>" class="btn btn-sm btn-outline-primary rounded-pill py-1 px-3">
                                        Continuar módulo ▷
                                    </a>
                                <?php else: ?>
                                    <span class="text-secondary small">No hay acciones pendientes</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-3">
                                <div class="dropdown">
                                    <button class="btn-dot-menu" type="button" id="drop-<?= $i->id ?>" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Opciones para <?= e($i->titulo) ?>">
                                        <?= icon('dots') ?>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2" aria-labelledby="drop-<?= $i->id ?>">
                                        <li>
                                            <a class="dropdown-item py-2 small" href="<?= url('/modulos/' . $i->slug) ?>">
                                                Ver contenido del módulo
                                            </a>
                                        </li>
                                        <?php if ($i->finalizado()): ?>
                                            <li>
                                                <a class="dropdown-item py-2 small text-primary" href="<?= url('/mi-aprendizaje/' . $i->id . '/constancia') ?>" target="_blank" rel="noopener">
                                                    Descargar constancia
                                                </a>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <form action="<?= url('/modulos/' . $i->slug . '/avance') ?>" method="POST" class="m-0">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="accion" value="finalizar">
                                                    <input type="hidden" name="volver" value="aprendizaje">
                                                    <button type="submit" class="dropdown-item py-2 small text-success">
                                                        Marcar como finalizado
                                                    </button>
                                                </form>
                                            </li>
                                        <?php endif; ?>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <form action="<?= url('/mi-aprendizaje/' . $i->id . '/baja') ?>" method="POST" class="m-0" onsubmit="return confirm('¿Seguro que deseas darte de baja de este módulo?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="dropdown-item py-2 small text-danger">
                                                    Darse de baja
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <!-- Filas del apartado de Becas e iniciativas (TecVictoria y TECNM) -->
                    <?php foreach (($becas ?? []) as $b): ?>
                        <tr data-learning-row
                            data-tipo="<?= e(mb_strtolower('beca becas e iniciativas ' . $b->siglas . ' ' . $b->titulo . ' ' . $b->institucion)) ?>"
                            data-estado="disponible">
                            <td class="text-secondary fw-normal">
                                <span class="badge bg-cyan-subtle text-navy fw-semibold">
                                    <?= e($b->icono) ?> Beca · <?= e($b->siglas) ?>
                                </span>
                            </td>
                            <td>
                                <div>
                                    <a href="<?= e($b->url) ?>" target="_blank" rel="noopener noreferrer" class="module-link-title">
                                        <?= e($b->titulo) ?> ↗
                                    </a>
                                    <div class="module-provider-subtitle">
                                        <?= e($b->institucion) ?> · <span class="text-muted"><?= e($b->descripcion) ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-status-outline status-en_curso">
                                    <?= e(strtoupper($b->estado)) ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= e($b->url) ?>" target="_blank" rel="noopener noreferrer"
                                   class="btn btn-sm btn-cyan fw-bold rounded-pill py-1 px-3">
                                    Ir a <?= e($b->siglas) ?> ↗
                                </a>
                            </td>
                            <td class="text-end pe-3">
                                <div class="dropdown">
                                    <button class="btn-dot-menu" type="button" id="drop-beca-<?= $b->id ?>" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Opciones para <?= e($b->titulo) ?>">
                                        <?= icon('dots') ?>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2" aria-labelledby="drop-beca-<?= $b->id ?>">
                                        <li>
                                            <a class="dropdown-item py-2 small fw-semibold text-primary" href="<?= e($b->url) ?>" target="_blank" rel="noopener noreferrer">
                                                🌐 Abrir página web de <?= e($b->siglas) ?> ↗
                                            </a>
                                        </li>
                                        <?php if (!empty($esAdmin)): ?>
                                            <li>
                                                <a class="dropdown-item py-2 small" href="<?= url('/admin/aprendizaje#seccion-becas') ?>">
                                                    ⚙️ Configurar en Panel Admin
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <tr id="learningNoResults" style="display: none;">
                        <td colspan="5" class="text-center py-4 text-muted small">
                            No se encontraron cursos o becas que coincidan con los filtros seleccionados.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <?php if (!empty($recomendados)): ?>
            <!-- Sección de sugerencias para ampliar el aprendizaje -->
            <div class="mt-5 pt-3 border-top border-slate-200">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="h5 fw-bold mb-1">Continúa ampliando tus competencias</h2>
                        <p class="text-muted small mb-0">Módulos interactivos recomendados para ti</p>
                    </div>
                    <a href="<?= url('/modulos') ?>" class="small fw-bold text-decoration-none text-cyan">Explorar todos ↗</a>
                </div>
                <div class="row g-4">
                    <?php foreach ($recomendados as $r): ?>
                        <div class="col-12 col-md-4">
                            <div class="card h-100 border-0 rounded-4 shadow-xs p-4 bg-white hover-card d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="fs-4"><?= e($r->icono) ?></span>
                                    <span class="badge bg-slate-100 text-slate-700 rounded-pill"><?= e($r->nivel->etiqueta()) ?></span>
                                </div>
                                <h3 class="h6 fw-bold mb-2"><?= e($r->titulo) ?></h3>
                                <p class="text-muted small mb-4 flex-grow-1"><?= e($r->descripcion) ?></p>
                                <form action="<?= url('/modulos/' . $r->slug . '/inscripcion') ?>" method="POST" class="m-0">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-cyan w-100 fw-bold">Inscribirme</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

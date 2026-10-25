<section class="learning-section">
    <div class="container-fluid max-w-7xl mx-auto px-3 px-md-4">
        <h1 class="learning-title">Mi aprendizaje</h1>

        <!-- Filtros superiores estilo Santander Open Academy -->
        <div class="learning-filter-bar">
            <div>
                <label for="filterTipoContenido" class="visually-hidden">Tipo de contenido</label>
                <select id="filterTipoContenido" class="form-select learning-select">
                    <option value="">Tipo de contenido</option>
                    <option value="curso">Cursos y módulos</option>
                    <option value="beca">Becas e iniciativas</option>
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

        <!-- Tabla interactiva con cabecera beige/crema como en la imagen -->
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
                    <?php if (empty($inscripciones)): ?>
                        <tr id="learningEmptyRow">
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted mb-3 fs-3">📚</div>
                                <h2 class="h5 fw-bold mb-2">Aún no tienes módulos en tu aprendizaje</h2>
                                <p class="text-muted small mb-3">Explora el catálogo para comenzar a desarrollar tu razonamiento lógico.</p>
                                <a href="<?= url('/modulos') ?>" class="btn btn-cyan btn-sm px-4 fw-bold">Explorar módulos</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($inscripciones as $i): ?>
                            <?php
                                // Determinar etiqueta de tipo de contenido acorde al título/tipo
                                $esBeca = str_contains(mb_strtolower($i->titulo), 'beca') || str_contains(mb_strtolower($i->titulo), 'manutenci');
                                $tipoEtiqueta = $esBeca ? 'Beca' : 'Curso acceso directo';

                                // Proveedor o departamento académico asociado en Tec Victoria
                                $proveedor = match(true) {
                                    str_contains(mb_strtolower($i->titulo), 'programaci') => 'Depto. Sistemas y Computación · IT Victoria',
                                    str_contains(mb_strtolower($i->titulo), 'cálculo') || str_contains(mb_strtolower($i->titulo), 'calculo') => 'Depto. Ciencias Básicas · IT Victoria',
                                    str_contains(mb_strtolower($i->titulo), 'discretas') => 'Depto. Sistemas y Computación · IT Victoria',
                                    str_contains(mb_strtolower($i->titulo), 'física') || str_contains(mb_strtolower($i->titulo), 'fisica') => 'Depto. Ciencias Básicas · IT Victoria',
                                    default => 'Instituto Tecnológico de Ciudad Victoria'
                                };
                            ?>
                            <tr data-learning-row
                                data-tipo="<?= e(mb_strtolower($tipoEtiqueta . ' ' . $i->titulo)) ?>"
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
                    <?php endif; ?>
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

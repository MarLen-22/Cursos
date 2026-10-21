<div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Catálogo de Módulos Interactivos</h1>
            <p class="text-slate-500 small mb-0">Administra los simuladores y retos ABP disponibles para los estudiantes.</p>
        </div>
        <a href="<?= url('/admin/modulos/nuevo') ?>" class="btn btn-cyan fw-bold d-flex align-items-center gap-1 shadow-cyan">
            <span>➕</span>
            <span>Nuevo Módulo</span>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small">
                <tr>
                    <th style="width: 50px;">Ord.</th>
                    <th>Módulo</th>
                    <th>Identificador (Slug)</th>
                    <th>Nivel</th>
                    <th>Color</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody class="small">
                <?php foreach ($modulos as $m): ?>
                    <tr>
                        <td class="fw-bold text-slate-400"><?= $m->orden ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="module-icon-box-sm bg-<?= e($m->color->value) ?>-subtle text-<?= e($m->color->value) ?>">
                                    <?= e($m->icono) ?>
                                </span>
                                <div>
                                    <div class="fw-semibold text-navy"><?= e($m->titulo) ?></div>
                                    <div class="text-slate-400 text-xs text-truncate max-w-250"><?= e($m->descripcion) ?></div>
                                </div>
                            </div>
                        </td>
                        <td><code class="text-xs"><?= e($m->slug) ?></code></td>
                        <td><span class="badge bg-slate-100 text-slate-700"><?= e($m->nivel->etiqueta()) ?></span></td>
                        <td>
                            <span class="badge bg-<?= e($m->color->value) ?>-subtle text-<?= e($m->color->value) ?>">
                                <?= e($m->color->etiqueta()) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= $m->activo ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?>">
                                <?= $m->activo ? 'Visible' : 'Oculto' ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="<?= url('/modulos/' . e($m->slug)) ?>" target="_blank" class="btn btn-xs btn-outline-secondary" title="Ver en el sitio">
                                    👁️
                                </a>
                                <a href="<?= url('/admin/modulos/' . $m->id . '/editar') ?>" class="btn btn-xs btn-outline-primary">
                                    Editar
                                </a>
                                <form action="<?= url('/admin/modulos/' . $m->id . '/estado') ?>" method="POST" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-xs <?= $m->activo ? 'btn-outline-warning' : 'btn-outline-success' ?>">
                                        <?= $m->activo ? 'Ocultar' : 'Publicar' ?>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$esEdicion = ($modulo !== null);
$action = $esEdicion ? url('/admin/modulos/' . $modulo->id) : url('/admin/modulos');

$val = function(string $campo, string $default = '') use ($modulo, $esEdicion): string {
    if (has_old()) {
        return old($campo, $default);
    }
    if ($esEdicion && $modulo !== null) {
        return match ($campo) {
            'titulo'      => $modulo->titulo,
            'slug'        => $modulo->slug,
            'descripcion' => $modulo->descripcion,
            'contenido'   => (string) $modulo->contenido,
            'icono'       => $modulo->icono,
            'color'       => $modulo->color->value,
            'nivel'       => $modulo->nivel->value,
            'orden'       => (string) $modulo->orden,
            'activo'      => $modulo->activo ? '1' : '0',
            default       => $default,
        };
    }
    return $default;
};
?>
<div class="card border-0 rounded-4 shadow-sm p-4 bg-white max-w-4xl mx-auto">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h1 class="h4 fw-bold mb-1"><?= $esEdicion ? 'Editar Módulo Interactivo' : 'Nuevo Módulo Interactivo' ?></h1>
            <p class="text-slate-500 small mb-0">Configura la información pedagógica y visual del simulador ABP.</p>
        </div>
        <a href="<?= url('/admin/modulos') ?>" class="btn btn-outline-secondary btn-sm">← Volver al listado</a>
    </div>

    <form action="<?= $action ?>" method="POST" novalidate>
        <?= csrf_field() ?>

        <div class="row g-3 mb-3">
            <div class="col-12 col-md-8">
                <label for="titulo" class="form-label small fw-semibold">Título del Módulo *</label>
                <input type="text" class="form-control<?= invalid_class('titulo') ?>" id="titulo" name="titulo"
                       value="<?= e($val('titulo')) ?>" required placeholder="Ej. Fundamentos de Programación">
                <?php if ($err = field_error('titulo')): ?>
                    <div class="invalid-feedback"><?= e($err) ?></div>
                <?php endif; ?>
            </div>

            <div class="col-12 col-md-4">
                <label for="slug" class="form-label small fw-semibold">Identificador URL (Slug) *</label>
                <input type="text" class="form-control<?= invalid_class('slug') ?>" id="slug" name="slug"
                       value="<?= e($val('slug')) ?>" required placeholder="ej-fundamentos-programacion">
                <?php if ($err = field_error('slug')): ?>
                    <div class="invalid-feedback"><?= e($err) ?></div>
                <?php endif; ?>
                <div class="form-text small text-xs">Solo letras minúsculas, números y guiones.</div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-12 col-md-3">
                <label for="icono" class="form-label small fw-semibold">Ícono (Emoji o Texto) *</label>
                <input type="text" class="form-control<?= invalid_class('icono') ?>" id="icono" name="icono"
                       value="<?= e($val('icono', '</>')) ?>" required placeholder="Ej. </> o 📈">
                <?php if ($err = field_error('icono')): ?>
                    <div class="invalid-feedback"><?= e($err) ?></div>
                <?php endif; ?>
            </div>

            <div class="col-12 col-md-3">
                <label for="color" class="form-label small fw-semibold">Color Temático *</label>
                <select class="form-select<?= invalid_class('color') ?>" id="color" name="color">
                    <option value="cyan" <?= $val('color') === 'cyan' ? 'selected' : '' ?>>Cian (#22d3ee)</option>
                    <option value="indigo" <?= $val('color') === 'indigo' ? 'selected' : '' ?>>Índigo (#818cf8)</option>
                    <option value="purple" <?= $val('color') === 'purple' ? 'selected' : '' ?>>Púrpura (#c084fc)</option>
                    <option value="sky" <?= $val('color') === 'sky' ? 'selected' : '' ?>>Cielo (#38bdf8)</option>
                </select>
                <?php if ($err = field_error('color')): ?>
                    <div class="invalid-feedback"><?= e($err) ?></div>
                <?php endif; ?>
            </div>

            <div class="col-12 col-md-3">
                <label for="nivel" class="form-label small fw-semibold">Nivel *</label>
                <select class="form-select<?= invalid_class('nivel') ?>" id="nivel" name="nivel">
                    <option value="inicial" <?= $val('nivel', 'inicial') === 'inicial' ? 'selected' : '' ?>>Inicial</option>
                    <option value="intermedio" <?= $val('nivel') === 'intermedio' ? 'selected' : '' ?>>Intermedio</option>
                    <option value="avanzado" <?= $val('nivel') === 'avanzado' ? 'selected' : '' ?>>Avanzado</option>
                </select>
                <?php if ($err = field_error('nivel')): ?>
                    <div class="invalid-feedback"><?= e($err) ?></div>
                <?php endif; ?>
            </div>

            <div class="col-12 col-md-3">
                <label for="orden" class="form-label small fw-semibold">Orden de Visualización *</label>
                <input type="number" class="form-control<?= invalid_class('orden') ?>" id="orden" name="orden"
                       value="<?= e($val('orden', '1')) ?>" min="0" max="999" required>
                <?php if ($err = field_error('orden')): ?>
                    <div class="invalid-feedback"><?= e($err) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label small fw-semibold">Descripción Breve (para tarjetas) *</label>
            <textarea class="form-control<?= invalid_class('descripcion') ?>" id="descripcion" name="descripcion"
                      rows="2" required placeholder="Resumen conciso del objetivo del módulo..."><?= e($val('descripcion')) ?></textarea>
            <?php if ($err = field_error('descripcion')): ?>
                <div class="invalid-feedback"><?= e($err) ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="contenido" class="form-label small fw-semibold">Contenido y Guía Metodológica ABP</label>
            <textarea class="form-control<?= invalid_class('contenido') ?>" id="contenido" name="contenido"
                      rows="6" placeholder="Detalles pedagógicos, instrucciones de simulación y planteamiento del reto..."><?= e($val('contenido')) ?></textarea>
            <?php if ($err = field_error('contenido')): ?>
                <div class="invalid-feedback"><?= e($err) ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-4 form-check form-switch">
            <input type="hidden" name="activo" value="0">
            <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1"
                   <?= $val('activo', '1') === '1' ? 'checked' : '' ?>>
            <label class="form-check-label small fw-semibold" for="activo">Módulo visible para estudiantes en el catálogo público</label>
        </div>

        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="<?= url('/admin/modulos') ?>" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-cyan fw-bold px-4 shadow-cyan">
                <?= $esEdicion ? 'Guardar Cambios' : 'Crear Módulo' ?>
            </button>
        </div>
    </form>
</div>

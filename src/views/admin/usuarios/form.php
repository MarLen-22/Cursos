<?php
use App\Models\Catalogo;
use App\Models\Genero;
use App\Models\Modalidad;
use App\Models\NivelEstudios;

$esEdicion = ($usuarioEditar !== null);
$action = $esEdicion ? url('/admin/usuarios/' . $usuarioEditar->id) : url('/admin/usuarios');

$rolInicial = 'estudiante';
if ($esEdicion && $adminEditar !== null) {
    $rolInicial = $adminEditar->nivel->value;
}

$val = function (string $campo, string $default = '') use ($esEdicion, $usuarioEditar, $adminEditar, $perfil, $rolInicial): string {
    if (has_old()) {
        return old($campo, $default);
    }
    if ($esEdicion && $usuarioEditar !== null) {
        return match ($campo) {
            'nombre'       => $usuarioEditar->nombre,
            'email'        => $usuarioEditar->email,
            'activo'       => $usuarioEditar->activo ? '1' : '0',
            'rol'          => $rolInicial,
            'cargo'        => (string) ($adminEditar?->cargo ?? ''),
            'departamento' => (string) ($adminEditar?->departamento ?? ''),
            default        => (string) ($perfil->get($campo) ?? $default),
        };
    }
    return $default;
};

$rolSeleccionado = $val('rol', 'estudiante');
?>

<div class="card border-0 rounded-4 shadow-sm p-4 bg-white max-w-7xl mx-auto mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 pb-3 border-bottom">
        <div>
            <span class="badge bg-cyan text-navy fw-bold mb-1">
                <?= $esEdicion ? 'Edición Integral de Cuenta y Rol' : 'Registro Administrativo de Usuario' ?>
            </span>
            <h1 class="h4 fw-bold mb-1">
                <?= $esEdicion ? 'Editar Usuario: ' . e($usuarioEditar->nombre) : 'Añadir Nuevo Estudiante, Jefe de Curso o Administrador' ?>
            </h1>
            <p class="text-slate-500 small mb-0">
                Define el correo electrónico, contraseña, rol en la plataforma (Estudiante, Jefe de Curso, Administrador o Superadministrador), datos personales, académicos y cursos asignados.
            </p>
        </div>
        <a href="<?= url('/admin/usuarios') ?>" class="btn btn-outline-secondary btn-sm">
            ← Volver a Usuarios y Permisos
        </a>
    </div>

    <form action="<?= $action ?>" method="POST" id="formAdminUsuario" novalidate>
        <?= csrf_field() ?>

        <!-- SECCIÓN 1: ROL EN LA PÁGINA (RBAC) -->
        <div class="p-3 p-md-4 rounded-4 bg-slate-50 border border-slate-200 mb-4">
            <h2 class="h6 fw-bold text-navy text-uppercase mb-3 d-flex align-items-center gap-2">
                <span>🛡️</span>
                <span>1. Selecciona el Rol y Nivel de Privilegios en la Plataforma *</span>
            </h2>

            <div class="row g-3">
                <!-- Estudiante -->
                <div class="col-12 col-md-6 col-xl-3">
                    <label class="card h-100 p-3 border rounded-3 cursor-pointer role-option-card <?= $rolSeleccionado === 'estudiante' ? 'border-primary bg-white shadow-xs' : 'bg-white' ?>">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <input class="form-check-input mt-0" type="radio" name="rol" value="estudiante"
                                   <?= $rolSeleccionado === 'estudiante' ? 'checked' : '' ?>>
                            <span class="fw-bold text-navy">🎓 Estudiante</span>
                        </div>
                        <p class="text-slate-500 text-xs mb-0">
                            Acceso a módulos interactivos, simuladores ABP, seguimiento de avance, becas y descarga de constancias.
                        </p>
                    </label>
                </div>

                <!-- Jefe de Curso -->
                <div class="col-12 col-md-6 col-xl-3">
                    <label class="card h-100 p-3 border rounded-3 cursor-pointer role-option-card <?= $rolSeleccionado === 'jefe_curso' ? 'border-primary bg-white shadow-xs' : 'bg-white' ?>">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <input class="form-check-input mt-0" type="radio" name="rol" value="jefe_curso"
                                   <?= $rolSeleccionado === 'jefe_curso' ? 'checked' : '' ?>>
                            <span class="fw-bold text-indigo">👨‍🏫 Jefe de Curso</span>
                        </div>
                        <p class="text-slate-500 text-xs mb-0">
                            Responsable académico y docente. Puede gestionar módulos interactivos, seguimiento de alumnos y metodología ABP.
                        </p>
                    </label>
                </div>

                <!-- Administrador -->
                <div class="col-12 col-md-6 col-xl-3">
                    <label class="card h-100 p-3 border rounded-3 cursor-pointer role-option-card <?= $rolSeleccionado === 'admin' ? 'border-primary bg-white shadow-xs' : 'bg-white' ?>">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <input class="form-check-input mt-0" type="radio" name="rol" value="admin"
                                   <?= $rolSeleccionado === 'admin' ? 'checked' : '' ?>>
                            <span class="fw-bold text-navy">🛡️ Administrador</span>
                        </div>
                        <p class="text-slate-500 text-xs mb-0">
                            Acceso completo al panel administrativo: alta/edición de usuarios, CRUD de módulos, inscripciones y becas.
                        </p>
                    </label>
                </div>

                <!-- Superadministrador -->
                <div class="col-12 col-md-6 col-xl-3">
                    <label class="card h-100 p-3 border rounded-3 cursor-pointer role-option-card <?= $rolSeleccionado === 'superadmin' ? 'border-primary bg-white shadow-xs' : 'bg-white' ?>">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <input class="form-check-input mt-0" type="radio" name="rol" value="superadmin"
                                   <?= $rolSeleccionado === 'superadmin' ? 'checked' : '' ?>
                                   <?= !$esSuperAdmin && $rolSeleccionado !== 'superadmin' ? 'disabled' : '' ?>>
                            <span class="fw-bold text-cyan bg-navy px-2 py-0 rounded">👑 Superadministrador</span>
                        </div>
                        <p class="text-slate-500 text-xs mb-0">
                            Máxima autoridad del sistema (mismos privilegios que <code>admin@logicavisual.edu.mx</code>). Control total RBAC.
                        </p>
                    </label>
                </div>
            </div>
            <?php if ($err = field_error('rol')): ?>
                <div class="text-danger small mt-2"><?= e($err) ?></div>
            <?php endif; ?>

            <!-- Parámetros administrativos adicionales para Jefe de Curso / Admin / Superadmin -->
            <div class="row g-3 mt-2 pt-3 border-top border-slate-200">
                <div class="col-12 col-md-6">
                    <label for="cargo" class="form-label small fw-semibold">Cargo / Función en la Plataforma</label>
                    <input type="text" class="form-control<?= invalid_class('cargo') ?>" id="cargo" name="cargo"
                           value="<?= e($val('cargo')) ?>"
                           placeholder="Ej. Jefe de Curso de Ciencias Básicas, Coordinador ABP o Estudiante Regular">
                    <?php if ($err = field_error('cargo')): ?>
                        <div class="invalid-feedback"><?= e($err) ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-12 col-md-6">
                    <label for="departamento" class="form-label small fw-semibold">Departamento / Coordinación Académica</label>
                    <input type="text" class="form-control<?= invalid_class('departamento') ?>" id="departamento" name="departamento"
                           value="<?= e($val('departamento')) ?>"
                           placeholder="Ej. Depto. de Sistemas y Computación · TecVictoria">
                    <?php if ($err = field_error('departamento')): ?>
                        <div class="invalid-feedback"><?= e($err) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 2: CREDENCIALES DE ACCESO Y CORREO ELECTRÓNICO -->
        <div class="p-3 p-md-4 rounded-4 bg-white border border-slate-200 mb-4">
            <h2 class="h6 fw-bold text-navy text-uppercase mb-3 d-flex align-items-center gap-2">
                <span>🔐</span>
                <span>2. Credenciales de Acceso (Correo Electrónico y Contraseña)</span>
            </h2>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label for="nombre" class="form-label small fw-semibold">Nombre Completo *</label>
                    <input type="text" class="form-control<?= invalid_class('nombre') ?>" id="nombre" name="nombre"
                           value="<?= e($val('nombre')) ?>" required
                           placeholder="Ej. Marlene Jaquelin Escobedo Mejia">
                    <?php if ($err = field_error('nombre')): ?>
                        <div class="invalid-feedback"><?= e($err) ?></div>
                    <?php endif; ?>
                    <div class="form-text text-xs">Nombre y apellidos completos (solo letras y espacios).</div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="email" class="form-label small fw-semibold mb-1">Correo Electrónico de Acceso *</label>
                        <div class="d-flex gap-1 mb-1">
                            <button type="button" class="btn btn-xs btn-outline-secondary" data-fill-domain="@tecvictoria.edu.mx">
                                + @tecvictoria.edu.mx
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-secondary" data-fill-domain="@logicavisual.edu.mx">
                                + @logicavisual.edu.mx
                            </button>
                        </div>
                    </div>
                    <input type="email" class="form-control<?= invalid_class('email') ?>" id="email" name="email"
                           value="<?= e($val('email')) ?>" required
                           placeholder="usuario@tecvictoria.edu.mx">
                    <?php if ($err = field_error('email')): ?>
                        <div class="invalid-feedback"><?= e($err) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-12 col-md-5">
                    <label for="password" class="form-label small fw-semibold">
                        Contraseña <?= $esEdicion ? '(Dejar en blanco para conservar la actual)' : '*' ?>
                    </label>
                    <div class="input-group">
                        <input type="password" class="form-control<?= invalid_class('password') ?>" id="password" name="password"
                               <?= $esEdicion ? '' : 'required' ?>
                               placeholder="Mínimo 8 caracteres (letras y números)">
                        <button class="btn btn-outline-secondary" type="button" id="btnTogglePass">👁️</button>
                    </div>
                    <?php if ($err = field_error('password')): ?>
                        <div class="invalid-feedback d-block"><?= e($err) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-12 col-md-4">
                    <label for="password_confirmation" class="form-label small fw-semibold">
                        Confirmar Contraseña <?= $esEdicion ? '' : '*' ?>
                    </label>
                    <input type="password" class="form-control<?= invalid_class('password_confirmation') ?>" id="password_confirmation" name="password_confirmation"
                           <?= $esEdicion ? '' : 'required' ?>
                           placeholder="Repite la contraseña">
                    <?php if ($err = field_error('password_confirmation')): ?>
                        <div class="invalid-feedback d-block"><?= e($err) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-12 col-md-3 d-flex flex-column justify-content-end">
                    <button type="button" class="btn btn-outline-primary btn-sm w-100 py-2" id="btnGeneratePass">
                        ⚡ Generar contraseña segura
                    </button>
                    <div class="form-text text-xs text-center mt-1" id="generatedPassHint">Crea una clave fuerte al instante</div>
                </div>

                <div class="col-12 pt-2">
                    <div class="form-check form-switch">
                        <input type="hidden" name="activo" value="0">
                        <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1"
                               <?= $val('activo', '1') === '1' ? 'checked' : '' ?>>
                        <label class="form-check-label small fw-semibold" for="activo">
                            Cuenta activa y autorizada para iniciar sesión inmediatamente
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 3: DATOS PERSONALES Y DE CONTACTO -->
        <div class="p-3 p-md-4 rounded-4 bg-white border border-slate-200 mb-4">
            <h2 class="h6 fw-bold text-navy text-uppercase mb-3 d-flex align-items-center gap-2">
                <span>👤</span>
                <span>3. Datos Personales y de Contacto</span>
            </h2>

            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label for="numero_control" class="form-label small fw-semibold">No. de Control / Matrícula / ID</label>
                    <input type="text" class="form-control font-monospace text-uppercase<?= invalid_class('numero_control') ?>"
                           id="numero_control" name="numero_control"
                           value="<?= e($val('numero_control')) ?>"
                           placeholder="Ej. 22380123 o DOC001">
                    <?php if ($err = field_error('numero_control')): ?>
                        <div class="invalid-feedback"><?= e($err) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-12 col-md-4">
                    <label for="fecha_nacimiento" class="form-label small fw-semibold">Fecha de Nacimiento</label>
                    <input type="date" class="form-control<?= invalid_class('fecha_nacimiento') ?>"
                           id="fecha_nacimiento" name="fecha_nacimiento"
                           value="<?= e($val('fecha_nacimiento')) ?>">
                    <?php if ($err = field_error('fecha_nacimiento')): ?>
                        <div class="invalid-feedback"><?= e($err) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-12 col-md-4">
                    <label for="genero" class="form-label small fw-semibold">Género</label>
                    <select class="form-select<?= invalid_class('genero') ?>" id="genero" name="genero">
                        <option value="">Seleccionar…</option>
                        <?php foreach (Genero::cases() as $g): ?>
                            <option value="<?= e($g->value) ?>" <?= $val('genero') === $g->value ? 'selected' : '' ?>>
                                <?= e($g->etiqueta()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label for="telefono" class="form-label small fw-semibold">Teléfono de Contacto</label>
                    <input type="tel" class="form-control<?= invalid_class('telefono') ?>" id="telefono" name="telefono"
                           value="<?= e($val('telefono')) ?>" placeholder="+52 834 123 4567">
                    <?php if ($err = field_error('telefono')): ?>
                        <div class="invalid-feedback"><?= e($err) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-12 col-md-4">
                    <label for="email_alterno" class="form-label small fw-semibold">Correo Electrónico Alterno</label>
                    <input type="email" class="form-control<?= invalid_class('email_alterno') ?>" id="email_alterno" name="email_alterno"
                           value="<?= e($val('email_alterno')) ?>" placeholder="correo.personal@ejemplo.com">
                    <?php if ($err = field_error('email_alterno')): ?>
                        <div class="invalid-feedback"><?= e($err) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-12 col-md-4">
                    <label for="nacionalidad" class="form-label small fw-semibold">Nacionalidad</label>
                    <input type="text" class="form-control<?= invalid_class('nacionalidad') ?>" id="nacionalidad" name="nacionalidad"
                           value="<?= e($val('nacionalidad', 'México')) ?>" placeholder="México">
                </div>

                <div class="col-12 col-md-3">
                    <label for="pais_residencia" class="form-label small fw-semibold">País de Residencia</label>
                    <select class="form-select<?= invalid_class('pais_residencia') ?>" id="pais_residencia" name="pais_residencia">
                        <?php foreach (Catalogo::PAISES as $p): ?>
                            <option value="<?= e($p) ?>" <?= $val('pais_residencia', 'México') === $p ? 'selected' : '' ?>>
                                <?= e($p) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label for="ciudad" class="form-label small fw-semibold">Ciudad</label>
                    <input type="text" class="form-control<?= invalid_class('ciudad') ?>" id="ciudad" name="ciudad"
                           value="<?= e($val('ciudad', 'Ciudad Victoria')) ?>" placeholder="Ciudad Victoria">
                </div>

                <div class="col-12 col-md-3">
                    <label for="estado_residencia" class="form-label small fw-semibold">Estado</label>
                    <input type="text" class="form-control<?= invalid_class('estado_residencia') ?>" id="estado_residencia" name="estado_residencia"
                           value="<?= e($val('estado_residencia', 'Tamaulipas')) ?>" placeholder="Tamaulipas">
                </div>

                <div class="col-12 col-md-3">
                    <label for="codigo_postal" class="form-label small fw-semibold">Código Postal</label>
                    <input type="text" class="form-control font-monospace<?= invalid_class('codigo_postal') ?>" id="codigo_postal" name="codigo_postal"
                           value="<?= e($val('codigo_postal', '87000')) ?>" maxlength="5" placeholder="87000">
                </div>

                <div class="col-12 col-md-6">
                    <label for="contacto_emergencia_nombre" class="form-label small fw-semibold">Contacto de Emergencia (Nombre)</label>
                    <input type="text" class="form-control<?= invalid_class('contacto_emergencia_nombre') ?>"
                           id="contacto_emergencia_nombre" name="contacto_emergencia_nombre"
                           value="<?= e($val('contacto_emergencia_nombre')) ?>" placeholder="Nombre de familiar o contacto">
                </div>

                <div class="col-12 col-md-6">
                    <label for="contacto_emergencia_telefono" class="form-label small fw-semibold">Teléfono de Emergencia</label>
                    <input type="tel" class="form-control<?= invalid_class('contacto_emergencia_telefono') ?>"
                           id="contacto_emergencia_telefono" name="contacto_emergencia_telefono"
                           value="<?= e($val('contacto_emergencia_telefono')) ?>" placeholder="+52 834 000 0000">
                </div>
            </div>
        </div>

        <!-- SECCIÓN 4: PARÁMETROS ACADÉMICOS E INSTITUCIONALES -->
        <div class="p-3 p-md-4 rounded-4 bg-white border border-slate-200 mb-4">
            <h2 class="h6 fw-bold text-navy text-uppercase mb-3 d-flex align-items-center gap-2">
                <span>🎓</span>
                <span>4. Parámetros Académicos e Institucionales</span>
            </h2>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label for="institucion" class="form-label small fw-semibold">Institución Educativa</label>
                    <input type="text" class="form-control<?= invalid_class('institucion') ?>" id="institucion" name="institucion"
                           value="<?= e($val('institucion', 'Instituto Tecnológico de Ciudad Victoria')) ?>">
                </div>

                <div class="col-12 col-md-6">
                    <label for="carrera" class="form-label small fw-semibold">Carrera / Programa Educativo</label>
                    <input type="text" class="form-control<?= invalid_class('carrera') ?>" id="carrera" name="carrera"
                           value="<?= e($val('carrera', 'Ingeniería en Sistemas Computacionales')) ?>"
                           placeholder="Ej. Ingeniería en Sistemas Computacionales">
                </div>

                <div class="col-12 col-md-3">
                    <label for="nivel_estudios" class="form-label small fw-semibold">Nivel de Estudios</label>
                    <select class="form-select<?= invalid_class('nivel_estudios') ?>" id="nivel_estudios" name="nivel_estudios">
                        <option value="">Seleccionar…</option>
                        <?php foreach (NivelEstudios::cases() as $ne): ?>
                            <option value="<?= e($ne->value) ?>" <?= $val('nivel_estudios', 'ingenieria') === $ne->value ? 'selected' : '' ?>>
                                <?= e($ne->etiqueta()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label for="modalidad" class="form-label small fw-semibold">Modalidad</label>
                    <select class="form-select<?= invalid_class('modalidad') ?>" id="modalidad" name="modalidad">
                        <option value="">Seleccionar…</option>
                        <?php foreach (Modalidad::cases() as $mod): ?>
                            <option value="<?= e($mod->value) ?>" <?= $val('modalidad', 'escolarizada') === $mod->value ? 'selected' : '' ?>>
                                <?= e($mod->etiqueta()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label for="semestre" class="form-label small fw-semibold">Semestre (1 a 14)</label>
                    <input type="number" min="1" max="14" class="form-control<?= invalid_class('semestre') ?>" id="semestre" name="semestre"
                           value="<?= e($val('semestre')) ?>" placeholder="Ej. 6">
                </div>

                <div class="col-12 col-md-3">
                    <label for="nota_media" class="form-label small fw-semibold">Promedio / Nota Media</label>
                    <input type="number" step="0.01" min="0" max="100" class="form-control font-monospace<?= invalid_class('nota_media') ?>"
                           id="nota_media" name="nota_media"
                           value="<?= e($val('nota_media')) ?>" placeholder="Ej. 95.00">
                </div>

                <div class="col-12">
                    <label for="area_estudios" class="form-label small fw-semibold">Área de Especialidad / Materia o Curso Asignado</label>
                    <input type="text" class="form-control<?= invalid_class('area_estudios') ?>" id="area_estudios" name="area_estudios"
                           value="<?= e($val('area_estudios', 'Tecnologías de la Información y Software')) ?>"
                           placeholder="Ej. Algoritmos y Pensamiento Lógico / Ciencias Básicas">
                </div>
            </div>
        </div>

        <!-- SECCIÓN 5: INSCRIPCIÓN / ASIGNACIÓN INICIAL A MÓDULOS INTERACTIVOS -->
        <div class="p-3 p-md-4 rounded-4 bg-slate-50 border border-slate-200 mb-4">
            <h2 class="h6 fw-bold text-navy text-uppercase mb-2 d-flex align-items-center gap-2">
                <span>🧩</span>
                <span>5. Inscripción o Asignación a Módulos Interactivos</span>
            </h2>
            <p class="text-slate-500 text-xs mb-3">
                Selecciona los cursos en los que este usuario quedará inscrito en su apartado de <strong>Mi aprendizaje</strong>.
            </p>

            <div class="row g-2">
                <?php foreach ($modulos as $m): ?>
                    <?php $checked = in_array($m->id, $modulosInscritos, true); ?>
                    <div class="col-12 col-md-6 col-lg-3">
                        <label class="card p-3 h-100 border bg-white cursor-pointer d-flex flex-row align-items-center gap-2">
                            <input class="form-check-input mt-0" type="checkbox" name="modulos_inscritos[]"
                                   value="<?= $m->id ?>" <?= $checked ? 'checked' : '' ?>>
                            <span class="fs-5"><?= e($m->icono) ?></span>
                            <div class="min-w-0">
                                <div class="fw-semibold small text-navy text-truncate"><?= e($m->titulo) ?></div>
                                <div class="text-xs text-slate-400"><?= e($m->nivel->etiqueta()) ?></div>
                            </div>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="<?= url('/admin/usuarios') ?>" class="btn btn-outline-secondary px-4">Cancelar</a>
            <button type="submit" class="btn btn-cyan fw-bold px-4 shadow-cyan">
                <?= $esEdicion ? '💾 Guardar Cambios del Usuario' : '➕ Crear Usuario y Asignar Rol' ?>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Autocompletar dominio institucional en correo
    document.querySelectorAll('[data-fill-domain]').forEach(btn => {
        btn.addEventListener('click', () => {
            const domain = btn.getAttribute('data-fill-domain');
            const emailInput = document.getElementById('email');
            if (!emailInput) return;
            let base = emailInput.value.trim().split('@')[0];
            if (!base) {
                const nombre = (document.getElementById('nombre')?.value || '').trim().toLowerCase();
                base = nombre ? nombre.split(/\s+/).slice(0, 2).join('.').replace(/[^a-z0-9.]/g, '') : 'usuario';
            }
            emailInput.value = base + domain;
            emailInput.focus();
        });
    });

    // Mostrar/ocultar contraseña
    const passInput = document.getElementById('password');
    const passConf = document.getElementById('password_confirmation');
    const btnToggle = document.getElementById('btnTogglePass');
    btnToggle?.addEventListener('click', () => {
        if (!passInput) return;
        const nextType = passInput.type === 'password' ? 'text' : 'password';
        passInput.type = nextType;
        if (passConf) passConf.type = nextType;
    });

    // Generador de contraseña segura
    const btnGen = document.getElementById('btnGeneratePass');
    const hint = document.getElementById('generatedPassHint');
    btnGen?.addEventListener('click', () => {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
        let generated = 'TecV';
        for (let i = 0; i < 5; i++) {
            generated += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        generated += '1#';
        if (passInput) {
            passInput.type = 'text';
            passInput.value = generated;
        }
        if (passConf) {
            passConf.type = 'text';
            passConf.value = generated;
        }
        if (hint) {
            hint.innerHTML = `<span class="text-success fw-bold">Clave: <code>${generated}</code></span>`;
        }
    });
});
</script>


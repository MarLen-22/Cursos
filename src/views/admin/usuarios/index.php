<?php
use App\Core\Auth;

$currentUserId = Auth::user()?->id ?? 0;
$totalSuperAdmins = (int) ($countsPorRol['superadmin'] ?? 0);
$totalAdminsReg = (int) ($countsPorRol['admin'] ?? 0);
$totalJefesCurso = (int) ($countsPorRol['jefe_curso'] ?? 0);
$totalEstudiantes = max(0, (int) $total - ($totalSuperAdmins + $totalAdminsReg + $totalJefesCurso));
?>

<!-- Tarjetas de Resumen RBAC -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 rounded-4 shadow-sm p-3 bg-white h-100">
            <div class="text-uppercase text-xs fw-bold text-slate-400">Total Registrados</div>
            <div class="fs-3 fw-bold text-navy mt-1"><?= (int) $total ?></div>
            <div class="text-xs text-success fw-semibold"><?= (int) ($totalActivos ?? $total) ?> cuentas activas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 rounded-4 shadow-sm p-3 bg-white h-100">
            <div class="text-uppercase text-xs fw-bold text-slate-400">Superadministradores</div>
            <div class="fs-3 fw-bold text-navy mt-1"><?= $totalSuperAdmins ?></div>
            <div class="text-xs text-slate-500">👑 Control total RBAC</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 rounded-4 shadow-sm p-3 bg-white h-100">
            <div class="text-uppercase text-xs fw-bold text-slate-400">Admins y Jefes de Curso</div>
            <div class="fs-3 fw-bold text-navy mt-1"><?= $totalAdminsReg + $totalJefesCurso ?></div>
            <div class="text-xs text-slate-500">🛡️ <?= $totalAdminsReg ?> Admin · 👨‍🏫 <?= $totalJefesCurso ?> Jefe Curso</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 rounded-4 shadow-sm p-3 bg-white h-100">
            <div class="text-uppercase text-xs fw-bold text-slate-400">Estudiantes</div>
            <div class="fs-3 fw-bold text-navy mt-1"><?= $totalEstudiantes ?></div>
            <div class="text-xs text-slate-500">🎓 Alumnos en plataforma</div>
        </div>
    </div>
</div>

<div class="card border-0 rounded-4 shadow-sm p-4 bg-white mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 pb-3 border-bottom">
        <div>
            <h1 class="h4 fw-bold mb-1">Gestión de Usuarios y Control de Acceso (RBAC)</h1>
            <p class="text-slate-500 small mb-0">
                Administra estudiantes, jefes de curso, administradores y superadministradores, así como sus datos personales, académicos y privilegios.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="<?= url('/admin/usuarios/nuevo') ?>" class="btn btn-cyan fw-bold d-flex align-items-center gap-2 shadow-cyan px-3 py-2">
                <span>➕</span>
                <span>Añadir Usuario (Estudiante / Admin / Jefe de Curso)</span>
            </a>
        </div>
    </div>

    <!-- Barra de filtros interactivos -->
    <div class="row g-2 align-items-center mb-3">
        <div class="col-12 col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0">🔍</span>
                <input type="search" id="adminUserSearch" class="form-control border-start-0"
                       placeholder="Buscar por nombre, correo, matrícula, carrera o cargo…">
            </div>
        </div>
        <div class="col-6 col-md-3">
            <select id="adminUserRoleFilter" class="form-select form-select-sm">
                <option value="">Todos los roles (RBAC)</option>
                <option value="superadmin">👑 Superadministrador</option>
                <option value="admin">🛡️ Administrador</option>
                <option value="jefe_curso">👨‍🏫 Jefe de Curso</option>
                <option value="estudiante">🎓 Estudiante</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select id="adminUserStatusFilter" class="form-select form-select-sm">
                <option value="">Todos los estados</option>
                <option value="activo">Activo</option>
                <option value="deshabilitado">Deshabilitado</option>
            </select>
        </div>
        <div class="col-12 col-md-2 text-md-end">
            <span class="badge bg-slate-100 text-slate-700 px-3 py-2 w-100">
                Mostrando: <span id="adminUserVisibleCount"><?= count($usuarios) ?></span> de <?= (int) $total ?>
            </span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-3" id="adminUsersTable">
            <thead class="table-light small">
                <tr>
                    <th>ID</th>
                    <th>Nombre y Correo</th>
                    <th>Matrícula y Adscripción / Carrera</th>
                    <th>Rol / Permisos</th>
                    <th>Módulos</th>
                    <th>Estado</th>
                    <th>Último Acceso</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody class="small">
                <?php foreach ($usuarios as $u): ?>
                    <?php
                        $rolCodigo = $u->codigoRol();
                        $estadoCodigo = $u->activo ? 'activo' : 'deshabilitado';
                        $esMismoUsuario = ($u->id === $currentUserId);
                    ?>
                    <tr data-user-row
                        data-rol="<?= e($rolCodigo) ?>"
                        data-estado="<?= e($estadoCodigo) ?>">
                        <td class="text-slate-400 fw-semibold">#<?= $u->id ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar-circle flex-shrink-0"><?= e($u->iniciales()) ?></span>
                                <div>
                                    <div class="fw-semibold text-navy">
                                        <?= e($u->nombre) ?>
                                        <?php if ($esMismoUsuario): ?>
                                            <span class="badge bg-info-subtle text-info ms-1">Tú</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-slate-500 text-xs"><?= e($u->email) ?></div>
                                    <?php if ($u->telefono): ?>
                                        <div class="text-slate-400 text-xs">📞 <?= e($u->telefono) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if ($u->numeroControl): ?>
                                <span class="badge bg-slate-100 text-slate-700 font-monospace mb-1">
                                    ID: <?= e($u->numeroControl) ?>
                                </span>
                            <?php endif; ?>
                            <div class="text-slate-700 fw-medium text-xs">
                                <?= e($u->cargo ?: ($u->carrera ?: ($u->departamento ?: 'Instituto Tecnológico de Ciudad Victoria'))) ?>
                            </div>
                            <?php if ($u->semestre): ?>
                                <div class="text-slate-400 text-xs"><?= (int) $u->semestre ?>.° Semestre · <?= e($u->institucion ?: 'ITCV') ?></div>
                            <?php elseif ($u->departamento): ?>
                                <div class="text-slate-400 text-xs"><?= e($u->departamento) ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1 align-items-start">
                                <?php if ($u->nivel): ?>
                                    <span class="badge <?= e($u->nivel->badgeClass()) ?> fw-bold px-2 py-1">
                                        <?= e($u->nivel->icono() . ' ' . $u->nivel->etiqueta()) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-slate-200 text-slate-700 px-2 py-1">
                                        🎓 Estudiante
                                    </span>
                                <?php endif; ?>

                                <!-- Cambio rápido de rol -->
                                <?php if (!$esMismoUsuario && ($esSuperAdmin || $rolCodigo !== 'superadmin')): ?>
                                    <form action="<?= url('/admin/usuarios/' . $u->id . '/rol') ?>" method="POST" class="d-inline-flex align-items-center gap-1 mt-1">
                                        <?= csrf_field() ?>
                                        <select name="rol" class="form-select form-select-sm py-0 px-2 text-xs" style="width: auto; height: 24px;"
                                                aria-label="Cambiar rol de <?= e($u->nombre) ?>"
                                                onchange="this.form.submit()">
                                            <option value="estudiante" <?= $rolCodigo === 'estudiante' ? 'selected' : '' ?>>Rol: Estudiante</option>
                                            <option value="jefe_curso" <?= $rolCodigo === 'jefe_curso' ? 'selected' : '' ?>>Rol: Jefe de Curso</option>
                                            <option value="admin" <?= $rolCodigo === 'admin' ? 'selected' : '' ?>>Rol: Administrador</option>
                                            <?php if ($esSuperAdmin): ?>
                                                <option value="superadmin" <?= $rolCodigo === 'superadmin' ? 'selected' : '' ?>>Rol: Superadmin</option>
                                            <?php endif; ?>
                                        </select>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-slate-700 border">
                                📚 <?= (int) $u->totalInscripciones ?> <?= $u->totalInscripciones === 1 ? 'curso' : 'cursos' ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= $u->activo ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?>">
                                <?= $u->activo ? 'Activo' : 'Deshabilitado' ?>
                            </span>
                        </td>
                        <td class="text-slate-500 text-xs"><?= fecha($u->ultimoAcceso) ?></td>
                        <td class="text-end">
                            <div class="d-inline-flex flex-wrap justify-content-end gap-1">
                                <!-- Editar datos completos, contraseña y rol -->
                                <a href="<?= url('/admin/usuarios/' . $u->id . '/editar') ?>"
                                   class="btn btn-xs btn-outline-primary fw-semibold"
                                   title="Editar datos personales, contraseña, rol y cursos">
                                    ✏️ Editar
                                </a>

                                <!-- Activar / Desactivar cuenta -->
                                <?php if (!$esMismoUsuario): ?>
                                    <form action="<?= url('/admin/usuarios/' . $u->id . '/estado') ?>" method="POST" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-xs <?= $u->activo ? 'btn-outline-warning' : 'btn-outline-success' ?>"
                                                title="<?= $u->activo ? 'Deshabilitar cuenta' : 'Activar cuenta' ?>">
                                            <?= $u->activo ? 'Deshabilitar' : 'Activar' ?>
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <!-- Otorgar / Revocar rápido -->
                                <?php if ($esSuperAdmin && !$esMismoUsuario): ?>
                                    <?php if ($u->nivel === null): ?>
                                        <form action="<?= url('/admin/usuarios/' . $u->id . '/otorgar') ?>" method="POST" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="nivel" value="admin">
                                            <button type="submit" class="btn btn-xs btn-outline-cyan fw-semibold">
                                                Hacer Admin
                                            </button>
                                        </form>
                                    <?php elseif ($u->nivel->value !== 'superadmin'): ?>
                                        <form action="<?= url('/admin/usuarios/' . $u->id . '/revocar') ?>" method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Cambiar a <?= e(addslashes($u->nombre)) ?> al rol de Estudiante?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-xs btn-outline-secondary">
                                                Revocar Admin
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <!-- Eliminar cuenta -->
                                <?php if (!$esMismoUsuario && ($esSuperAdmin || $rolCodigo !== 'superadmin')): ?>
                                    <form action="<?= url('/admin/usuarios/' . $u->id . '/eliminar') ?>" method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar permanentemente la cuenta de <?= e(addslashes($u->nombre)) ?> (<?= e(addslashes($u->email)) ?>)?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-xs btn-outline-danger" title="Eliminar usuario">
                                            🗑️
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <?php if ($totalPaginas > 1): ?>
        <nav aria-label="Paginación" class="d-flex justify-content-center mt-3">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= url('/admin/usuarios?pagina=' . ($pagina - 1)) ?>">Anterior</a>
                </li>
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?= $pagina === $i ? 'active' : '' ?>">
                        <a class="page-link" href="<?= url('/admin/usuarios?pagina=' . $i) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $pagina >= $totalPaginas ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= url('/admin/usuarios?pagina=' . ($pagina + 1)) ?>">Siguiente</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('adminUserSearch');
    const roleFilter = document.getElementById('adminUserRoleFilter');
    const statusFilter = document.getElementById('adminUserStatusFilter');
    const countSpan = document.getElementById('adminUserVisibleCount');
    const rows = document.querySelectorAll('tr[data-user-row]');

    function filterUsers() {
        const q = (searchInput?.value || '').toLowerCase().trim();
        const rol = (roleFilter?.value || '').toLowerCase().trim();
        const est = (statusFilter?.value || '').toLowerCase().trim();
        let visible = 0;

        rows.forEach(row => {
            const rowRol = (row.getAttribute('data-rol') || '').toLowerCase();
            const rowEst = (row.getAttribute('data-estado') || '').toLowerCase();
            const text = row.textContent.toLowerCase();

            const matchQ = !q || text.includes(q);
            const matchRol = !rol || rowRol === rol;
            const matchEst = !est || rowEst === est;

            if (matchQ && matchRol && matchEst) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        if (countSpan) countSpan.textContent = String(visible);
    }

    searchInput?.addEventListener('input', filterUsers);
    roleFilter?.addEventListener('change', filterUsers);
    statusFilter?.addEventListener('change', filterUsers);
});
</script>

<div class="row g-4 mb-4">
    <div class="col-12 col-md-4">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-uppercase small fw-bold text-slate-400">Total Usuarios</div>
                    <div class="fs-2 fw-bold text-navy"><?= (int) $totalUsuarios ?></div>
                </div>
                <div class="icon-circle bg-cyan-subtle text-cyan fs-4">👥</div>
            </div>
            <div class="small text-slate-500 mt-2">Cuentas registradas en la plataforma</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-uppercase small fw-bold text-slate-400">Módulos Activos</div>
                    <div class="fs-2 fw-bold text-navy"><?= (int) $totalModulos ?></div>
                </div>
                <div class="icon-circle bg-indigo-subtle text-indigo fs-4">🧩</div>
            </div>
            <div class="small text-slate-500 mt-2">Simuladores publicados para estudiantes</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-uppercase small fw-bold text-slate-400">Administradores</div>
                    <div class="fs-2 fw-bold text-navy"><?= (int) $totalAdmins ?></div>
                </div>
                <div class="icon-circle bg-purple-subtle text-purple fs-4">🛡️</div>
            </div>
            <div class="small text-slate-500 mt-2">Usuarios con permisos en tabla <code>administrador</code></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Acciones Rápidas -->
    <div class="col-12 col-lg-4">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
            <h2 class="h5 fw-bold mb-3">Acciones Rápidas</h2>
            <div class="d-flex flex-column gap-2">
                <a href="<?= url('/admin/usuarios') ?>" class="btn btn-outline-primary text-start d-flex justify-content-between align-items-center py-2 px-3">
                    <span>👥 Administrar Usuarios y Permisos</span>
                    <span>→</span>
                </a>
                <a href="<?= url('/admin/modulos/nuevo') ?>" class="btn btn-cyan text-start d-flex justify-content-between align-items-center py-2 px-3 fw-bold">
                    <span>➕ Crear Nuevo Módulo Interactivo</span>
                    <span>→</span>
                </a>
                <a href="<?= url('/admin/modulos') ?>" class="btn btn-outline-secondary text-start d-flex justify-content-between align-items-center py-2 px-3">
                    <span>📋 Ver Catálogo de Módulos</span>
                    <span>→</span>
                </a>
            </div>

            <div class="mt-4 pt-3 border-top">
                <div class="small fw-semibold text-slate-700 mb-1">Información de Despliegue</div>
                <div class="small text-slate-500">
                    <div>Servidor Web: <strong>Apache (Puerto 8080)</strong></div>
                    <div>Base de Datos: <strong>MySQL (Puerto 3306)</strong></div>
                    <div>Compatibilidad: <strong>Windows Azure Ready</strong></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Usuarios Recientes -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-bold mb-0">Usuarios Recientes</h2>
                <a href="<?= url('/admin/usuarios') ?>" class="btn btn-sm btn-link text-decoration-none">Ver todos →</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Fecha de Registro</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?= e($u->nombre) ?></div>
                                    <div class="text-slate-400 text-xs"><?= e($u->email) ?></div>
                                </td>
                                <td>
                                    <?php if ($u->nivel): ?>
                                        <span class="badge bg-navy text-cyan"><?= e($u->nivel->etiqueta()) ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-slate-200 text-slate-700">Estudiante</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge <?= $u->activo ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?>">
                                        <?= $u->activo ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td class="text-slate-500"><?= fecha($u->creadoEn) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

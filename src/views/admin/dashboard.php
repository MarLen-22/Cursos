<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-uppercase small fw-bold text-slate-400">Total Usuarios</div>
                    <div class="fs-2 fw-bold text-navy"><?= (int) $totalUsuarios ?></div>
                </div>
                <div class="icon-circle bg-cyan-subtle text-cyan fs-4">👥</div>
            </div>
            <div class="small text-slate-500 mt-2">
                Estudiantes, Jefes de Curso y Admins
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-uppercase small fw-bold text-slate-400">Módulos Activos</div>
                    <div class="fs-2 fw-bold text-navy"><?= (int) $totalModulos ?></div>
                </div>
                <div class="icon-circle bg-indigo-subtle text-indigo fs-4">🧩</div>
            </div>
            <div class="small text-slate-500 mt-2">Simuladores publicados en catálogo</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-uppercase small fw-bold text-slate-400">Equipo Gestor (RBAC)</div>
                    <div class="fs-2 fw-bold text-navy"><?= (int) $totalAdmins ?></div>
                </div>
                <div class="icon-circle bg-purple-subtle text-purple fs-4">🛡️</div>
            </div>
            <div class="small text-slate-500 mt-2">
                <?= (int) ($countsPorRol['superadmin'] ?? 0) ?> Superadmin · <?= (int) ($countsPorRol['admin'] ?? 0) ?> Admin · <?= (int) ($countsPorRol['jefe_curso'] ?? 0) ?> Jefe Curso
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-uppercase small fw-bold text-slate-400">Inscripciones y Becas</div>
                    <div class="fs-2 fw-bold text-navy"><?= (int) ($inscripcionesStats['total'] ?? 0) ?></div>
                </div>
                <div class="icon-circle bg-sky-subtle text-sky fs-4">🎓</div>
            </div>
            <div class="small text-slate-500 mt-2">
                <?= (int) ($inscripcionesStats['finalizado'] ?? 0) ?> constancias · <?= (int) ($becasActivas ?? 2) ?> portales de becas activos
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Acciones Rápidas -->
    <div class="col-12 col-lg-4">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
            <h2 class="h5 fw-bold mb-3">Acciones Rápidas</h2>
            <div class="d-flex flex-column gap-2">
                <a href="<?= url('/admin/usuarios/nuevo') ?>" class="btn btn-cyan text-start d-flex justify-content-between align-items-center py-2 px-3 fw-bold shadow-xs">
                    <span>➕ Añadir Usuario (Alumno / Admin / Jefe)</span>
                    <span>→</span>
                </a>
                <a href="<?= url('/admin/usuarios') ?>" class="btn btn-outline-primary text-start d-flex justify-content-between align-items-center py-2 px-3">
                    <span>👥 Administrar Usuarios y Permisos</span>
                    <span>→</span>
                </a>
                <a href="<?= url('/admin/modulos/nuevo') ?>" class="btn btn-outline-primary text-start d-flex justify-content-between align-items-center py-2 px-3">
                    <span>🧩 Crear Nuevo Módulo Interactivo</span>
                    <span>→</span>
                </a>
                <a href="<?= url('/admin/modulos') ?>" class="btn btn-outline-secondary text-start d-flex justify-content-between align-items-center py-2 px-3">
                    <span>📋 Catálogo de Módulos (CRUD)</span>
                    <span>→</span>
                </a>
                <a href="<?= url('/admin/aprendizaje') ?>" class="btn btn-outline-secondary text-start d-flex justify-content-between align-items-center py-2 px-3">
                    <span>🎓 Gestionar Aprendizaje, Becas y ABP</span>
                    <span>→</span>
                </a>
            </div>

            <div class="mt-4 pt-3 border-top">
                <div class="small fw-semibold text-slate-700 mb-2">Portales Oficiales de Becas</div>
                <div class="d-flex flex-column gap-2">
                    <a href="https://cdvictoria.tecnm.mx/alumnos/becas.html" target="_blank" rel="noopener" class="btn btn-sm btn-light border text-start d-flex justify-content-between align-items-center">
                        <span>🎓 Becas TecVictoria (ITCV)</span>
                        <span class="text-cyan fw-bold">↗</span>
                    </a>
                    <a href="https://www.tecnm.mx/?vista=Becas" target="_blank" rel="noopener" class="btn btn-sm btn-light border text-start d-flex justify-content-between align-items-center">
                        <span>🏛️ Becas TECNM Nacional</span>
                        <span class="text-indigo fw-bold">↗</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Usuarios Recientes -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-bold mb-0">Usuarios y Administradores Registrados</h2>
                <div class="d-flex gap-2">
                    <a href="<?= url('/admin/usuarios/nuevo') ?>" class="btn btn-sm btn-cyan fw-semibold">+ Nuevo Usuario</a>
                    <a href="<?= url('/admin/usuarios') ?>" class="btn btn-sm btn-link text-decoration-none">Ver todos →</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>Usuario</th>
                            <th>Adscripción / Matrícula</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th class="text-end">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold text-navy"><?= e($u->nombre) ?></div>
                                    <div class="text-slate-400 text-xs"><?= e($u->email) ?></div>
                                </td>
                                <td>
                                    <?php if ($u->numeroControl): ?>
                                        <span class="font-monospace badge bg-slate-100 text-slate-700"><?= e($u->numeroControl) ?></span>
                                    <?php endif; ?>
                                    <div class="text-slate-500 text-xs text-truncate max-w-250">
                                        <?= e($u->cargo ?: ($u->carrera ?: ($u->departamento ?: 'IT Ciudad Victoria'))) ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($u->nivel): ?>
                                        <span class="badge <?= e($u->nivel->badgeClass()) ?>">
                                            <?= e($u->nivel->icono() . ' ' . $u->nivel->etiqueta()) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-slate-200 text-slate-700">🎓 Estudiante</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge <?= $u->activo ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?>">
                                        <?= $u->activo ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="<?= url('/admin/usuarios/' . $u->id . '/editar') ?>" class="btn btn-xs btn-outline-primary">
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

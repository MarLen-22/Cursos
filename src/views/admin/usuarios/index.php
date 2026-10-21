<div class="card border-0 rounded-4 shadow-sm p-4 bg-white mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div>
            <h1 class="h4 fw-bold mb-1">Gestión de Usuarios y Control de Acceso (RBAC)</h1>
            <p class="text-slate-500 small mb-0">
                Solo los usuarios registrados en la tabla <code>administrador</code> cuentan con privilegios de gestión.
                <?= $esSuperAdmin ? 'Como Superadministrador, tienes la facultad de otorgar y revocar permisos.' : 'Tienes permisos de consulta y gestión de estado.' ?>
            </p>
        </div>
        <span class="badge bg-slate-100 text-slate-700 px-3 py-2">
            Total registrados: <?= (int) $total ?>
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-3">
            <thead class="table-light small">
                <tr>
                    <th>ID</th>
                    <th>Nombre y Correo</th>
                    <th>Rol / Permisos</th>
                    <th>Estado</th>
                    <th>Último Acceso</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody class="small">
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td class="text-slate-400">#<?= $u->id ?></td>
                        <td>
                            <div class="fw-semibold text-navy"><?= e($u->nombre) ?></div>
                            <div class="text-slate-500 text-xs"><?= e($u->email) ?></div>
                        </td>
                        <td>
                            <?php if ($u->nivel): ?>
                                <span class="badge <?= $u->nivel->value === 'superadmin' ? 'bg-cyan text-navy' : 'bg-navy text-cyan' ?> fw-bold px-2 py-1">
                                    🛡️ <?= e($u->nivel->etiqueta()) ?>
                                </span>
                            <?php else: ?>
                                <span class="badge bg-slate-200 text-slate-700">Estudiante</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?= $u->activo ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?>">
                                <?= $u->activo ? 'Activo' : 'Deshabilitado' ?>
                            </span>
                        </td>
                        <td class="text-slate-500"><?= fecha($u->ultimoAcceso) ?></td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <!-- Activar / Desactivar cuenta -->
                                <form action="<?= url('/admin/usuarios/' . $u->id . '/estado') ?>" method="POST" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-xs <?= $u->activo ? 'btn-outline-warning' : 'btn-outline-success' ?>"
                                            title="<?= $u->activo ? 'Deshabilitar cuenta' : 'Activar cuenta' ?>">
                                        <?= $u->activo ? 'Deshabilitar' : 'Activar' ?>
                                    </button>
                                </form>

                                <!-- Otorgar / Revocar permisos de Administrador (Superadmin) -->
                                <?php if ($esSuperAdmin): ?>
                                    <?php if ($u->nivel === null): ?>
                                        <form action="<?= url('/admin/usuarios/' . $u->id . '/otorgar') ?>" method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Seguro que deseas otorgar permisos de Administrador a <?= e(addslashes($u->nombre)) ?>?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-xs btn-outline-cyan fw-semibold">
                                                Hacer Admin
                                            </button>
                                        </form>
                                    <?php elseif ($u->nivel->value === 'admin'): ?>
                                        <form action="<?= url('/admin/usuarios/' . $u->id . '/revocar') ?>" method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Revocar permisos de Administrador a <?= e(addslashes($u->nombre)) ?>?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-xs btn-outline-danger">
                                                Revocar Admin
                                            </button>
                                        </form>
                                    <?php endif; ?>
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

<?php

use App\Core\Auth;

$activeNav = $activeNav ?? '';
$usuario = Auth::user();
$esAdmin = Auth::isAdmin();
?>
<header class="app-header sticky-top">
    <nav class="navbar navbar-expand-lg navbar-dark bg-navy px-3 px-md-4 py-3 border-bottom border-navy-light">
        <div class="container-fluid max-w-7xl">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-white text-decoration-none" href="<?= url('/') ?>">
                <span class="brand-badge">{ }</span>
                <span class="fs-5 tracking-tight">Lógica Visual ABP</span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Navegación">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse mt-3 mt-lg-0" id="navbarMain">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-3 text-sm font-medium">
                    <li class="nav-item">
                        <a class="nav-link <?= $activeNav === 'inicio' ? 'active-nav' : '' ?>" href="<?= url('/') ?>">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activeNav === 'evaluacion' ? 'active-nav' : '' ?>" href="<?= url('/evaluacion') ?>">Evaluación</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activeNav === 'modulos' ? 'active-nav' : '' ?>" href="<?= url('/modulos') ?>">Módulos Interactivos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activeNav === 'metodologia' ? 'active-nav' : '' ?>" href="<?= url('/metodologia') ?>">Metodología ABP</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                    <?php if ($usuario !== null): ?>
                        <?php if ($esAdmin): ?>
                            <a href="<?= url('/admin') ?>" class="btn btn-sm btn-outline-cyan d-flex align-items-center gap-1">
                                <span>⚙</span>
                                <span>Panel Admin</span>
                            </a>
                        <?php endif; ?>

                        <div class="dropdown">
                            <button class="btn btn-sm btn-user-dropdown dropdown-toggle d-flex align-items-center gap-2" type="button" id="userMenuBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="avatar-circle"><?= e($usuario->iniciales()) ?></span>
                                <span class="d-none d-sm-inline text-white text-truncate max-w-150"><?= e($usuario->nombre) ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2 mt-2 dropdown-dark" aria-labelledby="userMenuBtn">
                                <li class="px-3 py-2 border-bottom border-navy-light">
                                    <div class="small text-muted">Sesión iniciada como</div>
                                    <div class="fw-semibold text-white text-truncate"><?= e($usuario->email) ?></div>
                                    <?php if ($esAdmin): ?>
                                        <span class="badge bg-cyan text-navy mt-1"><?= Auth::isSuperAdmin() ? 'Superadministrador' : 'Administrador' ?></span>
                                    <?php endif; ?>
                                </li>
                                <?php if ($esAdmin): ?>
                                    <li><a class="dropdown-item py-2" href="<?= url('/admin') ?>">Panel de control</a></li>
                                    <li><a class="dropdown-item py-2" href="<?= url('/admin/usuarios') ?>">Usuarios y permisos</a></li>
                                    <li><a class="dropdown-item py-2" href="<?= url('/admin/modulos') ?>">Gestión de módulos</a></li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                <?php endif; ?>
                                <li>
                                    <form action="<?= url('/logout') ?>" method="POST" class="m-0">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="dropdown-item text-danger py-2 w-100 text-start">Cerrar sesión</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?= url('/login') ?>" class="btn btn-sm btn-ghost text-slate-300 hover:text-white px-3">Iniciar Sesión</a>
                        <a href="<?= url('/registro') ?>" class="btn btn-sm btn-cyan px-4 py-2 rounded-pill fw-bold">Comenzar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>

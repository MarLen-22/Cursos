<?php

use App\Core\Auth;

$usuario = Auth::user();
$esSuperAdmin = Auth::isSuperAdmin();
$activeNav = $activeNav ?? '';
?>
<!DOCTYPE html>
<html lang="es" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Panel de Administración | Lógica Visual ABP') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('/vendor/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/styles.css') ?>">
</head>
<body class="d-flex flex-column h-100 bg-slate-100 text-slate-900">
    <!-- Barra superior de administración -->
    <header class="admin-topbar bg-navy px-3 px-md-4 py-2 border-bottom border-navy-light text-white sticky-top">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <a href="<?= url('/admin') ?>" class="d-flex align-items-center gap-2 text-white text-decoration-none">
                    <span class="brand-badge">{ }</span>
                    <span class="fw-bold fs-6">Lógica Visual · Panel Administrativo</span>
                </a>
                <span class="badge bg-cyan text-navy fw-bold d-none d-sm-inline">
                    <?= $esSuperAdmin ? 'Superadministrador' : 'Administrador' ?>
                </span>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="<?= url('/') ?>" class="btn btn-sm btn-outline-light d-flex align-items-center gap-1">
                    <span>↗</span>
                    <span class="d-none d-md-inline">Ver Sitio Web</span>
                </a>
                <div class="dropdown">
                    <button class="btn btn-sm btn-user-dropdown dropdown-toggle text-white d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="avatar-circle"><?= e($usuario?->iniciales()) ?></span>
                        <span class="d-none d-lg-inline"><?= e($usuario?->nombre) ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2 dropdown-dark">
                        <li class="px-3 py-1 small text-slate-400"><?= e($usuario?->email) ?></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form action="<?= url('/logout') ?>" method="POST" class="m-0">
                                <?= csrf_field() ?>
                                <button type="submit" class="dropdown-item text-danger py-2">Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- Navegación secundaria para panel admin -->
    <nav class="admin-subnav bg-white border-bottom shadow-sm px-3 px-md-4 py-2">
        <div class="container-fluid d-flex flex-wrap gap-2 align-items-center">
            <a href="<?= url('/admin') ?>" class="btn btn-sm <?= $activeNav === 'admin_dashboard' ? 'btn-primary' : 'btn-light' ?>">
                📊 Resumen
            </a>
            <a href="<?= url('/admin/usuarios') ?>" class="btn btn-sm <?= $activeNav === 'admin_usuarios' ? 'btn-primary' : 'btn-light' ?>">
                👥 Usuarios y Permisos
            </a>
            <a href="<?= url('/admin/modulos') ?>" class="btn btn-sm <?= $activeNav === 'admin_modulos' ? 'btn-primary' : 'btn-light' ?>">
                🧩 Módulos Interactivos
            </a>
        </div>
    </nav>

    <main class="flex-shrink-0 py-4">
        <div class="container-fluid max-w-7xl mx-auto px-3 px-md-4">
            <?= partial('partials/alerts') ?>
            <?= $content ?>
        </div>
    </main>

    <footer class="mt-auto py-3 bg-white border-top text-center small text-slate-500">
        <div class="container-fluid">
            Lógica Visual ABP · Sistema de Control de Acceso y Gestión Educativa · Instituto Tecnológico de Ciudad Victoria
        </div>
    </footer>

    <script src="<?= url('/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>

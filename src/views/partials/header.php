<?php

use App\Core\Auth;
use App\Core\Database;
use App\Models\ModuloRepository;

$activeNav = $activeNav ?? '';
$authAware = $authAware ?? true;
$usuario = $authAware ? Auth::user() : null;
$esAdmin = $authAware && Auth::isAdmin();

// Popular modules for the "Explora" menu. Never let the menu break a page.
$populares = [];
if ($authAware) {
    try {
        $populares = array_slice((new ModuloRepository(Database::connection()))->listActive(), 0, 4);
    } catch (\Throwable) {
        $populares = [];
    }
}
$q = isset($_GET['q']) && is_string($_GET['q']) ? mb_substr(trim($_GET['q']), 0, 80) : '';
?>
<header class="app-header sticky-top">
    <nav class="navbar navbar-expand-lg navbar-dark bg-navy px-3 px-md-4 py-2 border-bottom border-navy-light" aria-label="Navegación principal">
        <div class="container-fluid max-w-7xl gap-2">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-white text-decoration-none me-lg-4" href="<?= url('/') ?>">
                <span class="brand-badge">{ }</span>
                <span class="fs-5 tracking-tight">Lógica Visual <span class="text-cyan">ABP</span></span>
            </a>

            <div class="d-flex align-items-center gap-1 order-lg-last">
                <button type="button" class="btn-icon" data-theme-toggle aria-label="Cambiar tema claro u oscuro" title="Cambiar tema (claro/oscuro)">
                    <span class="theme-icon-light"><?= icon('moon') ?></span>
                    <span class="theme-icon-dark"><?= icon('sun') ?></span>
                </button>

                <?php if ($usuario !== null): ?>
                    <div class="dropdown">
                        <button class="btn-user-pill d-flex align-items-center gap-2" type="button" id="userMenuBtn"
                                data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menú de perfil de <?= e($usuario->nombre) ?>">
                            <span class="avatar-circle"><?= e($usuario->iniciales()) ?></span>
                            <span class="d-none d-xl-inline text-white text-truncate max-w-150 small fw-medium"><?= e($usuario->nombre) ?></span>
                            <?= icon('chevron-down', 'text-cyan icon-sm') ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end profile-menu shadow-lg border-0 p-2 mt-2" aria-labelledby="userMenuBtn">
                            <li class="d-flex align-items-center gap-3 px-3 py-3 mb-1 profile-menu-head">
                                <span class="avatar-circle avatar-lg"><?= e($usuario->iniciales()) ?></span>
                                <div class="min-w-0">
                                    <div class="fw-semibold text-truncate"><?= e($usuario->nombre) ?></div>
                                    <div class="small text-body-secondary text-truncate"><?= e($usuario->email) ?></div>
                                    <?php if ($esAdmin): ?>
                                        <span class="badge bg-cyan text-navy mt-1"><?= Auth::isSuperAdmin() ? 'Superadministrador' : 'Administrador' ?></span>
                                    <?php endif; ?>
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-item profile-item <?= $activeNav === 'aprendizaje' ? 'active' : '' ?>" href="<?= url('/mi-aprendizaje') ?>">
                                    <?= icon('book') ?> <span>Mi aprendizaje</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item profile-item <?= $activeNav === 'cuenta' ? 'active' : '' ?>" href="<?= url('/mi-cuenta') ?>">
                                    <?= icon('user-circle') ?> <span>Mi cuenta</span>
                                </a>
                            </li>
                            <?php if ($esAdmin): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item profile-item" href="<?= url('/admin') ?>"><?= icon('settings') ?> <span>Panel de control</span></a></li>
                                <li><a class="dropdown-item profile-item" href="<?= url('/admin/usuarios') ?>"><?= icon('user') ?> <span>Usuarios y permisos</span></a></li>
                                <li><a class="dropdown-item profile-item" href="<?= url('/admin/modulos') ?>"><?= icon('school') ?> <span>Gestión de módulos</span></a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="<?= url('/logout') ?>" method="POST" class="m-0">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="dropdown-item profile-item profile-item-logout">
                                        <?= icon('logout') ?> <span>Cerrar sesión</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= url('/login') ?>" class="btn btn-sm btn-ghost-light px-3 d-none d-sm-inline-flex">Iniciar sesión</a>
                    <a href="<?= url('/registro') ?>" class="btn btn-sm btn-cyan px-3 rounded-pill fw-bold">Crear cuenta</a>
                <?php endif; ?>

                <button class="navbar-toggler border-0 shadow-none ms-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
                        aria-controls="navbarMain" aria-expanded="false" aria-label="Abrir menú">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav align-items-lg-center gap-lg-2">
                    <li class="nav-item dropdown explora static-lg">
                        <button class="nav-link explora-toggle d-flex align-items-center gap-1 <?= in_array($activeNav, ['modulos', 'metodologia', 'evaluacion', 'explora'], true) ? 'active-nav' : '' ?>"
                                type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" id="exploraBtn">
                            Explora <?= icon('chevron-down', 'icon-sm explora-chevron') ?>
                        </button>
                        <div class="dropdown-menu explora-menu shadow-lg border-0 p-0" aria-labelledby="exploraBtn">
                            <div class="row g-0">
                                <div class="col-12 col-lg-6 p-3 p-lg-4 explora-left">
                                    <a href="<?= url('/modulos') ?>" class="explora-item is-active" data-explora-panel="panel-modulos">
                                        <span>Módulos interactivos</span><?= icon('chevron-right', 'icon-sm') ?>
                                    </a>
                                    <a href="<?= url('/videopodcast') ?>" class="explora-item" data-explora-panel="panel-videopodcast">
                                        <span>Videopodcast</span>
                                    </a>
                                    <a href="<?= url('/metodologia') ?>" class="explora-item" data-explora-panel="panel-iniciativas">
                                        <span>Iniciativas destacadas</span><?= icon('chevron-right', 'icon-sm') ?>
                                    </a>

                                    <p class="explora-label mt-4 mb-2">Enlaces de interés</p>
                                    <a href="<?= url('/blog') ?>" class="explora-link">Blog</a>
                                    <a href="<?= url('/quienes-somos') ?>" class="explora-link">Quiénes somos</a>
                                    <a href="<?= url('/ayuda') ?>" class="explora-link">Ayuda</a>
                                </div>

                                <div class="col-lg-6 p-4 explora-right d-none d-lg-block">
                                    <div class="explora-panel is-active" id="panel-modulos">
                                        <div class="explora-feature">
                                            <h2 class="h6 fw-bold mb-1">Módulos interactivos</h2>
                                            <p class="small mb-2">Rutas de aprendizaje gratuitas con simuladores para razonar, experimentar y explicar.</p>
                                            <a href="<?= url('/modulos') ?>" class="small fw-bold link-cyan">Ver todos</a>
                                        </div>
                                        <?php if ($populares !== []): ?>
                                            <p class="explora-label mt-4 mb-2">Populares</p>
                                            <?php foreach ($populares as $p): ?>
                                                <a href="<?= url('/modulos/' . $p->slug) ?>" class="explora-link"><?= e($p->titulo) ?></a>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="explora-panel" id="panel-videopodcast">
                                        <div class="explora-feature">
                                            <h2 class="h6 fw-bold mb-1">Videopodcast</h2>
                                            <p class="small mb-2">Conversaciones con docentes y estudiantes sobre cómo pensar antes de programar.</p>
                                            <a href="<?= url('/videopodcast') ?>" class="small fw-bold link-cyan">Ver episodios</a>
                                        </div>
                                    </div>
                                    <div class="explora-panel" id="panel-iniciativas">
                                        <div class="explora-feature">
                                            <h2 class="h6 fw-bold mb-1">Iniciativas destacadas</h2>
                                            <p class="small mb-2">Conoce cómo aprendemos y cómo medimos el progreso.</p>
                                        </div>
                                        <p class="explora-label mt-4 mb-2">Descubre</p>
                                        <a href="<?= url('/metodologia') ?>" class="explora-link">Metodología ABP</a>
                                        <a href="<?= url('/evaluacion') ?>" class="explora-link">Evaluación formativa</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <form class="header-search ms-lg-auto me-lg-2 my-3 my-lg-0" action="<?= url('/buscar') ?>" method="GET" role="search">
                    <label for="headerSearch" class="visually-hidden">Buscar módulos, artículos y ayuda</label>
                    <input type="search" id="headerSearch" name="q" value="<?= e($q) ?>" placeholder="Módulos, artículos, ayuda…"
                           autocomplete="off" maxlength="80" enterkeyhint="search">
                    <kbd class="search-kbd d-none d-lg-inline" title="Atajo de teclado">/</kbd>
                    <button type="submit" aria-label="Buscar"><?= icon('search') ?></button>
                </form>
            </div>
        </div>
    </nav>
</header>

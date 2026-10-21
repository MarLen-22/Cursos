<?php

declare(strict_types=1);

use App\Controllers\AdminController;
use App\Controllers\AdminModuloController;
use App\Controllers\AssetController;
use App\Controllers\AuthController;
use App\Controllers\EvaluacionController;
use App\Controllers\HomeController;
use App\Controllers\MetodologiaController;
use App\Controllers\ModuloController;
use App\Core\Router;

return static function (Router $r): void {
    // Público
    $r->get('/', [HomeController::class, 'index']);
    $r->get('/modulos', [ModuloController::class, 'index']);
    $r->get('/modulos/{slug:[a-z0-9]+(?:-[a-z0-9]+)*}', [ModuloController::class, 'show']);
    $r->get('/metodologia', [MetodologiaController::class, 'index']);
    $r->get('/evaluacion', [EvaluacionController::class, 'index']);

    // Autenticación
    $r->get('/login', [AuthController::class, 'showLogin'], ['guest']);
    $r->post('/login', [AuthController::class, 'login'], ['guest']);
    $r->get('/registro', [AuthController::class, 'showRegistro'], ['guest']);
    $r->post('/registro', [AuthController::class, 'registro'], ['guest']);
    $r->post('/logout', [AuthController::class, 'logout'], ['auth']);

    // Administración (solo usuarios presentes en la tabla administrador)
    $r->get('/admin', [AdminController::class, 'dashboard'], ['admin']);
    $r->get('/admin/usuarios', [AdminController::class, 'usuarios'], ['admin']);
    $r->post('/admin/usuarios/{id:\d+}/estado', [AdminController::class, 'estado'], ['admin']);
    $r->post('/admin/usuarios/{id:\d+}/otorgar', [AdminController::class, 'otorgar'], ['superadmin']);
    $r->post('/admin/usuarios/{id:\d+}/revocar', [AdminController::class, 'revocar'], ['superadmin']);

    $r->get('/admin/modulos', [AdminModuloController::class, 'index'], ['admin']);
    $r->get('/admin/modulos/nuevo', [AdminModuloController::class, 'create'], ['admin']);
    $r->post('/admin/modulos', [AdminModuloController::class, 'store'], ['admin']);
    $r->get('/admin/modulos/{id:\d+}/editar', [AdminModuloController::class, 'edit'], ['admin']);
    $r->post('/admin/modulos/{id:\d+}', [AdminModuloController::class, 'update'], ['admin']);
    $r->post('/admin/modulos/{id:\d+}/estado', [AdminModuloController::class, 'estado'], ['admin']);

    // Bootstrap vive fuera de public/ (carpeta BootStrap/); se sirve con caché larga.
    $r->get('/vendor/bootstrap/{path:(?:css|js)/[a-z0-9.]+}', [AssetController::class, 'bootstrap']);
};

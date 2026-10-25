<?php

declare(strict_types=1);

use App\Controllers\AdminController;
use App\Controllers\AdminModuloController;
use App\Controllers\AprendizajeController;
use App\Controllers\AssetController;
use App\Controllers\AuthController;
use App\Controllers\BuscarController;
use App\Controllers\CuentaController;
use App\Controllers\EvaluacionController;
use App\Controllers\HomeController;
use App\Controllers\InfoController;
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
    $r->get('/buscar', [BuscarController::class, 'index']);
    $r->get('/videopodcast', [InfoController::class, 'videopodcast']);
    $r->get('/blog', [InfoController::class, 'blog']);
    $r->get('/quienes-somos', [InfoController::class, 'quienesSomos']);
    $r->get('/ayuda', [InfoController::class, 'ayuda']);

    // Autenticación
    $r->get('/login', [AuthController::class, 'showLogin'], ['guest']);
    $r->post('/login', [AuthController::class, 'login'], ['guest']);
    $r->get('/registro', [AuthController::class, 'showRegistro'], ['guest']);
    $r->post('/registro', [AuthController::class, 'registro'], ['guest']);
    $r->post('/logout', [AuthController::class, 'logout'], ['auth']);

    // Área del alumno
    $slug = '{slug:[a-z0-9]+(?:-[a-z0-9]+)*}';
    $r->get('/mi-aprendizaje', [AprendizajeController::class, 'index'], ['auth']);
    $r->get('/mi-aprendizaje/{id:\d+}/constancia', [AprendizajeController::class, 'constancia'], ['auth']);
    $r->post('/mi-aprendizaje/{id:\d+}/baja', [AprendizajeController::class, 'baja'], ['auth']);
    $r->post('/modulos/' . $slug . '/inscripcion', [AprendizajeController::class, 'inscribir'], ['auth']);
    $r->post('/modulos/' . $slug . '/avance', [AprendizajeController::class, 'avance'], ['auth']);
    $r->get('/mi-cuenta', [CuentaController::class, 'show'], ['auth']);
    $r->post('/mi-cuenta', [CuentaController::class, 'update'], ['auth']);

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

<?php

declare(strict_types=1);

use App\Controllers\AdminAprendizajeController;
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

    // Área del alumno y aprendizaje
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

    // Usuarios y Permisos (RBAC + Añadir/Editar Estudiantes, Jefes de Curso, Admins y Superadmins)
    $r->get('/admin/usuarios', [AdminController::class, 'usuarios'], ['admin']);
    $r->get('/admin/usuarios/nuevo', [AdminController::class, 'createUsuario'], ['admin']);
    $r->post('/admin/usuarios', [AdminController::class, 'storeUsuario'], ['admin']);
    $r->get('/admin/usuarios/{id:\d+}/editar', [AdminController::class, 'editUsuario'], ['admin']);
    $r->post('/admin/usuarios/{id:\d+}', [AdminController::class, 'updateUsuario'], ['admin']);
    $r->post('/admin/usuarios/{id:\d+}/rol', [AdminController::class, 'cambiarRol'], ['admin']);
    $r->post('/admin/usuarios/{id:\d+}/estado', [AdminController::class, 'estado'], ['admin']);
    $r->post('/admin/usuarios/{id:\d+}/otorgar', [AdminController::class, 'otorgar'], ['admin']);
    $r->post('/admin/usuarios/{id:\d+}/revocar', [AdminController::class, 'revocar'], ['admin']);
    $r->post('/admin/usuarios/{id:\d+}/eliminar', [AdminController::class, 'eliminarUsuario'], ['admin']);

    // Módulos Interactivos (CRUD Completo: Crear, Ver, Editar, Ocultar/Publicar y Eliminar)
    $r->get('/admin/modulos', [AdminModuloController::class, 'index'], ['admin']);
    $r->get('/admin/modulos/nuevo', [AdminModuloController::class, 'create'], ['admin']);
    $r->post('/admin/modulos', [AdminModuloController::class, 'store'], ['admin']);
    $r->get('/admin/modulos/{id:\d+}/editar', [AdminModuloController::class, 'edit'], ['admin']);
    $r->post('/admin/modulos/{id:\d+}', [AdminModuloController::class, 'update'], ['admin']);
    $r->post('/admin/modulos/{id:\d+}/estado', [AdminModuloController::class, 'estado'], ['admin']);
    $r->post('/admin/modulos/{id:\d+}/eliminar', [AdminModuloController::class, 'destroy'], ['admin']);

    // Nueva pestaña: Aprendizaje, Becas (TecVictoria y TECNM), Inscripciones y Metodología ABP
    $r->get('/admin/aprendizaje', [AdminAprendizajeController::class, 'index'], ['admin']);
    $r->post('/admin/aprendizaje/becas', [AdminAprendizajeController::class, 'storeBeca'], ['admin']);
    $r->post('/admin/aprendizaje/becas/{id:\d+}', [AdminAprendizajeController::class, 'updateBeca'], ['admin']);
    $r->post('/admin/aprendizaje/becas/{id:\d+}/estado', [AdminAprendizajeController::class, 'estadoBeca'], ['admin']);
    $r->post('/admin/aprendizaje/becas/{id:\d+}/eliminar', [AdminAprendizajeController::class, 'destroyBeca'], ['admin']);
    $r->post('/admin/aprendizaje/inscripciones', [AdminAprendizajeController::class, 'inscribirUsuario'], ['admin']);
    $r->post('/admin/aprendizaje/inscripciones/{id:\d+}/avance', [AdminAprendizajeController::class, 'actualizarInscripcion'], ['admin']);
    $r->post('/admin/aprendizaje/inscripciones/{id:\d+}/eliminar', [AdminAprendizajeController::class, 'eliminarInscripcion'], ['admin']);
    $r->post('/admin/aprendizaje/etapas/{id:\d+}', [AdminAprendizajeController::class, 'actualizarEtapaAbp'], ['admin']);
    $r->post('/admin/aprendizaje/evaluaciones/{id:\d+}', [AdminAprendizajeController::class, 'actualizarEvaluacion'], ['admin']);

    // Bootstrap vive fuera de public/ (carpeta BootStrap/); se sirve con caché larga.
    $r->get('/vendor/bootstrap/{path:(?:css|js)/[a-z0-9.]+}', [AssetController::class, 'bootstrap']);
};

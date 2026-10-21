<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\HttpException;
use App\Models\ModuloDatos;
use App\Models\ModuloRepository;

final class AdminModuloController extends Controller
{
    public function index(): void
    {
        $repo = new ModuloRepository($this->db());
        $modulos = $repo->listAll();

        $this->render('admin/modulos/index', [
            'title'     => 'Gestión de Módulos | Panel Administrativo',
            'activeNav' => 'admin_modulos',
            'modulos'   => $modulos,
        ], 'layouts/admin');
    }

    public function create(): void
    {
        $this->render('admin/modulos/form', [
            'title'     => 'Nuevo Módulo Interactivo | Panel Administrativo',
            'activeNav' => 'admin_modulos',
            'modulo'    => null,
        ], 'layouts/admin');
    }

    public function store(): void
    {
        $input = $this->request->only(ModuloDatos::CAMPOS);
        [$datos, $errores] = ModuloDatos::desdeFormulario($input);

        $repo = new ModuloRepository($this->db());
        if ($datos !== null && $repo->slugExists($datos->slug)) {
            $errores['slug'] = 'Este identificador (slug) ya está en uso por otro módulo.';
        }

        if ($errores !== [] || $datos === null) {
            $this->backWithErrors('/admin/modulos/nuevo', $errores, $input);
        }

        $id = $repo->create($datos);

        $this->flash('success', 'Módulo interactivo creado exitosamente.');
        $this->redirect('/admin/modulos');
    }

    public function edit(string $id): void
    {
        $moduloId = $this->intParam($id);
        $repo = new ModuloRepository($this->db());
        $modulo = $repo->findById($moduloId);

        if ($modulo === null) {
            throw new HttpException(404, 'Módulo no encontrado.');
        }

        $this->render('admin/modulos/form', [
            'title'     => 'Editar Módulo: ' . e($modulo->titulo),
            'activeNav' => 'admin_modulos',
            'modulo'    => $modulo,
        ], 'layouts/admin');
    }

    public function update(string $id): void
    {
        $moduloId = $this->intParam($id);
        $repo = new ModuloRepository($this->db());
        $existente = $repo->findById($moduloId);

        if ($existente === null) {
            throw new HttpException(404, 'Módulo no encontrado.');
        }

        $input = $this->request->only(ModuloDatos::CAMPOS);
        [$datos, $errores] = ModuloDatos::desdeFormulario($input);

        if ($datos !== null && $repo->slugExists($datos->slug, $moduloId)) {
            $errores['slug'] = 'Este identificador (slug) ya está en uso por otro módulo.';
        }

        if ($errores !== [] || $datos === null) {
            $this->backWithErrors('/admin/modulos/' . $moduloId . '/editar', $errores, $input);
        }

        $repo->update($moduloId, $datos);

        $this->flash('success', 'Módulo interactivo actualizado correctamente.');
        $this->redirect('/admin/modulos');
    }

    public function estado(string $id): void
    {
        $moduloId = $this->intParam($id);
        $repo = new ModuloRepository($this->db());
        $modulo = $repo->findById($moduloId);

        if ($modulo === null) {
            throw new HttpException(404, 'Módulo no encontrado.');
        }

        $nuevo = !$modulo->activo;
        $repo->setActivo($moduloId, $nuevo);

        $this->flash('success', sprintf('El módulo "%s" fue %s.', $modulo->titulo, $nuevo ? 'activado' : 'desactivado'));
        $this->redirect('/admin/modulos');
    }
}

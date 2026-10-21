<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\HttpException;
use App\Models\ModuloRepository;

final class ModuloController extends Controller
{
    public function index(): void
    {
        $repo = new ModuloRepository($this->db());
        $modulos = $repo->listActive();

        $this->render('modulos/index', [
            'title'     => 'Módulos Interactivos | Lógica Visual ABP',
            'activeNav' => 'modulos',
            'modulos'   => $modulos,
        ]);
    }

    public function show(string $slug): void
    {
        $repo = new ModuloRepository($this->db());
        $modulo = $repo->findActiveBySlug($slug);

        if ($modulo === null) {
            throw new HttpException(404, 'El módulo interactivo solicitado no existe o está deshabilitado.');
        }

        $this->render('modulos/show', [
            'title'     => e($modulo->titulo) . ' | Lógica Visual ABP',
            'activeNav' => 'modulos',
            'modulo'    => $modulo,
        ]);
    }
}

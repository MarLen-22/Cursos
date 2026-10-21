<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ModuloRepository;

final class HomeController extends Controller
{
    public function index(): void
    {
        $repo = new ModuloRepository($this->db());
        $modulos = $repo->listActive();

        $this->render('home/index', [
            'title'     => 'Inicio | Lógica Visual ABP',
            'activeNav' => 'inicio',
            'modulos'   => $modulos,
        ]);
    }
}

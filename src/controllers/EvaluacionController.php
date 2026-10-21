<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\EvaluacionRepository;
use App\Models\ProgresoRepository;

final class EvaluacionController extends Controller
{
    public function index(): void
    {
        $evalRepo = new EvaluacionRepository($this->db());
        $progRepo = new ProgresoRepository($this->db());

        $evaluaciones = $evalRepo->listAll();

        $usuario = Auth::user();
        $progreso = $usuario !== null
            ? $progRepo->getForUsuario($usuario->id)
            : [
                'comprension'  => 78,
                'argumentacion' => 64,
                'aplicacion'   => 71,
            ];

        $this->render('evaluacion/index', [
            'title'        => 'Evaluación | Lógica Visual ABP',
            'activeNav'    => 'evaluacion',
            'evaluaciones' => $evaluaciones,
            'progreso'     => $progreso,
        ]);
    }
}

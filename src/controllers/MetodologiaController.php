<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\EtapaAbpRepository;

final class MetodologiaController extends Controller
{
    public function index(): void
    {
        $repo = new EtapaAbpRepository($this->db());
        $etapas = $repo->listAll();

        $this->render('metodologia/index', [
            'title'     => 'Metodología ABP | Lógica Visual ABP',
            'activeNav' => 'metodologia',
            'etapas'    => $etapas,
        ]);
    }
}

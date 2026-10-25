<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ModuloRepository;

/** Header search: matches modules (DB) and site sections (static index). */
final class BuscarController extends Controller
{
    public function index(): void
    {
        $q = mb_substr($this->request->query('q'), 0, 80);
        $modulos = [];
        $paginas = [];

        if (mb_strlen($q) >= 2) {
            $modulos = (new ModuloRepository($this->db()))->search($q);

            $needle = self::normalizar($q);
            $paginas = array_values(array_filter(
                InfoController::PAGINAS,
                static fn (array $p): bool => str_contains(
                    self::normalizar($p['titulo'] . ' ' . $p['descripcion'] . ' ' . $p['claves']),
                    $needle,
                ),
            ));
        }

        $this->render('buscar/index', [
            'title'     => ($q !== '' ? '«' . $q . '» · ' : '') . 'Búsqueda | Lógica Visual ABP',
            'activeNav' => '',
            'q'         => $q,
            'modulos'   => $modulos,
            'paginas'   => $paginas,
        ]);
    }

    /** Lowercase and strip accents so "evaluacion" matches "Evaluación". */
    private static function normalizar(string $texto): string
    {
        $texto = mb_strtolower($texto);

        return strtr($texto, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n']);
    }
}

<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\HttpException;
use App\Models\EstadoInscripcion;
use App\Models\Inscripcion;
use App\Models\InscripcionRepository;
use App\Models\Modulo;
use App\Models\ModuloRepository;

/** "Mi aprendizaje": enrollments, progress and completion certificates. */
final class AprendizajeController extends Controller
{
    public function index(): void
    {
        $usuario = Auth::user();
        $inscripciones = (new InscripcionRepository($this->db()))->listForUsuario($usuario->id);

        $inscritos = array_map(static fn (Inscripcion $i): int => $i->idModulo, $inscripciones);
        $recomendados = array_values(array_filter(
            (new ModuloRepository($this->db()))->listActive(),
            static fn (Modulo $m): bool => !in_array($m->id, $inscritos, true),
        ));

        $resumen = ['total' => count($inscripciones), 'en_curso' => 0, 'inscrito' => 0, 'finalizado' => 0];
        foreach ($inscripciones as $i) {
            $resumen[$i->estado->value]++;
        }

        $this->render('aprendizaje/index', [
            'title'         => 'Mi aprendizaje | Lógica Visual ABP',
            'activeNav'     => 'aprendizaje',
            'inscripciones' => $inscripciones,
            'recomendados'  => array_slice($recomendados, 0, 3),
            'resumen'       => $resumen,
        ]);
    }

    public function inscribir(string $slug): void
    {
        $modulo = $this->moduloActivo($slug);
        (new InscripcionRepository($this->db()))->inscribir(Auth::user()->id, $modulo->id);

        $this->flash('success', 'Te inscribiste en «' . $modulo->titulo . '». Lo encontrarás en Mi aprendizaje.');
        $this->redirect('/modulos/' . $modulo->slug);
    }

    public function avance(string $slug): void
    {
        $modulo = $this->moduloActivo($slug);
        $repo = new InscripcionRepository($this->db());
        $inscripcion = $repo->findByModulo(Auth::user()->id, $modulo->id);

        if ($inscripcion === null) {
            $this->flash('warning', 'Primero inscríbete en el módulo.');
            $this->redirect('/modulos/' . $modulo->slug);
        }

        $accion = $this->request->input('accion');
        if ($accion === 'finalizar') {
            $repo->actualizarAvance($inscripcion->id, EstadoInscripcion::Finalizado, 100);
            $this->flash('success', '¡Felicidades! Completaste «' . $modulo->titulo . '». Tu constancia ya está disponible.');
        } elseif ($accion === 'comenzar' && !$inscripcion->finalizado()) {
            $repo->actualizarAvance($inscripcion->id, EstadoInscripcion::EnCurso, max($inscripcion->avance, 25));
            $this->flash('info', 'Módulo en curso. ¡Sigue así!');
        }

        $destino = $this->request->input('volver') === 'aprendizaje' ? '/mi-aprendizaje' : '/modulos/' . $modulo->slug;
        $this->redirect($destino);
    }

    public function baja(string $id): void
    {
        (new InscripcionRepository($this->db()))->eliminar(Auth::user()->id, $this->intParam($id));

        $this->flash('info', 'Te diste de baja del módulo. Puedes volver a inscribirte cuando quieras.');
        $this->redirect('/mi-aprendizaje');
    }

    public function constancia(string $id): void
    {
        $usuario = Auth::user();
        $inscripcion = (new InscripcionRepository($this->db()))->findForUsuario($usuario->id, $this->intParam($id));

        if ($inscripcion === null || !$inscripcion->finalizado()) {
            throw new HttpException(404, 'La constancia solo está disponible para módulos finalizados.');
        }

        $this->render('aprendizaje/constancia', [
            'title'       => 'Constancia · ' . $inscripcion->titulo,
            'usuario'     => $usuario,
            'inscripcion' => $inscripcion,
            'folio'       => sprintf('LV-%05d-%04d', $inscripcion->id, $usuario->id),
        ], null);
    }

    private function moduloActivo(string $slug): Modulo
    {
        $modulo = (new ModuloRepository($this->db()))->findActiveBySlug($slug);
        if ($modulo === null) {
            throw new HttpException(404, 'El módulo interactivo solicitado no existe o está deshabilitado.');
        }

        return $modulo;
    }
}

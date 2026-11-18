<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\BecaRepository;
use App\Models\EstadoInscripcion;
use App\Models\EtapaAbpRepository;
use App\Models\EvaluacionRepository;
use App\Models\InscripcionRepository;
use App\Models\ModuloRepository;
use App\Models\UsuarioRepository;

/**
 * Nueva pestaña administrativa: Gestión de Aprendizaje, Becas (TecVictoria y TECNM),
 * Inscripciones/Constancias y Parámetros Pedagógicos ABP.
 */
final class AdminAprendizajeController extends Controller
{
    public function index(): void
    {
        $db = $this->db();
        $becaRepo = new BecaRepository($db);
        $inscRepo = new InscripcionRepository($db);
        $modRepo = new ModuloRepository($db);
        $userRepo = new UsuarioRepository($db);
        $etapaRepo = new EtapaAbpRepository($db);
        $evalRepo = new EvaluacionRepository($db);

        $this->render('admin/aprendizaje/index', [
            'title'         => 'Aprendizaje, Becas y Configuración ABP | Panel Administrativo',
            'activeNav'     => 'admin_aprendizaje',
            'becas'         => $becaRepo->listAll(),
            'inscripciones' => $inscRepo->listAllWithUsuarioAndModulo(),
            'stats'         => $inscRepo->countStats(),
            'modulos'       => $modRepo->listAll(),
            'usuarios'      => $userRepo->listAllWithRole(),
            'etapasAbp'     => $etapaRepo->listAll(),
            'evaluaciones'  => $evalRepo->listAll(),
        ], 'layouts/admin');
    }

    public function storeBeca(): void
    {
        $input = $this->request->only(['institucion', 'siglas', 'titulo', 'descripcion', 'url', 'estado', 'icono', 'orden', 'activo']);

        if ($input['titulo'] === '' || $input['url'] === '' || !filter_var($input['url'], FILTER_VALIDATE_URL)) {
            $this->flash('error', 'Por favor ingresa un título válido y una dirección URL completa (https://...).');
            $this->redirect('/admin/aprendizaje#seccion-becas');
        }

        $clave = strtolower((string) preg_replace('/[^a-zA-Z0-9]+/', '-', $input['siglas'] . '-' . $input['titulo'])) . '-' . random_int(10, 99);

        (new BecaRepository($this->db()))->create([
            'clave'       => substr($clave, 0, 58),
            'institucion' => $input['institucion'] ?: 'Instituto Tecnológico de Ciudad Victoria',
            'siglas'      => $input['siglas'] ?: 'TecVictoria',
            'titulo'      => $input['titulo'],
            'descripcion' => $input['descripcion'] ?: 'Convocatoria de beca e iniciativa estudiantil.',
            'url'         => $input['url'],
            'estado'      => $input['estado'] ?: 'Convocatoria activa',
            'icono'       => $input['icono'] ?: '🎓',
            'orden'       => max(1, (int) ($input['orden'] ?: 1)),
            'activo'      => ($input['activo'] ?? '1') === '1' ? 1 : 0,
        ]);

        $this->flash('success', sprintf('La convocatoria de beca "%s" fue añadida a Mi Aprendizaje.', $input['titulo']));
        $this->redirect('/admin/aprendizaje#seccion-becas');
    }

    public function updateBeca(string $id): void
    {
        $becaId = $this->intParam($id);
        $repo = new BecaRepository($this->db());
        $existente = $repo->findById($becaId);

        if ($existente === null) {
            $this->flash('error', 'Beca no encontrada.');
            $this->redirect('/admin/aprendizaje#seccion-becas');
        }

        $input = $this->request->only(['institucion', 'siglas', 'titulo', 'descripcion', 'url', 'estado', 'icono', 'orden', 'activo']);
        if ($input['titulo'] === '' || $input['url'] === '' || !filter_var($input['url'], FILTER_VALIDATE_URL)) {
            $this->flash('error', 'El título y una URL válida son obligatorios.');
            $this->redirect('/admin/aprendizaje#seccion-becas');
        }

        $repo->update($becaId, [
            'institucion' => $input['institucion'] ?: $existente->institucion,
            'siglas'      => $input['siglas'] ?: $existente->siglas,
            'titulo'      => $input['titulo'],
            'descripcion' => $input['descripcion'] ?: $existente->descripcion,
            'url'         => $input['url'],
            'estado'      => $input['estado'] ?: $existente->estado,
            'icono'       => $input['icono'] ?: $existente->icono,
            'orden'       => max(1, (int) ($input['orden'] ?: $existente->orden)),
            'activo'      => ($input['activo'] ?? '1') === '1' ? 1 : 0,
        ]);

        $this->flash('success', sprintf('Beca "%s" actualizada correctamente.', $input['titulo']));
        $this->redirect('/admin/aprendizaje#seccion-becas');
    }

    public function estadoBeca(string $id): void
    {
        $becaId = $this->intParam($id);
        $repo = new BecaRepository($this->db());
        $beca = $repo->findById($becaId);

        if ($beca !== null) {
            $repo->setActivo($becaId, !$beca->activo);
            $this->flash('success', sprintf('La beca "%s" fue %s.', $beca->titulo, !$beca->activo ? 'activada' : 'ocultada'));
        }

        $this->redirect('/admin/aprendizaje#seccion-becas');
    }

    public function destroyBeca(string $id): void
    {
        $becaId = $this->intParam($id);
        $repo = new BecaRepository($this->db());
        $beca = $repo->findById($becaId);

        if ($beca !== null) {
            $repo->delete($becaId);
            $this->flash('success', sprintf('La beca "%s" fue eliminada.', $beca->titulo));
        }

        $this->redirect('/admin/aprendizaje#seccion-becas');
    }

    public function inscribirUsuario(): void
    {
        $idUsuario = (int) $this->request->input('id_usuario', '0');
        $idModulo = (int) $this->request->input('id_modulo', '0');
        $estadoStr = (string) $this->request->input('estado', 'inscrito');
        $avance = (int) $this->request->input('avance', '0');

        if ($idUsuario <= 0 || $idModulo <= 0) {
            $this->flash('error', 'Selecciona un usuario y un módulo válidos.');
            $this->redirect('/admin/aprendizaje#seccion-inscripciones');
        }

        $estado = EstadoInscripcion::tryFrom($estadoStr) ?? EstadoInscripcion::Inscrito;
        if ($estado === EstadoInscripcion::Finalizado) {
            $avance = 100;
        } elseif ($estado === EstadoInscripcion::EnCurso && $avance <= 0) {
            $avance = 50;
        }

        (new InscripcionRepository($this->db()))->inscribirConAvance($idUsuario, $idModulo, $estado, $avance);

        $this->flash('success', 'Inscripción y avance registrados correctamente.');
        $this->redirect('/admin/aprendizaje#seccion-inscripciones');
    }

    public function actualizarInscripcion(string $id): void
    {
        $idInscripcion = $this->intParam($id);
        $estadoStr = (string) $this->request->input('estado', 'en_curso');
        $avance = (int) $this->request->input('avance', '50');

        $estado = EstadoInscripcion::tryFrom($estadoStr) ?? EstadoInscripcion::EnCurso;
        if ($estado === EstadoInscripcion::Finalizado) {
            $avance = 100;
        } elseif ($estado === EstadoInscripcion::Inscrito && $avance === 100) {
            $avance = 0;
        }

        (new InscripcionRepository($this->db()))->actualizarAvance($idInscripcion, $estado, $avance);

        $this->flash('success', 'El estado y avance del alumno fueron actualizados.');
        $this->redirect('/admin/aprendizaje#seccion-inscripciones');
    }

    public function eliminarInscripcion(string $id): void
    {
        $idInscripcion = $this->intParam($id);
        (new InscripcionRepository($this->db()))->eliminarPorId($idInscripcion);

        $this->flash('info', 'La inscripción del usuario al módulo fue eliminada.');
        $this->redirect('/admin/aprendizaje#seccion-inscripciones');
    }

    public function actualizarEtapaAbp(string $id): void
    {
        $idEtapa = $this->intParam($id);
        $nombre = trim((string) $this->request->input('nombre', ''));
        $descripcion = trim((string) $this->request->input('descripcion', ''));

        if ($nombre === '' || $descripcion === '') {
            $this->flash('error', 'El nombre y la descripción de la etapa ABP son obligatorios.');
            $this->redirect('/admin/aprendizaje#seccion-pedagogia');
        }

        (new EtapaAbpRepository($this->db()))->update($idEtapa, $nombre, $descripcion);

        $this->flash('success', sprintf('Etapa ABP "%s" actualizada en el sitio.', $nombre));
        $this->redirect('/admin/aprendizaje#seccion-pedagogia');
    }

    public function actualizarEvaluacion(string $id): void
    {
        $idEval = $this->intParam($id);
        $titulo = trim((string) $this->request->input('titulo', ''));
        $etapa = trim((string) $this->request->input('etapa', ''));
        $descripcion = trim((string) $this->request->input('descripcion', ''));
        $icono = trim((string) $this->request->input('icono', '📊'));

        if ($titulo === '' || $descripcion === '') {
            $this->flash('error', 'El título y la descripción de la evaluación son obligatorios.');
            $this->redirect('/admin/aprendizaje#seccion-pedagogia');
        }

        (new EvaluacionRepository($this->db()))->update($idEval, $titulo, $etapa ?: 'Proceso ABP', $descripcion, $icono ?: '📊');

        $this->flash('success', sprintf('Criterio de evaluación "%s" actualizado correctamente.', $titulo));
        $this->redirect('/admin/aprendizaje#seccion-pedagogia');
    }
}


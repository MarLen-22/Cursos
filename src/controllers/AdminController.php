<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\AdministradorRepository;
use App\Models\ModuloRepository;
use App\Models\NivelAdmin;
use App\Models\UsuarioRepository;

final class AdminController extends Controller
{
    public function dashboard(): void
    {
        $db = $this->db();
        $userRepo = new UsuarioRepository($db);
        $modRepo = new ModuloRepository($db);
        $adminRepo = new AdministradorRepository($db);

        $totalUsuarios = $userRepo->countAll();
        $totalModulos = $modRepo->countActive();
        $totalAdmins = $adminRepo->countAll();
        $recientes = $userRepo->paginateWithRole(1, 6);

        $this->render('admin/dashboard', [
            'title'          => 'Panel de Administración | Lógica Visual ABP',
            'activeNav'      => 'admin_dashboard',
            'totalUsuarios'  => $totalUsuarios,
            'totalModulos'   => $totalModulos,
            'totalAdmins'    => $totalAdmins,
            'usuarios'       => $recientes,
        ], 'layouts/admin');
    }

    public function usuarios(): void
    {
        $page = max(1, $this->intParam((string) $this->request->query('pagina', '1')));
        $perPage = 15;

        $db = $this->db();
        $userRepo = new UsuarioRepository($db);

        $total = $userRepo->countAll();
        $usuarios = $userRepo->paginateWithRole($page, $perPage);
        $totalPaginas = (int) ceil($total / $perPage);

        $this->render('admin/usuarios/index', [
            'title'        => 'Gestión de Usuarios y Permisos | Lógica Visual ABP',
            'activeNav'    => 'admin_usuarios',
            'usuarios'     => $usuarios,
            'pagina'       => $page,
            'totalPaginas' => max(1, $totalPaginas),
            'total'        => $total,
            'esSuperAdmin' => Auth::isSuperAdmin(),
        ], 'layouts/admin');
    }

    public function estado(string $id): void
    {
        $userId = $this->intParam($id);
        $currentUser = Auth::user();

        if ($currentUser !== null && $currentUser->id === $userId) {
            $this->flash('error', 'No puedes deshabilitar tu propia cuenta mientras estés en sesión.');
            $this->redirect('/admin/usuarios');
        }

        $userRepo = new UsuarioRepository($this->db());
        $target = $userRepo->findById($userId);

        if ($target === null) {
            $this->flash('error', 'Usuario no encontrado.');
            $this->redirect('/admin/usuarios');
        }

        $nuevoEstado = !$target->activo;
        $userRepo->setActivo($userId, $nuevoEstado);

        $this->flash(
            'success',
            sprintf('El usuario "%s" fue %s.', $target->nombre, $nuevoEstado ? 'activado' : 'deshabilitado')
        );
        $this->redirect('/admin/usuarios');
    }

    public function otorgar(string $id): void
    {
        $userId = $this->intParam($id);
        $currentUser = Auth::user();

        $db = $this->db();
        $userRepo = new UsuarioRepository($db);
        $adminRepo = new AdministradorRepository($db);

        $target = $userRepo->findById($userId);
        if ($target === null) {
            $this->flash('error', 'Usuario no encontrado.');
            $this->redirect('/admin/usuarios');
        }

        $existente = $adminRepo->findByUsuario($userId);
        if ($existente !== null) {
            $this->flash('info', 'El usuario ya cuenta con permisos de administración.');
            $this->redirect('/admin/usuarios');
        }

        $adminRepo->grant($userId, NivelAdmin::Admin, $currentUser?->id);

        $this->flash('success', sprintf('Se otorgaron permisos de Administrador a "%s".', $target->nombre));
        $this->redirect('/admin/usuarios');
    }

    public function revocar(string $id): void
    {
        $userId = $this->intParam($id);
        $currentUser = Auth::user();

        if ($currentUser !== null && $currentUser->id === $userId) {
            $this->flash('error', 'No puedes revocar tus propios permisos de administrador.');
            $this->redirect('/admin/usuarios');
        }

        $adminRepo = new AdministradorRepository($this->db());
        $existente = $adminRepo->findByUsuario($userId);

        if ($existente === null) {
            $this->flash('error', 'El usuario no es administrador.');
            $this->redirect('/admin/usuarios');
        }

        if ($existente->nivel === NivelAdmin::SuperAdmin) {
            $this->flash('error', 'No es posible revocar permisos a un Superadministrador.');
            $this->redirect('/admin/usuarios');
        }

        $adminRepo->revoke($userId);

        $this->flash('success', 'Permisos de administración revocados correctamente.');
        $this->redirect('/admin/usuarios');
    }
}

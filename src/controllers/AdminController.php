<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\HttpException;
use App\Core\Validator;
use App\Models\AdministradorRepository;
use App\Models\BecaRepository;
use App\Models\Catalogo;
use App\Models\Genero;
use App\Models\InscripcionRepository;
use App\Models\Modalidad;
use App\Models\ModuloRepository;
use App\Models\NivelAdmin;
use App\Models\NivelEstudios;
use App\Models\PerfilAlumno;
use App\Models\PerfilAlumnoRepository;
use App\Models\UsuarioRepository;

final class AdminController extends Controller
{
    private const TELEFONO = 'regex:/^\+?[0-9\s()\-]{7,20}$/';

    public function dashboard(): void
    {
        $db = $this->db();
        $userRepo = new UsuarioRepository($db);
        $modRepo = new ModuloRepository($db);
        $adminRepo = new AdministradorRepository($db);
        $inscRepo = new InscripcionRepository($db);
        $becaRepo = new BecaRepository($db);

        $totalUsuarios = $userRepo->countAll();
        $totalModulos = $modRepo->countActive();
        $totalAdmins = $adminRepo->countAll();
        $countsPorRol = $adminRepo->countByNivel();
        $inscripcionesStats = $inscRepo->countStats();
        $becasActivas = count($becaRepo->listActive());
        $recientes = $userRepo->paginateWithRole(1, 8);

        $this->render('admin/dashboard', [
            'title'              => 'Panel de Administración | Lógica Visual ABP',
            'activeNav'          => 'admin_dashboard',
            'totalUsuarios'      => $totalUsuarios,
            'totalModulos'       => $totalModulos,
            'totalAdmins'        => $totalAdmins,
            'countsPorRol'       => $countsPorRol,
            'inscripcionesStats' => $inscripcionesStats,
            'becasActivas'       => $becasActivas,
            'usuarios'           => $recientes,
        ], 'layouts/admin');
    }

    public function usuarios(): void
    {
        $page = max(1, $this->intParam((string) $this->request->query('pagina', '1')));
        $perPage = 25;

        $db = $this->db();
        $userRepo = new UsuarioRepository($db);
        $adminRepo = new AdministradorRepository($db);

        $total = $userRepo->countAll();
        $totalActivos = $userRepo->countActive();
        $countsPorRol = $adminRepo->countByNivel();
        $usuarios = $userRepo->paginateWithRole($page, $perPage);
        $totalPaginas = (int) ceil($total / $perPage);

        $this->render('admin/usuarios/index', [
            'title'        => 'Gestión de Usuarios y Permisos | Lógica Visual ABP',
            'activeNav'    => 'admin_usuarios',
            'usuarios'     => $usuarios,
            'pagina'       => $page,
            'totalPaginas' => max(1, $totalPaginas),
            'total'        => $total,
            'totalActivos' => $totalActivos,
            'countsPorRol' => $countsPorRol,
            'esSuperAdmin' => Auth::isSuperAdmin(),
        ], 'layouts/admin');
    }

    public function createUsuario(): void
    {
        $db = $this->db();
        $modulos = (new ModuloRepository($db))->listAll();

        $this->render('admin/usuarios/form', [
            'title'            => 'Añadir Nuevo Usuario | Panel Administrativo',
            'activeNav'        => 'admin_usuarios',
            'usuarioEditar'    => null,
            'adminEditar'      => null,
            'perfil'           => new PerfilAlumno(),
            'modulos'          => $modulos,
            'modulosInscritos' => [],
            'esSuperAdmin'     => Auth::isSuperAdmin(),
        ], 'layouts/admin');
    }

    public function storeUsuario(): void
    {
        $camposCuenta = ['nombre', 'email', 'password', 'password_confirmation', 'rol', 'activo', 'cargo', 'departamento'];
        $input = $this->request->only(array_merge($camposCuenta, PerfilAlumno::CAMPOS));
        $input['numero_control'] = strtoupper(trim($input['numero_control']));
        $input['email'] = trim($input['email']);
        $modulosSeleccionados = $this->parseModulosInput();

        [$errores, $perfilesRepo, $userRepo] = $this->validarFormularioUsuario($input, null);

        if ($errores !== []) {
            $this->backWithErrors('/admin/usuarios/nuevo', $errores, $input);
        }

        $db = $this->db();
        $adminRepo = new AdministradorRepository($db);
        $inscRepo = new InscripcionRepository($db);

        $activo = ($input['activo'] ?? '1') === '1';
        $hash = password_hash($input['password'], PASSWORD_DEFAULT);
        $rol = $this->normalizarRol($input['rol'] ?? 'estudiante');

        $db->beginTransaction();
        try {
            $nuevoId = $userRepo->create($input['nombre'], $input['email'], $hash, $activo);

            $datosPerfil = array_intersect_key($input, array_flip(PerfilAlumno::CAMPOS));
            $perfilesRepo->guardar($nuevoId, $datosPerfil);

            if ($rol !== 'estudiante') {
                $nivel = NivelAdmin::from($rol);
                $adminRepo->grant(
                    $nuevoId,
                    $nivel,
                    Auth::user()?->id,
                    $input['cargo'] ?: null,
                    $input['departamento'] ?: ($input['carrera'] ?: null),
                );
            }

            foreach ($modulosSeleccionados as $idModulo) {
                $inscRepo->inscribir($nuevoId, $idModulo);
            }

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }

        $etiquetaRol = $rol === 'estudiante' ? 'Estudiante' : NivelAdmin::from($rol)->etiqueta();
        $this->flash(
            'success',
            sprintf('Usuario "%s" (%s) creado exitosamente con el rol de %s.', $input['nombre'], $input['email'], $etiquetaRol)
        );
        $this->redirect('/admin/usuarios');
    }

    public function editUsuario(string $id): void
    {
        $userId = $this->intParam($id);
        $db = $this->db();

        $userRepo = new UsuarioRepository($db);
        $usuarioEditar = $userRepo->findById($userId);

        if ($usuarioEditar === null) {
            throw new HttpException(404, 'El usuario solicitado no existe.');
        }

        $adminEditar = (new AdministradorRepository($db))->findByUsuario($userId);
        $perfil = (new PerfilAlumnoRepository($db))->findByUsuario($userId);
        $modulos = (new ModuloRepository($db))->listAll();
        $modulosInscritos = (new InscripcionRepository($db))->listModuloIdsForUsuario($userId);

        $this->render('admin/usuarios/form', [
            'title'            => 'Editar Usuario: ' . $usuarioEditar->nombre . ' | Panel Administrativo',
            'activeNav'        => 'admin_usuarios',
            'usuarioEditar'    => $usuarioEditar,
            'adminEditar'      => $adminEditar,
            'perfil'           => $perfil,
            'modulos'          => $modulos,
            'modulosInscritos' => $modulosInscritos,
            'esSuperAdmin'     => Auth::isSuperAdmin(),
        ], 'layouts/admin');
    }

    public function updateUsuario(string $id): void
    {
        $userId = $this->intParam($id);
        $db = $this->db();
        $userRepo = new UsuarioRepository($db);
        $usuarioEditar = $userRepo->findById($userId);

        if ($usuarioEditar === null) {
            throw new HttpException(404, 'El usuario solicitado no existe.');
        }

        $camposCuenta = ['nombre', 'email', 'password', 'password_confirmation', 'rol', 'activo', 'cargo', 'departamento'];
        $input = $this->request->only(array_merge($camposCuenta, PerfilAlumno::CAMPOS));
        $input['numero_control'] = strtoupper(trim($input['numero_control']));
        $input['email'] = trim($input['email']);
        $modulosSeleccionados = $this->parseModulosInput();

        [$errores, $perfilesRepo] = $this->validarFormularioUsuario($input, $userId);

        if ($errores !== []) {
            $this->backWithErrors('/admin/usuarios/' . $userId . '/editar', $errores, $input);
        }

        $adminRepo = new AdministradorRepository($db);
        $inscRepo = new InscripcionRepository($db);
        $currentUser = Auth::user();

        $activo = ($input['activo'] ?? '1') === '1';
        if ($currentUser !== null && $currentUser->id === $userId && !$activo) {
            $activo = true;
        }

        $rol = $this->normalizarRol($input['rol'] ?? 'estudiante');

        $db->beginTransaction();
        try {
            $userRepo->updateDatosCuenta($userId, $input['nombre'], $input['email'], $activo);

            if ($input['password'] !== '') {
                $userRepo->updatePasswordHash($userId, password_hash($input['password'], PASSWORD_DEFAULT));
            }

            $datosPerfil = array_intersect_key($input, array_flip(PerfilAlumno::CAMPOS));
            $perfilesRepo->guardar($userId, $datosPerfil);

            // Actualizar rol si corresponde (protegiendo que el usuario en sesión no se quite su propio superadmin)
            $esPropioSuperAdmin = $currentUser !== null && $currentUser->id === $userId && Auth::isSuperAdmin();
            if ($esPropioSuperAdmin && $rol !== 'superadmin') {
                $rol = 'superadmin';
            }

            if ($rol === 'estudiante') {
                $adminRepo->revoke($userId, Auth::isSuperAdmin());
            } else {
                $nivel = NivelAdmin::from($rol);
                $adminRepo->grant(
                    $userId,
                    $nivel,
                    $currentUser?->id,
                    $input['cargo'] ?: null,
                    $input['departamento'] ?: ($input['carrera'] ?: null),
                );
            }

            // Sincronizar módulos inscritos seleccionados
            foreach ($modulosSeleccionados as $idModulo) {
                $inscRepo->inscribir($userId, $idModulo);
            }

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }

        $this->flash('success', sprintf('Los datos y permisos de "%s" se actualizaron correctamente.', $input['nombre']));
        $this->redirect('/admin/usuarios');
    }

    public function cambiarRol(string $id): void
    {
        $userId = $this->intParam($id);
        $currentUser = Auth::user();
        $nuevoRol = $this->normalizarRol((string) $this->request->input('rol', 'estudiante'));

        $db = $this->db();
        $userRepo = new UsuarioRepository($db);
        $adminRepo = new AdministradorRepository($db);

        $target = $userRepo->findById($userId);
        if ($target === null) {
            $this->flash('error', 'Usuario no encontrado.');
            $this->redirect('/admin/usuarios');
        }

        if ($currentUser !== null && $currentUser->id === $userId && $nuevoRol !== 'superadmin' && Auth::isSuperAdmin()) {
            $this->flash('error', 'No puedes degradar tu propio rol de Superadministrador mientras estás en sesión.');
            $this->redirect('/admin/usuarios');
        }

        if ($nuevoRol === 'superadmin' && !Auth::isSuperAdmin()) {
            $this->flash('error', 'Solo un Superadministrador puede otorgar el nivel de Superadministrador.');
            $this->redirect('/admin/usuarios');
        }

        if ($nuevoRol === 'estudiante') {
            $adminRepo->revoke($userId, Auth::isSuperAdmin());
            $this->flash('success', sprintf('El rol de "%s" fue cambiado a Estudiante.', $target->nombre));
        } else {
            $nivel = NivelAdmin::from($nuevoRol);
            $adminRepo->grant($userId, $nivel, $currentUser?->id);
            $this->flash('success', sprintf('Se asignó el rol de %s a "%s".', $nivel->etiqueta(), $target->nombre));
        }

        $this->redirect('/admin/usuarios');
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

        $nivelSolicitado = (string) $this->request->input('nivel', 'admin');
        $nivel = NivelAdmin::tryFrom($nivelSolicitado) ?? NivelAdmin::Admin;

        $adminRepo->grant($userId, $nivel, $currentUser?->id);

        $this->flash('success', sprintf('Se otorgaron permisos de %s a "%s".', $nivel->etiqueta(), $target->nombre));
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
            $this->flash('error', 'El usuario ya tiene rol de Estudiante.');
            $this->redirect('/admin/usuarios');
        }

        if ($existente->nivel === NivelAdmin::SuperAdmin && !Auth::isSuperAdmin()) {
            $this->flash('error', 'No tienes permisos para revocar a un Superadministrador.');
            $this->redirect('/admin/usuarios');
        }

        $adminRepo->revoke($userId, Auth::isSuperAdmin());

        $this->flash('success', 'Permisos administrativos revocados correctamente (ahora es Estudiante).');
        $this->redirect('/admin/usuarios');
    }

    public function eliminarUsuario(string $id): void
    {
        $userId = $this->intParam($id);
        $currentUser = Auth::user();

        if ($currentUser !== null && $currentUser->id === $userId) {
            $this->flash('error', 'No puedes eliminar tu propia cuenta mientras estás en sesión.');
            $this->redirect('/admin/usuarios');
        }

        $db = $this->db();
        $userRepo = new UsuarioRepository($db);
        $adminRepo = new AdministradorRepository($db);

        $target = $userRepo->findById($userId);
        if ($target === null) {
            $this->flash('error', 'Usuario no encontrado.');
            $this->redirect('/admin/usuarios');
        }

        $adminTarget = $adminRepo->findByUsuario($userId);
        if ($adminTarget !== null && $adminTarget->nivel === NivelAdmin::SuperAdmin && !Auth::isSuperAdmin()) {
            $this->flash('error', 'Solo un Superadministrador puede eliminar otra cuenta de Superadministrador.');
            $this->redirect('/admin/usuarios');
        }

        $userRepo->delete($userId);

        $this->flash('success', sprintf('La cuenta de "%s" (%s) fue eliminada del sistema.', $target->nombre, $target->email));
        $this->redirect('/admin/usuarios');
    }

    /**
     * @param array<string, string> $input
     * @return array{0: array<string, string>, 1: PerfilAlumnoRepository, 2: UsuarioRepository}
     */
    private function validarFormularioUsuario(array $input, ?int $exceptUserId): array
    {
        $esEdicion = $exceptUserId !== null;
        $paises = implode(',', Catalogo::PAISES);

        $rules = [
            'nombre'                       => ['required', 'min:3', 'max:100', 'letters'],
            'email'                        => ['required', 'email', 'max:150'],
            'rol'                          => ['required', 'in:estudiante,jefe_curso,admin,superadmin'],
            'numero_control'               => ['regex:/^[A-Z0-9\-]{4,20}$/'],
            'telefono'                     => [self::TELEFONO],
            'pais_residencia'              => ['in:' . $paises],
            'nacionalidad'                 => ['letters'],
            'fecha_nacimiento'             => ['date:12,100'],
            'genero'                       => ['in:' . Catalogo::valores(Genero::class)],
            'email_alterno'                => ['email', 'max:150'],
            'ciudad'                       => ['letters', 'max:80'],
            'estado_residencia'            => ['letters', 'max:80'],
            'codigo_postal'                => ['regex:/^[0-9]{5}$/'],
            'contacto_emergencia_nombre'   => ['letters', 'max:100'],
            'contacto_emergencia_telefono' => [self::TELEFONO],
            'institucion'                  => ['max:150'],
            'carrera'                      => ['max:120'],
            'semestre'                     => ['between:1,14'],
            'modalidad'                    => ['in:' . Catalogo::valores(Modalidad::class)],
            'nivel_estudios'               => ['in:' . Catalogo::valores(NivelEstudios::class)],
            'area_estudios'                => ['max:120'],
            'nota_media'                   => ['decimal:0,100'],
            'cargo'                        => ['max:100'],
            'departamento'                 => ['max:120'],
        ];

        if (!$esEdicion || ($input['password'] ?? '') !== '') {
            $rules['password'] = ['required', 'min:8', 'maxbytes:72', 'password'];
            $rules['password_confirmation'] = ['required', 'same:password'];
        }

        $validator = new Validator($input, $rules, [
            'nombre'                       => 'El nombre completo',
            'email'                        => 'El correo electrónico',
            'password'                     => 'La contraseña',
            'password_confirmation'        => 'La confirmación de contraseña',
            'rol'                          => 'El rol del usuario',
            'numero_control'               => 'El número de control o matrícula',
            'telefono'                     => 'El teléfono',
            'pais_residencia'              => 'El país de residencia',
            'nacionalidad'                 => 'La nacionalidad',
            'fecha_nacimiento'             => 'La fecha de nacimiento',
            'genero'                       => 'El género',
            'email_alterno'                => 'El correo alterno',
            'ciudad'                       => 'La ciudad',
            'estado_residencia'            => 'El estado',
            'codigo_postal'                => 'El código postal',
            'contacto_emergencia_nombre'   => 'El contacto de emergencia',
            'contacto_emergencia_telefono' => 'El teléfono de emergencia',
            'institucion'                  => 'La institución',
            'carrera'                      => 'La carrera o programa',
            'semestre'                     => 'El semestre',
            'modalidad'                    => 'La modalidad',
            'nivel_estudios'               => 'El nivel de estudios',
            'area_estudios'                => 'El área de estudios',
            'nota_media'                   => 'El promedio / nota media',
            'cargo'                        => 'El cargo administrativo/académico',
            'departamento'                 => 'El departamento o coordinación',
        ]);

        $errores = $validator->errors();
        $db = $this->db();
        $userRepo = new UsuarioRepository($db);
        $perfiles = new PerfilAlumnoRepository($db);

        if (!isset($errores['email']) && $userRepo->emailExists($input['email'], $exceptUserId ?? 0)) {
            $errores['email'] = 'Este correo electrónico ya está registrado en otra cuenta.';
        }

        if ($input['numero_control'] !== '' && !isset($errores['numero_control']) && $perfiles->numeroControlExists($input['numero_control'], $exceptUserId ?? 0)) {
            $errores['numero_control'] = 'Este número de control / matrícula ya está asignado a otro usuario.';
        }

        if (($input['rol'] ?? '') === 'superadmin' && !Auth::isSuperAdmin()) {
            $errores['rol'] = 'Solo un Superadministrador puede asignar el rol de Superadministrador.';
        }

        return [$errores, $perfiles, $userRepo];
    }

    private function normalizarRol(string $rol): string
    {
        return in_array($rol, ['estudiante', 'jefe_curso', 'admin', 'superadmin'], true) ? $rol : 'estudiante';
    }

    /** @return list<int> */
    private function parseModulosInput(): array
    {
        $raw = $_POST['modulos_inscritos'] ?? [];
        if (!is_array($raw)) {
            return [];
        }

        $ids = [];
        foreach ($raw as $v) {
            if (is_scalar($v) && ctype_digit((string) $v)) {
                $ids[] = (int) $v;
            }
        }

        return array_values(array_unique($ids));
    }
}

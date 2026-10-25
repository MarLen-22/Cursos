<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Url;
use App\Core\Validator;
use App\Models\PerfilAlumnoRepository;
use App\Models\UsuarioRepository;

final class AuthController extends Controller
{
    // Dummy bcrypt hash so verification takes the same CPU time when an email does not exist
    private const DUMMY_HASH = '$2y$10$q1oeifqgNkMd9lhdReGnmeSIEj6Oe/rxBV2gAIa/LTQQlio6e31RC';
    private const MAX_ATTEMPTS = 5;
    private const LOCK_MINUTES = 15;

    public function showLogin(): void
    {
        $this->render('auth/login', [
            'title'     => 'Iniciar sesión | Lógica Visual ABP',
            'activeNav' => 'login',
            'next'      => $this->request->query('next'),
        ]);
    }

    public function login(): void
    {
        $input = $this->request->only(['email', 'password', 'next']);

        $validator = new Validator($input, [
            'email'    => ['required', 'email', 'max:150'],
            'password' => ['required', 'maxbytes:72'],
        ], [
            'email'    => 'El correo electrónico',
            'password' => 'La contraseña',
        ]);

        if (!$validator->passes()) {
            $this->backWithErrors('/login', $validator->errors(), ['email' => $input['email'], 'next' => $input['next']]);
        }

        $repo = new UsuarioRepository($this->db());
        $usuario = $repo->findByEmail(strtolower($input['email']));

        if ($usuario === null) {
            password_verify($input['password'], self::DUMMY_HASH);
            $this->backWithErrors('/login', [
                'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
            ], ['email' => $input['email'], 'next' => $input['next']]);
        }

        if ($usuario->bloqueado) {
            $this->backWithErrors('/login', [
                'email' => 'Cuenta bloqueada temporalmente por múltiples intentos fallidos. Inténtalo más tarde.',
            ], ['email' => $input['email']]);
        }

        if (!$usuario->activo) {
            $this->backWithErrors('/login', [
                'email' => 'Esta cuenta se encuentra deshabilitada. Contacta al administrador.',
            ], ['email' => $input['email']]);
        }

        if (!password_verify($input['password'], $usuario->passwordHash)) {
            $repo->registerFailedLogin($usuario->id, self::MAX_ATTEMPTS, self::LOCK_MINUTES);
            $this->backWithErrors('/login', [
                'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
            ], ['email' => $input['email'], 'next' => $input['next']]);
        }

        $repo->registerSuccessfulLogin($usuario->id);

        if (password_needs_rehash($usuario->passwordHash, PASSWORD_DEFAULT)) {
            $repo->updatePasswordHash($usuario->id, password_hash($input['password'], PASSWORD_DEFAULT));
        }

        Auth::login($usuario);

        $this->flash('success', '¡Bienvenido de nuevo, ' . $usuario->nombre . '!');

        $next = Url::safeRedirectPath($input['next'], Auth::isAdmin() ? '/admin' : '/mi-aprendizaje');
        $this->redirect($next);
    }

    public function showRegistro(): void
    {
        $this->render('auth/registro', [
            'title'     => 'Crear cuenta | Lógica Visual ABP',
            'activeNav' => 'registro',
        ]);
    }

    public function registro(): void
    {
        $input = $this->request->only(['nombre', 'numero_control', 'email', 'password', 'password_confirmation', 'terminos']);
        $input['numero_control'] = strtoupper($input['numero_control']);
        $old = [
            'nombre'         => $input['nombre'],
            'numero_control' => $input['numero_control'],
            'email'          => $input['email'],
        ];

        $validator = new Validator($input, [
            'nombre'                => ['required', 'min:3', 'max:100', 'letters'],
            'numero_control'        => ['required', 'regex:/^[A-Z0-9]{6,20}$/'],
            'email'                 => ['required', 'email', 'max:150', 'tecvictoria_email'],
            'password'              => ['required', 'min:8', 'maxbytes:72', 'password'],
            'password_confirmation' => ['required', 'same:password'],
            'terminos'              => ['required'],
        ], [
            'nombre'                => 'El nombre completo',
            'numero_control'        => 'El número de control',
            'email'                 => 'El correo electrónico',
            'password'              => 'La contraseña',
            'password_confirmation' => 'La confirmación de la contraseña',
            'terminos'              => 'La aceptación del aviso de privacidad',
        ]);

        if (!$validator->passes()) {
            $this->backWithErrors('/registro', $validator->errors(), $old);
        }

        $db = $this->db();
        $repo = new UsuarioRepository($db);
        $perfiles = new PerfilAlumnoRepository($db);
        $email = strtolower($input['email']);

        $errores = [];
        if ($repo->emailExists($email)) {
            $errores['email'] = 'El correo electrónico ya se encuentra registrado.';
        }
        if ($perfiles->numeroControlExists($input['numero_control'])) {
            $errores['numero_control'] = 'El número de control ya está asociado a otra cuenta.';
        }
        if ($errores !== []) {
            $this->backWithErrors('/registro', $errores, $old);
        }

        $hash = password_hash($input['password'], PASSWORD_DEFAULT);

        $db->beginTransaction();
        try {
            $id = $repo->create($input['nombre'], $email, $hash);
            $perfiles->guardar($id, ['numero_control' => $input['numero_control']]);
            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }

        $usuario = $repo->findById($id);
        if ($usuario === null) {
            $this->backWithErrors('/registro', ['email' => 'Ocurrió un error al crear la cuenta.']);
        }

        Auth::login($usuario);

        $this->flash('success', '¡Cuenta creada con éxito! Completa tu perfil para personalizar tu experiencia.');
        $this->redirect('/mi-cuenta');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->flash('info', 'Has cerrado sesión correctamente.');
        $this->redirect('/');
    }
}

<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Url;
use App\Core\Validator;
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

        $next = Url::safeRedirectPath($input['next'], Auth::isAdmin() ? '/admin' : '/');
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
        $input = $this->request->only(['nombre', 'email', 'password', 'password_confirmation']);

        $validator = new Validator($input, [
            'nombre'                => ['required', 'min:3', 'max:100'],
            'email'                 => ['required', 'email', 'max:150'],
            'password'              => ['required', 'min:8', 'maxbytes:72', 'password'],
            'password_confirmation' => ['required', 'same:password'],
        ], [
            'nombre'                => 'El nombre completo',
            'email'                 => 'El correo electrónico',
            'password'              => 'La contraseña',
            'password_confirmation' => 'La confirmación de la contraseña',
        ]);

        if (!$validator->passes()) {
            $this->backWithErrors('/registro', $validator->errors(), [
                'nombre' => $input['nombre'],
                'email'  => $input['email'],
            ]);
        }

        $repo = new UsuarioRepository($this->db());
        $email = strtolower($input['email']);

        if ($repo->emailExists($email)) {
            $this->backWithErrors('/registro', [
                'email' => 'El correo electrónico ya se encuentra registrado.',
            ], [
                'nombre' => $input['nombre'],
                'email'  => $input['email'],
            ]);
        }

        $hash = password_hash($input['password'], PASSWORD_DEFAULT);
        $id = $repo->create($input['nombre'], $email, $hash);

        $usuario = $repo->findById($id);
        if ($usuario === null) {
            $this->backWithErrors('/registro', ['email' => 'Ocurrió un error al crear la cuenta.']);
        }

        Auth::login($usuario);

        $this->flash('success', '¡Cuenta creada con éxito! Bienvenido a Lógica Visual ABP.');
        $this->redirect('/');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->flash('info', 'Has cerrado sesión correctamente.');
        $this->redirect('/');
    }
}

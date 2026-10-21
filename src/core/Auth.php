<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\Administrador;
use App\Models\AdministradorRepository;
use App\Models\NivelAdmin;
use App\Models\Usuario;
use App\Models\UsuarioRepository;

/**
 * Authentication state for the current request. The user and their
 * admin role are re-read from the database on every request so that
 * deactivations or revoked permissions take effect immediately.
 */
final class Auth
{
    private const SESSION_KEY = 'uid';

    private static bool $resolved = false;
    private static ?Usuario $user = null;
    private static ?Administrador $admin = null;

    public static function user(): ?Usuario
    {
        self::resolve();

        return self::$user;
    }

    public static function admin(): ?Administrador
    {
        self::resolve();

        return self::$admin;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function isAdmin(): bool
    {
        return self::admin() !== null;
    }

    public static function isSuperAdmin(): bool
    {
        return self::admin()?->nivel === NivelAdmin::SuperAdmin;
    }

    public static function login(Usuario $user): void
    {
        Session::regenerate();
        Session::set(self::SESSION_KEY, $user->id);
        Csrf::rotate();
        self::$resolved = false;
    }

    public static function logout(): void
    {
        Session::destroy();
        self::$user = null;
        self::$admin = null;
        self::$resolved = true;
    }

    private static function resolve(): void
    {
        if (self::$resolved) {
            return;
        }
        self::$resolved = true;

        $id = Session::get(self::SESSION_KEY);
        if (!is_int($id)) {
            return;
        }

        $db = Database::connection();
        $user = (new UsuarioRepository($db))->findById($id);

        if ($user === null || !$user->activo) {
            self::logout();

            return;
        }

        self::$user = $user;
        self::$admin = (new AdministradorRepository($db))->findByUsuario($user->id);
    }
}

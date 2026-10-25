<?php

declare(strict_types=1);

namespace App\Core;

final class Middleware
{
    /** @param list<string> $names */
    public static function run(array $names, Request $request): void
    {
        foreach ($names as $name) {
            match ($name) {
                'auth'       => self::requireAuth($request),
                'guest'      => self::requireGuest(),
                'admin'      => self::requireAdmin($request, false),
                'superadmin' => self::requireAdmin($request, true),
                default      => throw new \LogicException("Middleware desconocido: {$name}"),
            };
        }
    }

    private static function requireAuth(Request $request): void
    {
        if (Auth::check()) {
            return;
        }

        Session::flash('error', 'Inicia sesión para continuar.');
        $next = $request->method === 'GET' ? '?next=' . rawurlencode($request->path) : '';
        Response::redirect(Url::to('/login') . $next);
    }

    private static function requireGuest(): void
    {
        if (Auth::check()) {
            Response::redirect(Url::to(Auth::isAdmin() ? '/admin' : '/mi-aprendizaje'));
        }
    }

    private static function requireAdmin(Request $request, bool $superadmin): void
    {
        self::requireAuth($request);

        $allowed = $superadmin ? Auth::isSuperAdmin() : Auth::isAdmin();
        if (!$allowed) {
            throw new HttpException(403);
        }
    }
}

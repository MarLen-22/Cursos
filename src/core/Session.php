<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Hardened, lazily-started session with idle timeout and one-request
 * flash storage (values flashed now are readable on the next request).
 */
final class Session
{
    private static bool $started = false;
    private static bool $destroyed = false;
    private static string $name = 'lv_session';
    private static string $cookiePath = '/';
    private static bool $secure = false;
    private static int $idleLifetime = 7200;

    /** @var array<string, mixed> */
    private static array $flash = [];

    public static function configure(string $name, string $cookiePath, bool $secure, int $idleLifetime): void
    {
        self::$name = $name;
        self::$cookiePath = $cookiePath;
        self::$secure = $secure;
        self::$idleLifetime = max(300, $idleLifetime);
    }

    public static function start(): void
    {
        if (self::$started) {
            return;
        }
        if (headers_sent($file, $line)) {
            throw new \RuntimeException("No se puede iniciar la sesión: salida enviada en {$file}:{$line}.");
        }

        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_trans_sid', '0');
        ini_set('session.gc_maxlifetime', (string) self::$idleLifetime);

        session_name(self::$name);
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => self::$cookiePath,
            'secure'   => self::$secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        if (self::$destroyed) {
            session_id(session_create_id());
            self::$destroyed = false;
        }

        if (!session_start()) {
            throw new \RuntimeException('No fue posible iniciar la sesión.');
        }
        self::$started = true;

        $now = time();
        $last = $_SESSION['_last_activity'] ?? null;
        if (is_int($last) && ($now - $last) > self::$idleLifetime) {
            $_SESSION = [];
            session_regenerate_id(true);
        }
        $_SESSION['_last_activity'] = $now;

        self::$flash = is_array($_SESSION['_flash'] ?? null) ? $_SESSION['_flash'] : [];
        unset($_SESSION['_flash']);
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();

        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function forget(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function flash(string $key, mixed $value): void
    {
        self::start();
        $_SESSION['_flash'][$key] = $value;
    }

    public static function getFlash(string $key, mixed $default = null): mixed
    {
        self::start();

        return self::$flash[$key] ?? $default;
    }

    public static function regenerate(): void
    {
        self::start();
        session_regenerate_id(true);
    }

    public static function destroy(): void
    {
        self::start();
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            setcookie(self::$name, '', [
                'expires'  => time() - 3600,
                'path'     => self::$cookiePath,
                'secure'   => self::$secure,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        }

        session_destroy();
        self::$started = false;
        self::$destroyed = true;
        self::$flash = [];
    }
}

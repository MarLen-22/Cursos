<?php

declare(strict_types=1);

namespace App\Core;

final class Url
{
    private static string $basePath = '';
    private static string $publicPath = '';

    public static function configure(string $basePath, string $publicPath): void
    {
        self::$basePath = $basePath;
        self::$publicPath = $publicPath;
    }

    public static function basePath(): string
    {
        return self::$basePath;
    }

    /**
     * Resolves the URL prefix for both access modes:
     *  - http://localhost:8080/Cursos/public/...  (direct)
     *  - http://localhost:8080/Cursos/...         (via root .htaccess rewrite)
     *  - https://app.azurewebsites.net/...        (document root = public)
     *
     * @param array<string, mixed> $server
     */
    public static function detectBasePath(array $server, string $configured): string
    {
        if ($configured !== '') {
            return '/' . trim($configured, '/');
        }

        $scriptDir = rtrim(str_replace('\\', '/', dirname((string) ($server['SCRIPT_NAME'] ?? '/'))), '/');
        $uriPath = parse_url((string) ($server['REQUEST_URI'] ?? '/'), PHP_URL_PATH);
        $requestPath = is_string($uriPath) ? rawurldecode($uriPath) : '/';

        if ($scriptDir === '' || $requestPath === $scriptDir || str_starts_with($requestPath, $scriptDir . '/')) {
            return $scriptDir;
        }

        if (str_ends_with($scriptDir, '/public')) {
            return substr($scriptDir, 0, -strlen('/public'));
        }

        return $scriptDir;
    }

    public static function to(string $path = '/'): string
    {
        $path = '/' . ltrim($path, '/');

        return self::$basePath . ($path === '/' ? '/' : $path);
    }

    /** Public asset URL with mtime-based cache busting. */
    public static function asset(string $path): string
    {
        $path = ltrim($path, '/');
        $file = self::$publicPath . '/' . $path;
        $version = is_file($file) ? (string) filemtime($file) : '1';

        return self::to($path) . '?v=' . $version;
    }

    /** Accepts only same-site relative paths to prevent open redirects. */
    public static function safeRedirectPath(string $path, string $fallback = '/'): string
    {
        if ($path === '' || $path[0] !== '/' || str_starts_with($path, '//') || str_contains($path, '\\')
            || preg_match('/[\x00-\x1F]/', $path)) {
            return $fallback;
        }

        return $path;
    }
}

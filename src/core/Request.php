<?php

declare(strict_types=1);

namespace App\Core;

final class Request
{
    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $body
     * @param array<string, mixed> $server
     */
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        private readonly array $query,
        private readonly array $body,
        private readonly array $server,
    ) {
    }

    public static function fromGlobals(string $basePath): self
    {
        $method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
        $uriPath = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);
        $path = rawurldecode(is_string($uriPath) ? $uriPath : '/');

        if ($basePath !== '' && ($path === $basePath || str_starts_with($path, $basePath . '/'))) {
            $path = substr($path, strlen($basePath));
        }

        $path = '/' . trim($path, '/');
        if ($path === '/index.php') {
            $path = '/';
        }

        return new self($method, $path, $_GET, $_POST, $_SERVER);
    }

    /** Returns a trimmed scalar POST value; arrays are rejected to avoid type juggling. */
    public function input(string $key, string $default = ''): string
    {
        return self::scalar($this->body[$key] ?? null, $default);
    }

    public function query(string $key, string $default = ''): string
    {
        return self::scalar($this->query[$key] ?? null, $default);
    }

    /**
     * @param list<string> $keys
     * @return array<string, string>
     */
    public function only(array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->input($key);
        }

        return $result;
    }

    public function header(string $name): string
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));

        return self::scalar($this->server[$key] ?? null, '');
    }

    public function isSecure(): bool
    {
        return self::detectHttps($this->server);
    }

    /** @param array<string, mixed> $server */
    public static function detectHttps(array $server): bool
    {
        $https = strtolower((string) ($server['HTTPS'] ?? ''));
        if ($https !== '' && $https !== 'off') {
            return true;
        }

        // Azure App Service terminates TLS at its front end and forwards these headers.
        return strtolower((string) ($server['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https'
            || isset($server['HTTP_X_ARR_SSL']);
    }

    private static function scalar(mixed $value, string $default): string
    {
        if (is_string($value)) {
            return trim($value);
        }
        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        return $default;
    }
}

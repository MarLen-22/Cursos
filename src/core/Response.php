<?php

declare(strict_types=1);

namespace App\Core;

final class Response
{
    public static function redirect(string $url, int $status = 302): never
    {
        header('Location: ' . $url, true, $status);
        exit;
    }

    public static function sendSecurityHeaders(bool $secure): void
    {
        if (headers_sent()) {
            return;
        }

        header_remove('X-Powered-By');
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
        header(
            "Content-Security-Policy: default-src 'self'; "
            . "script-src 'self' 'unsafe-inline'; "
            . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; "
            . "font-src 'self' https://fonts.gstatic.com; "
            . "img-src 'self' data: https://i.ytimg.com https://img.youtube.com; "
            . "frame-src 'self' blob: https://www.youtube.com https://www.youtube-nocookie.com; "
            . "form-action 'self'; frame-ancestors 'self'; base-uri 'self'; object-src 'self'"
        );

        if ($secure) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
    }
}

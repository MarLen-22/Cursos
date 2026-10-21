<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\HttpException;

final class AssetController extends Controller
{
    private const MIME_TYPES = [
        'css' => 'text/css; charset=UTF-8',
        'js'  => 'application/javascript; charset=UTF-8',
        'map' => 'application/json; charset=UTF-8',
    ];

    public function bootstrap(string $path): void
    {
        // Prevent path traversal
        if (str_contains($path, '..') || str_contains($path, '\\') || preg_match('/[\x00-\x1F]/', $path)) {
            throw new HttpException(404);
        }

        $baseDir = realpath(dirname(__DIR__, 2) . '/BootStrap');
        if ($baseDir === false) {
            throw new HttpException(404);
        }

        $filePath = realpath($baseDir . '/' . $path);
        if ($filePath === false || !str_starts_with($filePath, $baseDir) || !is_file($filePath)) {
            throw new HttpException(404);
        }

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mime = self::MIME_TYPES[$ext] ?? 'application/octet-stream';

        $mtime = filemtime($filePath);
        $etag = sprintf('"%x-%x"', $mtime, filesize($filePath));

        $ifNoneMatch = $this->request->header('If-None-Match');
        if ($ifNoneMatch === $etag) {
            http_response_code(304);
            exit;
        }

        header('Content-Type: ' . $mime);
        header('ETag: ' . $etag);
        header('Cache-Control: public, max-age=2592000, immutable');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT');

        readfile($filePath);
        exit;
    }
}

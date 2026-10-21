<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    private static string $viewsPath = '';

    public static function configure(string $viewsPath): void
    {
        self::$viewsPath = $viewsPath;
    }

    /** @param array<string, mixed> $data */
    public static function render(string $template, array $data = [], ?string $layout = 'layouts/main'): string
    {
        $content = self::renderFile($template, $data);

        if ($layout === null) {
            return $content;
        }

        return self::renderFile($layout, ['content' => $content] + $data);
    }

    /** @param array<string, mixed> $data */
    public static function partial(string $template, array $data = []): string
    {
        return self::renderFile($template, $data);
    }

    /** @param array<string, mixed> $__data */
    private static function renderFile(string $__template, array $__data): string
    {
        if (!preg_match('#^[a-z0-9_]+(?:/[a-z0-9_]+)*$#', $__template)) {
            throw new \InvalidArgumentException("Nombre de vista inválido: {$__template}");
        }

        $__file = self::$viewsPath . '/' . $__template . '.php';
        if (!is_file($__file)) {
            throw new \RuntimeException("Vista no encontrada: {$__template}");
        }

        extract($__data, EXTR_SKIP);
        ob_start();

        try {
            require $__file;
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }

        return (string) ob_get_clean();
    }
}

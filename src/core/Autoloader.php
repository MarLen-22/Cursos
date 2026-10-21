<?php

declare(strict_types=1);

namespace App\Core;

/**
 * PSR-4-style autoloader. Namespace segment after "App\" maps to a
 * lowercase directory (Linux/Azure file systems are case-sensitive).
 */
final class Autoloader
{
    private const NAMESPACE_MAP = [
        'Core'        => 'core',
        'Controllers' => 'controllers',
        'Models'      => 'models',
    ];

    public static function register(string $srcPath): void
    {
        spl_autoload_register(static function (string $class) use ($srcPath): void {
            if (!str_starts_with($class, 'App\\')) {
                return;
            }

            $parts = explode('\\', substr($class, 4));
            $root = array_shift($parts);

            if ($root === null || !isset(self::NAMESPACE_MAP[$root]) || $parts === []) {
                return;
            }

            $file = $srcPath . '/' . self::NAMESPACE_MAP[$root] . '/' . implode('/', $parts) . '.php';

            if (is_file($file)) {
                require $file;
            }
        });
    }
}

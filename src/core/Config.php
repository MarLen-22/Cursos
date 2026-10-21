<?php

declare(strict_types=1);

namespace App\Core;

final class Config
{
    private const FILES = ['app', 'database'];

    /** @var array<string, mixed> */
    private static array $items = [];

    public static function load(string $configPath): void
    {
        foreach (self::FILES as $name) {
            $file = $configPath . '/' . $name . '.php';
            if (!is_file($file)) {
                throw new \RuntimeException("Archivo de configuración faltante: {$name}.php");
            }

            $values = require $file;
            if (!is_array($values)) {
                throw new \RuntimeException("El archivo de configuración {$name}.php debe retornar un arreglo.");
            }

            self::$items[$name] = $values;
        }
    }

    /** Dot-notation access, e.g. Config::get('app.session.name'). */
    public static function get(string $key, mixed $default = null): mixed
    {
        $value = self::$items;

        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }
}

<?php

declare(strict_types=1);

/**
 * Front Controller · Lógica Visual ABP
 * Punto de entrada único para la aplicación web.
 */

$rootPath = dirname(__DIR__);

require_once $rootPath . '/src/core/Autoloader.php';
\App\Core\Autoloader::register($rootPath . '/src');

\App\Core\App::create($rootPath)->run();

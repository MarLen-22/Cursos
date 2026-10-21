<?php

declare(strict_types=1);

use App\Core\Env;

return [
    'name'      => Env::get('APP_NAME', 'Lógica Visual ABP'),
    'env'       => Env::get('APP_ENV', 'production'),
    'debug'     => Env::bool('APP_DEBUG', false),
    // Leave empty for auto-detection (/Cursos, /Cursos/public or domain root on Azure).
    'base_path' => Env::get('APP_BASE_PATH', ''),
    'timezone'  => Env::get('APP_TIMEZONE', 'America/Monterrey'),
    'log_path'  => Env::get('LOG_PATH', ''),
    'session'   => [
        'name'          => 'lv_session',
        'idle_lifetime' => Env::int('SESSION_IDLE_LIFETIME', 7200),
    ],
];

<?php

declare(strict_types=1);

use App\Core\Env;

/*
 * Local (XAMPP):  DB_HOST=127.0.0.1  DB_PORT=3306  DB_DATABASE=cursos
 * Azure MySQL:    DB_HOST=<servidor>.mysql.database.azure.com
 *                 DB_SSL_CA=/ruta/DigiCertGlobalRootG2.crt.pem
 */
return [
    'host'       => Env::get('DB_HOST', '127.0.0.1'),
    'port'       => Env::int('DB_PORT', 3306),
    'database'   => Env::get('DB_DATABASE', 'cursos'),
    'username'   => Env::get('DB_USERNAME', 'root'),
    'password'   => Env::get('DB_PASSWORD', ''),
    'ssl_ca'     => Env::get('DB_SSL_CA', ''),
    'ssl_verify' => Env::bool('DB_SSL_VERIFY', true),
    'timeout'    => Env::int('DB_TIMEOUT', 5),
];

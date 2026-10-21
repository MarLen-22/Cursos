<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

/**
 * Lazily-created shared PDO connection. Supports TLS (required by
 * Azure Database for MySQL) through DB_SSL_CA.
 */
final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection !== null) {
            return self::$connection;
        }

        /** @var array{host:string, port:int, database:string, username:string, password:string, ssl_ca:string, ssl_verify:bool, timeout:int} $config */
        $config = Config::get('database');

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $config['host'],
            $config['port'],
            $config['database'],
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_STRINGIFY_FETCHES  => false,
            PDO::ATTR_TIMEOUT            => $config['timeout'],
            // All DATETIME values are stored in UTC, both locally and on Azure.
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+00:00'",
        ];

        if ($config['ssl_ca'] !== '') {
            if (!is_readable($config['ssl_ca'])) {
                throw new \RuntimeException('El certificado CA definido en DB_SSL_CA no existe o no es legible.');
            }
            $options[PDO::MYSQL_ATTR_SSL_CA] = $config['ssl_ca'];
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = $config['ssl_verify'];
        }

        try {
            self::$connection = new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (PDOException $e) {
            // Never expose the DSN or credentials to the client.
            error_log('[Database] Conexión fallida: ' . $e->getMessage());
            throw new HttpException(503, 'No fue posible conectar con la base de datos. Inténtalo más tarde.', [], $e);
        }

        return self::$connection;
    }
}

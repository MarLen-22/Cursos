<?php

declare(strict_types=1);

namespace App\Core;

class HttpException extends \RuntimeException
{
    private const TITLES = [
        400 => 'Solicitud inválida',
        403 => 'Acceso denegado',
        404 => 'Página no encontrada',
        405 => 'Método no permitido',
        419 => 'Sesión expirada',
        500 => 'Error interno',
        503 => 'Servicio no disponible',
    ];

    private const MESSAGES = [
        400 => 'La solicitud no pudo procesarse.',
        403 => 'No tienes permisos para acceder a esta sección.',
        404 => 'El recurso que buscas no existe o fue movido.',
        405 => 'Este recurso no admite el método solicitado.',
        419 => 'Tu formulario expiró por seguridad. Recarga la página e inténtalo de nuevo.',
        500 => 'Ocurrió un error inesperado. Ya lo registramos para revisarlo.',
        503 => 'El servicio no está disponible temporalmente. Inténtalo en unos minutos.',
    ];

    /** @param array<string, string> $headers */
    public function __construct(
        public readonly int $status,
        string $message = '',
        private readonly array $headers = [],
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message !== '' ? $message : self::defaultMessage($status), $status, $previous);
    }

    /** @return array<string, string> */
    public function headers(): array
    {
        return $this->headers;
    }

    public static function titleFor(int $status): string
    {
        return self::TITLES[$status] ?? 'Error';
    }

    public static function defaultMessage(int $status): string
    {
        return self::MESSAGES[$status] ?? self::MESSAGES[500];
    }
}

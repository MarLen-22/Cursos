<?php

declare(strict_types=1);

namespace App\Core;

final class ErrorHandler
{
    private static bool $debug = false;

    public static function register(bool $debug, string $logPath): void
    {
        self::$debug = $debug;

        error_reporting(E_ALL);
        ini_set('display_errors', '0');
        ini_set('log_errors', '1');
        if ($logPath !== '') {
            ini_set('error_log', $logPath);
        }

        set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
            if ((error_reporting() & $severity) === 0) {
                return false;
            }
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });

        set_exception_handler(static function (\Throwable $e): void {
            self::handle($e);
        });

        register_shutdown_function(static function (): void {
            $error = error_get_last();
            if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
                self::handle(new \ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']));
            }
        });
    }

    public static function handle(\Throwable $e): void
    {
        $status = $e instanceof HttpException ? $e->status : 500;

        if ($status >= 500) {
            $origin = $e->getPrevious() ?? $e;
            error_log(sprintf(
                '[%s] %s en %s:%d%s%s',
                $origin::class,
                $origin->getMessage(),
                $origin->getFile(),
                $origin->getLine(),
                PHP_EOL,
                $origin->getTraceAsString(),
            ));
        }

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        if (!headers_sent()) {
            http_response_code($status);
            header('Content-Type: text/html; charset=UTF-8');
            if ($e instanceof HttpException) {
                foreach ($e->headers() as $name => $value) {
                    header($name . ': ' . $value);
                }
            }
        }

        $message = $e instanceof HttpException ? $e->getMessage() : HttpException::defaultMessage(500);

        try {
            echo View::render('errors/error', [
                'title'     => HttpException::titleFor($status),
                'status'    => $status,
                'message'   => $message,
                'detail'    => self::$debug && $status >= 500 ? self::describe($e) : null,
                // The DB may be the failing component; don't query it to draw the header.
                'authAware' => $status !== 503 && $status !== 500,
                'activeNav' => '',
            ]);
        } catch (\Throwable $renderError) {
            error_log('[ErrorHandler] Falló la vista de error: ' . $renderError->getMessage());
            echo '<!DOCTYPE html><meta charset="UTF-8"><title>Error</title><h1>'
                . htmlspecialchars(HttpException::titleFor($status), ENT_QUOTES, 'UTF-8')
                . '</h1><p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>';
        }
    }

    private static function describe(\Throwable $e): string
    {
        $origin = $e->getPrevious() ?? $e;

        return sprintf(
            "%s: %s\n%s:%d\n\n%s",
            $origin::class,
            $origin->getMessage(),
            $origin->getFile(),
            $origin->getLine(),
            $origin->getTraceAsString(),
        );
    }
}

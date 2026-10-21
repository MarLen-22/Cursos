<?php

declare(strict_types=1);

namespace App\Core;

final class App
{
    private function __construct(private readonly string $rootPath)
    {
    }

    public static function create(string $rootPath): self
    {
        return new self($rootPath);
    }

    public function run(): void
    {
        Env::load($this->rootPath . '/.env');
        require $this->rootPath . '/src/core/helpers.php';
        Config::load($this->rootPath . '/src/config');

        ErrorHandler::register((bool) Config::get('app.debug', false), (string) Config::get('app.log_path', ''));
        date_default_timezone_set((string) Config::get('app.timezone', 'UTC'));
        mb_internal_encoding('UTF-8');

        $basePath = Url::detectBasePath($_SERVER, (string) Config::get('app.base_path', ''));
        $secure = Request::detectHttps($_SERVER);

        Url::configure($basePath, $this->rootPath . '/public');
        View::configure($this->rootPath . '/src/views');
        Session::configure(
            (string) Config::get('app.session.name', 'lv_session'),
            $basePath === '' ? '/' : $basePath . '/',
            $secure,
            (int) Config::get('app.session.idle_lifetime', 7200),
        );

        Response::sendSecurityHeaders($secure);

        $request = Request::fromGlobals($basePath);

        // Missing static files (e.g. /favicon.ico) get a cheap 404 without booting session/DB.
        if (preg_match('#\.[a-z0-9]{1,5}$#i', $request->path) === 1 && !str_starts_with($request->path, '/vendor/')) {
            http_response_code(404);
            header('Content-Type: text/plain; charset=UTF-8');
            echo 'Not Found';

            return;
        }

        $router = new Router();
        $registerRoutes = require $this->rootPath . '/src/config/routes.php';
        $registerRoutes($router);

        $router->dispatch($request);
    }
}

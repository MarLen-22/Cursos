<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /**
     * @var list<array{method:string, pattern:string, handler:array{0:class-string<Controller>, 1:string}, middleware:list<string>}>
     */
    private array $routes = [];

    /**
     * @param array{0:class-string<Controller>, 1:string} $handler
     * @param list<string> $middleware
     */
    public function get(string $path, array $handler, array $middleware = []): void
    {
        $this->add('GET', $path, $handler, $middleware);
    }

    /**
     * @param array{0:class-string<Controller>, 1:string} $handler
     * @param list<string> $middleware
     */
    public function post(string $path, array $handler, array $middleware = []): void
    {
        $this->add('POST', $path, $handler, $middleware);
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method === 'HEAD' ? 'GET' : $request->method;
        $allowed = [];

        foreach ($this->routes as $route) {
            if (preg_match($route['pattern'], $request->path, $matches) !== 1) {
                continue;
            }

            if ($route['method'] !== $method) {
                $allowed[] = $route['method'];
                continue;
            }

            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

            Middleware::run($route['middleware'], $request);

            if ($method === 'POST') {
                Csrf::verify($request->input('_csrf'));
            }

            [$class, $action] = $route['handler'];
            $controller = new $class($request);
            $controller->{$action}(...$params);

            return;
        }

        if ($allowed !== []) {
            throw new HttpException(405, '', ['Allow' => implode(', ', array_unique($allowed))]);
        }

        throw new HttpException(404);
    }

    /**
     * Converts "/modulos/{slug:[a-z0-9-]+}" into a regex with named groups.
     *
     * @param array{0:class-string<Controller>, 1:string} $handler
     * @param list<string> $middleware
     */
    private function add(string $method, string $path, array $handler, array $middleware): void
    {
        $regex = preg_replace_callback(
            '#\{([a-z_]+)(?::([^}]+))?\}#i',
            static fn (array $m): string => '(?P<' . $m[1] . '>' . ($m[2] ?? '[^/]+') . ')',
            $path,
        );

        $this->routes[] = [
            'method'     => $method,
            'pattern'    => '#^' . $regex . '$#u',
            'handler'    => $handler,
            'middleware' => $middleware,
        ];
    }
}

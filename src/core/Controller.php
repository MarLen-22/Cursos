<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

abstract class Controller
{
    public function __construct(protected readonly Request $request)
    {
    }

    /** @param array<string, mixed> $data */
    protected function render(string $template, array $data = [], ?string $layout = 'layouts/main', int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: text/html; charset=UTF-8');
        echo View::render($template, $data, $layout);
    }

    protected function redirect(string $path): never
    {
        Response::redirect(Url::to($path));
    }

    /**
     * Post/Redirect/Get on validation failure: flashes errors and the
     * submitted input (never passwords) back to the form.
     *
     * @param array<string, string> $errors
     * @param array<string, string> $input
     */
    protected function backWithErrors(string $path, array $errors, array $input = []): never
    {
        Session::flash('_errors', $errors);
        Session::flash('_old', $input);
        Session::flash('error', 'Revisa los campos marcados.');
        $this->redirect($path);
    }

    protected function flash(string $type, string $message): void
    {
        Session::flash($type, $message);
    }

    protected function db(): PDO
    {
        return Database::connection();
    }

    protected function intParam(string $value): int
    {
        $int = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($int === false) {
            throw new HttpException(404);
        }

        return $int;
    }
}

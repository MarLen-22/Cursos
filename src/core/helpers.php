<?php

declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Session;
use App\Core\Url;
use App\Core\View;

/** Global view helpers. */

function e(string|int|float|null $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $path = '/'): string
{
    return Url::to($path);
}

function asset(string $path): string
{
    return Url::asset($path);
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(Csrf::token()) . '">';
}

function old(string $key, string $default = ''): string
{
    $old = Session::getFlash('_old');

    return is_array($old) && array_key_exists($key, $old) ? (string) $old[$key] : $default;
}

function has_old(): bool
{
    return is_array(Session::getFlash('_old'));
}

function field_error(string $key): ?string
{
    $errors = Session::getFlash('_errors');

    return is_array($errors) && isset($errors[$key]) ? (string) $errors[$key] : null;
}

function invalid_class(string $key): string
{
    return field_error($key) !== null ? ' is-invalid' : '';
}

/** @param array<string, mixed> $data */
function partial(string $template, array $data = []): string
{
    return View::partial($template, $data);
}

/** Converts a UTC DATETIME from the database to the app timezone. */
function fecha(?string $utc, string $format = 'd/m/Y H:i'): string
{
    if ($utc === null || $utc === '') {
        return '—';
    }

    try {
        $date = new DateTimeImmutable($utc, new DateTimeZone('UTC'));

        return $date->setTimezone(new DateTimeZone(date_default_timezone_get()))->format($format);
    } catch (Exception) {
        return '—';
    }
}

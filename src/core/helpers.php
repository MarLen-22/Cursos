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

/**
 * Inline SVG icon (24x24 grid, stroke = currentColor). Decorative by
 * default; pass a label to expose it to screen readers.
 */
function icon(string $name, string $class = '', ?string $label = null): string
{
    static $paths = [
        'search'        => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
        'chevron-down'  => '<path d="m6 9 6 6 6-6"/>',
        'chevron-right' => '<path d="m9 6 6 6-6 6"/>',
        'arrow-right'   => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'sun'           => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
        'moon'          => '<path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/>',
        'monitor'       => '<rect x="2" y="4" width="20" height="13" rx="2"/><path d="M8 21h8M12 17v4"/>',
        'user'          => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
        'user-circle'   => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="10" r="3"/><path d="M6.2 18.4a7 7 0 0 1 11.6 0"/>',
        'book'          => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5z"/><path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20v-5"/>',
        'logout'        => '<path d="M12 3v9"/><path d="M18.4 6.6a9 9 0 1 1-12.8 0"/>',
        'pin'           => '<path d="M12 22s7-6.2 7-12a7 7 0 0 0-14 0c0 5.8 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/>',
        'phone'         => '<rect x="7" y="2" width="10" height="20" rx="2"/><path d="M11 18h2"/>',
        'pencil'        => '<path d="M17 3a2.8 2.8 0 0 1 4 4L7.5 20.5 2 22l1.5-5.5z"/>',
        'dots'          => '<circle cx="12" cy="5" r="1.2"/><circle cx="12" cy="12" r="1.2"/><circle cx="12" cy="19" r="1.2"/>',
        'check'         => '<path d="m5 12 5 5L20 7"/>',
        'mail'          => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
        'school'        => '<path d="M22 10 12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 2 9 2 12 0v-5"/>',
        'sparkles'      => '<path d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8z"/><path d="M19 17l.7 2 2 .7-2 .7-.7 2-.7-2-2-.7 2-.7z"/>',
        'settings'      => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1.1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"/>',
        'eye'           => '<path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>',
        'eye-off'       => '<path d="M17.9 17.9A10 10 0 0 1 12 19c-6.4 0-10-7-10-7a18 18 0 0 1 5.1-5.9M9.9 5.2A9 9 0 0 1 12 5c6.4 0 10 7 10 7a18 18 0 0 1-2.2 3.2M14.1 14.1a3 3 0 1 1-4.2-4.2M2 2l20 20"/>',
        'play'          => '<circle cx="12" cy="12" r="10"/><path d="m10 8 6 4-6 4z"/>',
        'award'         => '<circle cx="12" cy="9" r="6"/><path d="m8.2 13.8-1.7 8.2 5.5-3 5.5 3-1.7-8.2"/>',
        'filter'        => '<path d="M3 5h18l-7 8v6l-4 2v-8z"/>',
        'clock'         => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        'help'          => '<circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 0 1 5.8 1c0 2-3 3-3 3M12 17h.01"/>',
        'shield'        => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        'trash'         => '<path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/>',
        'text-size'     => '<path d="M4 7V5h10v2M9 5v14M7 19h4M14 13v-1h6v1M17 12v7M16 19h2"/>',
        'keyboard'      => '<rect x="2" y="6" width="20" height="12" rx="2"/><path d="M6 10h.01M10 10h.01M14 10h.01M18 10h.01M7 14h10"/>',
        'instagram'     => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5h.01"/>',
        'linkedin'      => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/>',
        'x-social'      => '<path d="M4 4l16 16M20 4 4 20"/>',
        'facebook'      => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
        'youtube'       => '<rect x="2" y="5" width="20" height="14" rx="4"/><path d="m10 9 5 3-5 3z"/>',
    ];

    $body = $paths[$name] ?? $paths['help'];
    $a11y = $label !== null ? 'role="img" aria-label="' . e($label) . '"' : 'aria-hidden="true" focusable="false"';

    return '<svg class="icon ' . e($class) . '" ' . $a11y . ' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" '
        . 'fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">'
        . $body . '</svg>';
}

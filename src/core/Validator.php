<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Declarative input validation. Rules: required, email, min:n, max:n,
 * maxbytes:n, between:a,b, int, in:a,b,c, regex:/pattern/, same:field,
 * password (letters + digits), date:minAge,maxAge (Y-m-d), decimal:min,max.
 */
final class Validator
{
    /** @var array<string, string> */
    private array $errors = [];

    /**
     * @param array<string, string>       $data
     * @param array<string, list<string>> $rules
     * @param array<string, string>       $labels
     */
    public function __construct(
        private readonly array $data,
        private readonly array $rules,
        private readonly array $labels = [],
    ) {
        $this->run();
    }

    public function passes(): bool
    {
        return $this->errors === [];
    }

    /** @return array<string, string> */
    public function errors(): array
    {
        return $this->errors;
    }

    private function run(): void
    {
        foreach ($this->rules as $field => $fieldRules) {
            $value = $this->data[$field] ?? '';
            $required = in_array('required', $fieldRules, true);

            if ($value === '') {
                if ($required) {
                    $this->errors[$field] = sprintf('%s es obligatorio.', $this->label($field));
                }
                continue;
            }

            foreach ($fieldRules as $rule) {
                $message = $this->check($field, $value, $rule);
                if ($message !== null) {
                    $this->errors[$field] = $message;
                    break;
                }
            }
        }
    }

    private function check(string $field, string $value, string $rule): ?string
    {
        [$name, $param] = array_pad(explode(':', $rule, 2), 2, '');
        $label = $this->label($field);

        return match ($name) {
            'required' => null,
            'email' => filter_var($value, FILTER_VALIDATE_EMAIL) === false
                ? sprintf('%s no es un correo electrónico válido.', $label) : null,
            'min' => mb_strlen($value) < (int) $param
                ? sprintf('%s debe tener al menos %d caracteres.', $label, (int) $param) : null,
            'max' => mb_strlen($value) > (int) $param
                ? sprintf('%s no debe exceder %d caracteres.', $label, (int) $param) : null,
            'maxbytes' => strlen($value) > (int) $param
                ? sprintf('%s es demasiado largo.', $label) : null,
            'int' => filter_var($value, FILTER_VALIDATE_INT) === false
                ? sprintf('%s debe ser un número entero.', $label) : null,
            'between' => $this->checkBetween($value, $param, $label),
            'date' => $this->checkDate($value, $param, $label),
            'decimal' => $this->checkDecimal($value, $param, $label),
            'in' => !in_array($value, explode(',', $param), true)
                ? sprintf('%s contiene un valor no permitido.', $label) : null,
            'regex' => preg_match($param, $value) !== 1
                ? sprintf('%s tiene un formato inválido.', $label) : null,
            'same' => $value !== ($this->data[$param] ?? null)
                ? sprintf('%s no coincide.', $label) : null,
            'password' => (preg_match('/\p{L}/u', $value) !== 1 || preg_match('/\d/', $value) !== 1)
                ? sprintf('%s debe incluir letras y números.', $label) : null,
            'letters' => preg_match('/^[\p{L}\s\.\'\-]+$/u', $value) !== 1
                ? sprintf('%s solo puede contener letras y espacios (sin números ni símbolos especiales).', $label) : null,
            'tecvictoria_email' => (!str_ends_with(strtolower($value), '@tecvictoria.edu.mx') && !str_starts_with(strtolower($value), '@tecvictoria.edu.mx'))
                ? sprintf('%s debe pertenecer obligatoriamente al dominio institucional @tecvictoria.edu.mx.', $label) : null,
            default => throw new \LogicException("Regla de validación desconocida: {$name}"),
        };
    }

    private function checkBetween(string $value, string $param, string $label): ?string
    {
        [$min, $max] = array_map('intval', array_pad(explode(',', $param, 2), 2, '0'));
        $int = filter_var($value, FILTER_VALIDATE_INT);

        if ($int === false || $int < $min || $int > $max) {
            return sprintf('%s debe estar entre %d y %d.', $label, $min, $max);
        }

        return null;
    }

    /** Validates a real Y-m-d date; optional "minAge,maxAge" in years. */
    private function checkDate(string $value, string $param, string $label): ?string
    {
        $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $value);
        if ($date === false || $date->format('Y-m-d') !== $value) {
            return sprintf('%s no es una fecha válida.', $label);
        }

        if ($param !== '') {
            [$minAge, $maxAge] = array_map('intval', array_pad(explode(',', $param, 2), 2, '0'));
            $age = $date->diff(new \DateTimeImmutable('today'))->y;
            if ($date > new \DateTimeImmutable('today') || $age < $minAge || ($maxAge > 0 && $age > $maxAge)) {
                return sprintf('%s debe corresponder a una edad entre %d y %d años.', $label, $minAge, $maxAge);
            }
        }

        return null;
    }

    private function checkDecimal(string $value, string $param, string $label): ?string
    {
        [$min, $max] = array_map('floatval', array_pad(explode(',', $param, 2), 2, '0'));

        if (preg_match('/^\d{1,3}(?:\.\d{1,2})?$/', $value) !== 1 || (float) $value < $min || (float) $value > $max) {
            return sprintf('%s debe ser un número entre %s y %s (máximo 2 decimales).', $label, $min, $max);
        }

        return null;
    }

    private function label(string $field): string
    {
        return $this->labels[$field] ?? ucfirst(str_replace('_', ' ', $field));
    }
}

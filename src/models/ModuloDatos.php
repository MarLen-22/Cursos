<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Validator;

/** Validated input for creating/updating a module. */
final class ModuloDatos
{
    public const CAMPOS = ['titulo', 'slug', 'descripcion', 'contenido', 'icono', 'color', 'nivel', 'orden', 'activo'];

    private function __construct(
        public readonly string $titulo,
        public readonly string $slug,
        public readonly string $descripcion,
        public readonly ?string $contenido,
        public readonly string $icono,
        public readonly ColorModulo $color,
        public readonly NivelModulo $nivel,
        public readonly int $orden,
        public readonly bool $activo,
    ) {
    }

    /**
     * @param array<string, string> $input
     * @return array{0: ?self, 1: array<string, string>}  [datos, errores]
     */
    public static function desdeFormulario(array $input): array
    {
        $input['slug'] = strtolower($input['slug'] ?? '');

        $validator = new Validator($input, [
            'titulo'      => ['required', 'min:3', 'max:120'],
            'slug'        => ['required', 'max:80', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'descripcion' => ['required', 'min:10', 'max:500'],
            'contenido'   => ['max:10000'],
            'icono'       => ['required', 'max:16'],
            'color'       => ['required', 'in:' . implode(',', ColorModulo::valores())],
            'nivel'       => ['required', 'in:' . implode(',', NivelModulo::valores())],
            'orden'       => ['required', 'between:0,999'],
            'activo'      => ['in:0,1'],
        ], [
            'titulo'      => 'El título',
            'slug'        => 'El identificador (slug)',
            'descripcion' => 'La descripción',
            'contenido'   => 'El contenido',
            'icono'       => 'El ícono',
            'color'       => 'El color',
            'nivel'       => 'El nivel',
            'orden'       => 'El orden',
            'activo'      => 'El estado',
        ]);

        if (!$validator->passes()) {
            return [null, $validator->errors()];
        }

        return [new self(
            $input['titulo'],
            $input['slug'],
            $input['descripcion'],
            ($input['contenido'] ?? '') !== '' ? $input['contenido'] : null,
            $input['icono'],
            ColorModulo::from($input['color']),
            NivelModulo::from($input['nivel']),
            (int) $input['orden'],
            ($input['activo'] ?? '0') === '1',
        ), []];
    }
}

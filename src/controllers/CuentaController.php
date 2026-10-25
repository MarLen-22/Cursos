<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Validator;
use App\Models\Catalogo;
use App\Models\Genero;
use App\Models\Modalidad;
use App\Models\NivelEstudios;
use App\Models\PerfilAlumno;
use App\Models\PerfilAlumnoRepository;
use App\Models\UsuarioRepository;

/** "Mi cuenta": student personal, contact and academic information. */
final class CuentaController extends Controller
{
    private const TELEFONO = 'regex:/^\+?[0-9\s()\-]{7,20}$/';

    public function show(): void
    {
        $usuario = Auth::user();

        $this->render('cuenta/index', [
            'title'     => 'Mi cuenta | Lógica Visual ABP',
            'activeNav' => 'cuenta',
            'usuario'   => $usuario,
            'perfil'    => (new PerfilAlumnoRepository($this->db()))->findByUsuario($usuario->id),
        ]);
    }

    public function update(): void
    {
        $usuario = Auth::user();
        $input = $this->request->only(array_merge(['nombre'], PerfilAlumno::CAMPOS));
        $input['numero_control'] = strtoupper($input['numero_control']);
        $paises = implode(',', Catalogo::PAISES);

        $validator = new Validator($input, [
            'nombre'                       => ['required', 'min:3', 'max:100', 'letters'],
            'numero_control'               => ['required', 'regex:/^[A-Z0-9]{6,20}$/'],
            'telefono'                     => [self::TELEFONO],
            'pais_residencia'              => ['in:' . $paises],
            'nacionalidad'                 => ['letters'],
            'fecha_nacimiento'             => ['date:12,100'],
            'genero'                       => ['in:' . Catalogo::valores(Genero::class)],
            'email_alterno'                => ['email', 'max:150'],
            'ciudad'                       => ['letters', 'max:80'],
            'estado_residencia'            => ['letters', 'max:80'],
            'codigo_postal'                => ['regex:/^[0-9]{5}$/'],
            'contacto_emergencia_nombre'   => ['letters', 'max:100'],
            'contacto_emergencia_telefono' => [self::TELEFONO],
            'institucion'                  => ['max:150'],
            'carrera'                      => ['max:120'],
            'semestre'                     => ['between:1,14'],
            'modalidad'                    => ['in:' . Catalogo::valores(Modalidad::class)],
            'nivel_estudios'               => ['in:' . Catalogo::valores(NivelEstudios::class)],
            'area_estudios'                => ['max:120'],
            'nota_media'                   => ['decimal:0,100'],
        ], [
            'nombre'                       => 'El nombre completo',
            'numero_control'               => 'El número de control',
            'telefono'                     => 'El teléfono',
            'pais_residencia'              => 'El país de residencia',
            'nacionalidad'                 => 'La nacionalidad',
            'fecha_nacimiento'             => 'La fecha de nacimiento',
            'genero'                       => 'El género',
            'email_alterno'                => 'El correo alterno',
            'ciudad'                       => 'La ciudad',
            'estado_residencia'            => 'El estado',
            'codigo_postal'                => 'El código postal',
            'contacto_emergencia_nombre'   => 'El contacto de emergencia',
            'contacto_emergencia_telefono' => 'El teléfono de emergencia',
            'institucion'                  => 'La institución',
            'carrera'                      => 'La carrera',
            'semestre'                     => 'El semestre',
            'modalidad'                    => 'La modalidad',
            'nivel_estudios'               => 'El nivel de estudios',
            'area_estudios'                => 'El área de estudios',
            'nota_media'                   => 'La nota media',
        ]);

        $errores = $validator->errors();
        $perfiles = new PerfilAlumnoRepository($this->db());

        if (!isset($errores['numero_control']) && $perfiles->numeroControlExists($input['numero_control'], $usuario->id)) {
            $errores['numero_control'] = 'El número de control ya está asociado a otra cuenta.';
        }

        if ($errores !== []) {
            $this->backWithErrors('/mi-cuenta', $errores, $input);
        }

        $db = $this->db();
        $db->beginTransaction();
        try {
            (new UsuarioRepository($db))->updateNombre($usuario->id, $input['nombre']);
            $perfiles->guardar($usuario->id, array_diff_key($input, ['nombre' => true]));
            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }

        $this->flash('success', 'Tus datos se guardaron correctamente.');
        $this->redirect('/mi-cuenta');
    }
}

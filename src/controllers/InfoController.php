<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

/** Informational pages linked from the "Explora" menu and the footer. */
final class InfoController extends Controller
{
    /** Searchable index of site sections (used by the header search). */
    public const PAGINAS = [
        ['ruta' => '/modulos', 'titulo' => 'Módulos interactivos', 'descripcion' => 'Catálogo completo de rutas de aprendizaje con simuladores.', 'claves' => 'cursos modulos simuladores catalogo programacion calculo fisica discretas'],
        ['ruta' => '/metodologia', 'titulo' => 'Metodología ABP', 'descripcion' => 'Cómo aplicamos el Aprendizaje Basado en Problemas paso a paso.', 'claves' => 'abp metodologia aprendizaje basado problemas etapas iniciativas'],
        ['ruta' => '/evaluacion', 'titulo' => 'Evaluación formativa', 'descripcion' => 'Evaluación diagnóstica, formativa y de desempeño.', 'claves' => 'evaluacion examen diagnostica formativa desempeno calificacion'],
        ['ruta' => '/videopodcast', 'titulo' => 'Videopodcast', 'descripcion' => 'Conversaciones con docentes y estudiantes sobre razonamiento lógico.', 'claves' => 'video podcast episodios entrevistas'],
        ['ruta' => '/blog', 'titulo' => 'Blog', 'descripcion' => 'Artículos, consejos de estudio y novedades de la plataforma.', 'claves' => 'blog articulos noticias consejos estudio'],
        ['ruta' => '/quienes-somos', 'titulo' => 'Quiénes somos', 'descripcion' => 'El equipo y la misión detrás de Lógica Visual ABP.', 'claves' => 'nosotros equipo mision vision tec victoria instituto'],
        ['ruta' => '/ayuda', 'titulo' => 'Soporte y ayuda', 'descripcion' => 'Preguntas frecuentes, contacto y avisos legales.', 'claves' => 'ayuda soporte faq preguntas contacto privacidad cuenta contrasena'],
    ];

    public function videopodcast(): void
    {
        $this->pagina('info/videopodcast', 'Videopodcast');
    }

    public function blog(): void
    {
        $this->pagina('info/blog', 'Blog');
    }

    public function quienesSomos(): void
    {
        $this->pagina('info/quienes_somos', 'Quiénes somos');
    }

    public function ayuda(): void
    {
        $this->pagina('info/ayuda', 'Soporte y ayuda');
    }

    private function pagina(string $vista, string $titulo): void
    {
        $this->render($vista, [
            'title'     => $titulo . ' | Lógica Visual ABP',
            'activeNav' => 'explora',
        ]);
    }
}

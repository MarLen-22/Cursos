<?php
/**
 * Videopodcast: Razonamiento Visible
 * Conversaciones y videos educativos sobre Aprendizaje Basado en Problemas y Simulación
 */
$episodios = [
    [
        'id'          => 1,
        'badge'       => 'Episodio 01',
        'badge_class' => 'bg-cyan text-navy',
        'duracion'    => '32 min',
        'titulo'      => 'Del enunciado al modelo mental',
        'descripcion' => 'Aprende a descomponer problemas complejos, identificar datos de entrada/salida y formular representaciones mentales claras antes de escribir una sola línea de código en un simulador.',
        'invitado'    => 'Dr. Carlos Treviño · Especialista en Algoritmia y Lógica',
        'youtube_url' => 'https://youtu.be/TdITcVD64zI',
        'video_id'    => 'TdITcVD64zI',
        'icon'        => '🧠',
        'puntos'      => [
            'Diferencia entre memorizar sintaxis y construir modelos lógicos.',
            'Técnicas de abstracción aplicadas a la programación y matemáticas.',
            'Cómo evitar los bloqueos iniciales al enfrentarse a enunciados extensos.',
        ],
    ],
    [
        'id'          => 2,
        'badge'       => 'Episodio 02',
        'badge_class' => 'bg-indigo-subtle text-indigo',
        'duracion'    => '28 min',
        'titulo'      => 'El valor pedagógico del error en ABP',
        'descripcion' => 'El error no es un fracaso, sino el motor fundamental del aprendizaje. Descubre cómo la experimentación deliberada y el análisis del fallo aceleran el razonamiento crítico de los estudiantes.',
        'invitado'    => 'Mesa redonda estudiantil y docente de Ciencias Básicas',
        'youtube_url' => 'https://youtu.be/noNv_rTwwNw',
        'video_id'    => 'noNv_rTwwNw',
        'icon'        => '💡',
        'puntos'      => [
            'El error como catalizador de nuevas sinapsis y comprensión profunda.',
            'Cultura del debriefing: reflexionar por qué falló la primera hipótesis.',
            'Comparativa entre enseñanza tradicional punitiva y pedagogía activa ABP.',
        ],
    ],
    [
        'id'          => 3,
        'badge'       => 'Episodio 03',
        'badge_class' => 'bg-purple-subtle text-purple',
        'duracion'    => '35 min',
        'titulo'      => 'Simuladores interactivos y la nube',
        'descripcion' => 'Exploración técnica y pedagógica de la arquitectura de simulación en la nube: reactividad en tiempo real, interfaces dinámicas y su impacto en la educación tecnológica superior.',
        'invitado'    => 'Ing. Sistemas Computacionales · Academia de Software',
        'youtube_url' => 'https://youtu.be/0TFZfvvB67Y',
        'video_id'    => '0TFZfvvB67Y',
        'icon'        => '☁️',
        'puntos'      => [
            'Por qué los entornos simulados interactivos superan a los libros estáticos.',
            'Despliegue y escalabilidad de laboratorios virtuales en la nube.',
            'El futuro de la evaluación formativa mediante telemetría de interacción.',
        ],
    ],
];
?>

<section class="py-5 bg-navy text-white position-relative overflow-hidden">
    <div class="bg-grid position-absolute top-0 start-0 w-100 h-100 pointer-events-none opacity-20"></div>
    <div class="container-fluid max-w-7xl mx-auto px-4 position-relative z-1">
        <div class="d-flex align-items-center gap-3 mb-3">
            <span class="fs-1 p-3 rounded-4 bg-white bg-opacity-10 shadow-xs">🎙️</span>
            <div>
                <span class="badge bg-cyan text-navy fw-bold text-uppercase px-3 py-1 mb-2">Producción Audiovisual</span>
                <h1 class="h2 fw-bold text-white mb-0">Videopodcast: Razonamiento Visible</h1>
                <p class="text-cyan small mb-0">Conversaciones sobre cómo pensar antes de programar</p>
            </div>
        </div>
        <p class="lead text-slate-300 max-w-2xl mb-0">
            Docentes, investigadores y estudiantes comparten sus experiencias resolviendo problemas complejos,
            superando bloqueos algorítmicos y aprovechando el Aprendizaje Basado en Problemas con simulación interactiva.
        </p>
    </div>
</section>

<section class="py-5 bg-slate-50 min-vh-75">
    <div class="container-fluid max-w-7xl mx-auto px-4">
        <!-- Banner informativo -->
        <div class="p-4 rounded-4 bg-white border border-slate-200 shadow-xs mb-5 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <span class="fs-2 text-cyan">📺</span>
                <div>
                    <h2 class="h6 fw-bold mb-1 text-navy">Visualización interactiva y acceso a YouTube</h2>
                    <p class="text-muted small mb-0">
                        Haz clic en cualquier episodio para abrir la ventana emergente con el reproductor integrado, navegar al siguiente video o abrir el contenido directamente en YouTube.
                    </p>
                </div>
            </div>
            <span class="badge bg-slate-100 text-slate-700 px-3 py-2 border rounded-pill">
                3 Episodios Disponibles
            </span>
        </div>

        <div class="row g-4">
            <?php foreach ($episodios as $index => $ep): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-0 rounded-4 shadow-xs overflow-hidden bg-white hover-card d-flex flex-column transition-all"
                         id="podcast-card-<?= $ep['id'] ?>">
                        <!-- Cabecera de la tarjeta con miniatura visual -->
                        <div class="p-4 bg-navy text-white position-relative">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge <?= $ep['badge_class'] ?> fw-bold px-3 py-1">
                                    <?= e($ep['badge']) ?>
                                </span>
                                <span class="small text-slate-300 font-monospace">
                                    ⏱ <?= e($ep['duracion']) ?>
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-3"><?= $ep['icon'] ?></span>
                                <span class="text-cyan small fw-semibold text-truncate">Lógica Visual ABP</span>
                            </div>
                        </div>

                        <!-- Cuerpo de la tarjeta -->
                        <div class="card-body p-4 d-flex flex-column">
                            <h2 class="h5 fw-bold mb-2 text-navy"><?= e($ep['titulo']) ?></h2>
                            <p class="text-muted small mb-3 flex-grow-1 leading-relaxed">
                                <?= e($ep['descripcion']) ?>
                            </p>

                            <!-- Puntos destacados -->
                            <div class="bg-light p-3 rounded-3 mb-4">
                                <div class="fw-semibold small text-slate-700 mb-2">Temas clave abordados:</div>
                                <ul class="list-unstyled small text-muted mb-0 space-y-1">
                                    <?php foreach ($ep['puntos'] as $punto): ?>
                                        <li class="d-flex align-items-start gap-2 mb-1">
                                            <span class="text-cyan fw-bold">✓</span>
                                            <span><?= e($punto) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <!-- Pie de tarjeta con botones de acción -->
                            <div class="pt-3 border-top mt-auto">
                                <div class="small text-muted mb-3 d-flex align-items-center gap-1">
                                    <span class="text-secondary"><?= icon('user-circle', 'icon-xs') ?></span>
                                    <span class="text-truncate"><?= e($ep['invitado']) ?></span>
                                </div>
                                    <button type="button"
                                            class="btn btn-cyan btn-sm rounded-pill px-3 fw-bold flex-grow-1 d-flex align-items-center justify-content-center gap-2 shadow-xs"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalVideoPodcast"
                                            data-podcast-idx="<?= $index ?>">
                                        <span>▶ Reproducir video</span>
                                    </button>
                                    <a href="<?= e($ep['youtube_url']) ?>"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="btn btn-outline-danger btn-sm rounded-pill px-3 d-flex align-items-center gap-1"
                                       title="Abrir en YouTube">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                            <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.022.26-.01.104c-.048.519-.119 1.023-.22 1.402a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"/>
                                        </svg>
                                        <span class="d-none d-sm-inline">YouTube</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Modal / Ventana Emergente del Videopodcast -->
<div class="modal fade" id="modalVideoPodcast" tabindex="-1" aria-labelledby="modalVideoPodcastLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            <!-- Header del Modal -->
            <div class="modal-header bg-navy text-white px-4 py-3 border-0">
                <div class="d-flex align-items-center gap-3">
                    <span id="podcastModalBadge" class="badge bg-cyan text-navy fw-bold px-3 py-1">Episodio</span>
                    <div>
                        <h3 class="modal-title fs-5 fw-bold text-white mb-0" id="modalVideoPodcastLabel">Título del Episodio</h3>
                        <p id="podcastModalGuest" class="text-cyan small mb-0"></p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar reproductor"></button>
            </div>

            <!-- Cuerpo del Modal: Reproductor de Video 16:9 -->
            <div class="modal-body p-0 bg-black">
                <div class="ratio ratio-16x9">
                    <iframe id="podcastIframe"
                            src=""
                            title="Reproductor de Video Videopodcast"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                </div>
            </div>

            <!-- Información adicional del tema dentro de la ventana emergente -->
            <div class="p-4 bg-white border-top">
                <div class="row align-items-center g-3">
                    <div class="col-12 col-md-8">
                        <h4 class="h6 fw-bold text-navy mb-1">Acerca de este episodio</h4>
                        <p id="podcastModalDesc" class="text-muted small mb-0 leading-relaxed"></p>
                    </div>
                    <div class="col-12 col-md-4 text-md-end">
                        <a id="podcastModalYoutubeBtn"
                           href="#"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="btn btn-danger btn-sm rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.022.26-.01.104c-.048.519-.119 1.023-.22 1.402a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"/>
                            </svg>
                            <span>Abrir en YouTube ↗</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer del Modal: Navegación entre videos (Anterior / Siguiente) -->
            <div class="modal-footer bg-slate-50 px-4 py-3 d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold" id="podcastPrevBtn">
                    ← Video anterior
                </button>
                <span class="small text-muted font-monospace" id="podcastCounterIndicator">Episodio 1 de 3</span>
                <button type="button" class="btn btn-cyan btn-sm rounded-pill px-4 fw-bold" id="podcastNextBtn">
                    Siguiente video →
                </button>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('js/videopodcast.js') ?>"></script>


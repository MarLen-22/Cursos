/**
 * Lógica Visual ABP · Controlador de Modal de Videopodcast
 * Soporta reproducción en ventana emergente, navegación al siguiente video y enlace a YouTube.
 */
document.addEventListener('DOMContentLoaded', function () {
    const episodiosData = [
        {
            id: 1,
            badge: 'Episodio 01',
            titulo: 'Del enunciado al modelo mental',
            descripcion: 'Aprende a descomponer problemas complejos, identificar datos de entrada/salida y formular representaciones mentales claras antes de escribir una sola línea de código en un simulador.',
            invitado: 'Dr. Carlos Treviño · Especialista en Algoritmia y Lógica',
            youtube_url: 'https://youtu.be/TdITcVD64zI',
            video_id: 'TdITcVD64zI'
        },
        {
            id: 2,
            badge: 'Episodio 02',
            titulo: 'El valor pedagógico del error en ABP',
            descripcion: 'El error no es un fracaso, sino el motor fundamental del aprendizaje. Descubre cómo la experimentación deliberada y el análisis del fallo aceleran el razonamiento crítico de los estudiantes.',
            invitado: 'Mesa redonda estudiantil y docente de Ciencias Básicas',
            youtube_url: 'https://youtu.be/noNv_rTwwNw',
            video_id: 'noNv_rTwwNw'
        },
        {
            id: 3,
            badge: 'Episodio 03',
            titulo: 'Simuladores interactivos y la nube',
            descripcion: 'Exploración técnica y pedagógica de la arquitectura de simulación en la nube: reactividad en tiempo real, interfaces dinámicas y su impacto en la educación tecnológica superior.',
            invitado: 'Ing. Sistemas Computacionales · Academia de Software',
            youtube_url: 'https://youtu.be/0TFZfvvB67Y',
            video_id: '0TFZfvvB67Y'
        }
    ];

    let indiceActual = 0;

    const modalEl = document.getElementById('modalVideoPodcast');
    const iframe = document.getElementById('podcastIframe');
    const titleEl = document.getElementById('modalVideoPodcastLabel');
    const guestEl = document.getElementById('podcastModalGuest');
    const descEl = document.getElementById('podcastModalDesc');
    const badgeEl = document.getElementById('podcastModalBadge');
    const ytBtn = document.getElementById('podcastModalYoutubeBtn');
    const counterEl = document.getElementById('podcastCounterIndicator');
    const prevBtn = document.getElementById('podcastPrevBtn');
    const nextBtn = document.getElementById('podcastNextBtn');

    function actualizarModalPodcast() {
        const ep = episodiosData[indiceActual];
        if (!ep) return;

        if (titleEl) titleEl.textContent = ep.titulo;
        if (guestEl) guestEl.textContent = ep.invitado;
        if (descEl) descEl.textContent = ep.descripcion;
        if (badgeEl) badgeEl.textContent = ep.badge;
        if (ytBtn) ytBtn.href = ep.youtube_url;
        if (counterEl) counterEl.textContent = `Episodio ${indiceActual + 1} de ${episodiosData.length}`;

        if (iframe) {
            iframe.src = `https://www.youtube.com/embed/${ep.video_id}?autoplay=1&rel=0`;
        }

        if (prevBtn) prevBtn.disabled = (indiceActual === 0);
        if (nextBtn) nextBtn.disabled = (indiceActual === episodiosData.length - 1);
    }

    window.abrirModalPodcast = function (index) {
        if (!episodiosData || index < 0 || index >= episodiosData.length) return;
        indiceActual = index;
        actualizarModalPodcast();
        if (modalEl && typeof bootstrap !== 'undefined') {
            const instance = bootstrap.Modal.getOrCreateInstance(modalEl);
            if (!modalEl.classList.contains('show')) {
                instance.show();
            }
        }
    };

    window.podcastNavegar = function (delta) {
        const nuevoIndice = indiceActual + delta;
        if (nuevoIndice >= 0 && nuevoIndice < episodiosData.length) {
            indiceActual = nuevoIndice;
            actualizarModalPodcast();
        }
    };

    // Escuchar apertura nativa por Bootstrap modal
    if (modalEl) {
        modalEl.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;
            if (trigger) {
                const idxAttr = trigger.getAttribute('data-podcast-idx') || trigger.getAttribute('data-podcast-index');
                if (idxAttr !== null) {
                    const idx = parseInt(idxAttr, 10);
                    if (!isNaN(idx) && idx >= 0 && idx < episodiosData.length) {
                        indiceActual = idx;
                    }
                }
            }
            actualizarModalPodcast();
        });

        // Detener video al cerrar el modal
        modalEl.addEventListener('hidden.bs.modal', function () {
            if (iframe) {
                iframe.src = '';
            }
        });
    }

    // Botones de navegación interna
    if (prevBtn) {
        prevBtn.addEventListener('click', function () {
            window.podcastNavegar(-1);
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            window.podcastNavegar(1);
        });
    }

    // Botones en tarjetas
    document.querySelectorAll('[data-podcast-idx], [data-podcast-index]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const idxAttr = this.getAttribute('data-podcast-idx') || this.getAttribute('data-podcast-index');
            const idx = parseInt(idxAttr, 10);
            if (!isNaN(idx)) {
                window.abrirModalPodcast(idx);
            }
        });
    });
});

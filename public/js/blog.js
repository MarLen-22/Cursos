/**
 * Lógica Visual ABP · Controlador de Recursos Multimedia del Blog
 * Gestiona ventanas emergentes (modales) para selección y reproducción de videos de YouTube,
 * y visualización integrada de documentos PDF.
 */
(function () {
    const videosApartado1 = [
        { id: 'CUDRZB-wdzI', titulo: 'Las 4 fases del Aprendizaje Basado en Problemas', url: 'https://youtu.be/CUDRZB-wdzI' },
        { id: 'hrBjKEu5EtE', titulo: 'Metodología y aplicación práctica de ABP en el aula', url: 'https://youtu.be/hrBjKEu5EtE' },
        { id: 'J2rv0TCg3vs', titulo: 'Estrategias de resolución y pensamiento crítico', url: 'https://youtu.be/J2rv0TCg3vs' }
    ];

    const videosApartado2 = [
        { id: 'IV0Cs5wlycY', titulo: 'Por qué los simuladores superan a la teoría estática', url: 'https://youtu.be/IV0Cs5wlycY' },
        { id: 'MAjm9Sfc9Mg', titulo: 'El valor pedagógico del error y el debriefing', url: 'https://youtu.be/MAjm9Sfc9Mg' },
        { id: '4J-lLDp4OXs', titulo: 'Modelos interactivos de simulación en ingeniería', url: 'https://youtu.be/4J-lLDp4OXs' },
        { id: '1TQxM3lpCf4', titulo: 'Entornos interactivos y neurociencia en educación', url: 'https://youtu.be/1TQxM3lpCf4' }
    ];

    let pdfsData = {
        'abp': {
            titulo: 'El Aprendizaje Basado en Problemas como técnica didáctica (ITESM)',
            archivo: 'abp.pdf',
            url: ''
        },
        'cap21': {
            titulo: 'Capítulo 21: La importancia de la simulación como estrategia didáctica (UNAM)',
            archivo: 'Cap-21-Formacion-Docente-en-las-Universidades.pdf',
            url: ''
        },
        'simulacion': {
            titulo: 'El aprendizaje basado en simulación y el aporte de las teorías educativas (Revista Espacios)',
            archivo: 'El aprendizaje basado en simulación y el aporte de las teorías educativas.pdf',
            url: ''
        }
    };

    let listaVideosActual = videosApartado1;
    let indiceVideoActual = 0;

    function initPdfUrls() {
        const pdfContainer = document.getElementById('blogPdfData');
        if (pdfContainer) {
            if (pdfContainer.dataset.pdfAbp) pdfsData.abp.url = pdfContainer.dataset.pdfAbp;
            if (pdfContainer.dataset.pdfCap21) pdfsData.cap21.url = pdfContainer.dataset.pdfCap21;
            if (pdfContainer.dataset.pdfSimulacion) pdfsData.simulacion.url = pdfContainer.dataset.pdfSimulacion;
        }
    }

    // --- Funciones para el Modal de Video ---
    window.prepararModalVideos = function (apartadoNum) {
        if (apartadoNum === 2) {
            listaVideosActual = videosApartado2;
            const sub = document.getElementById('blogModalVideoSubtitle');
            if (sub) sub.textContent = 'Apartado 2: Por qué los simuladores superan a la teoría estática';
            const b = document.getElementById('blogModalVideoBadge');
            if (b) {
                b.textContent = 'Videos Simulación';
                b.className = 'badge bg-indigo text-white fw-bold px-3 py-1';
            }
        } else {
            listaVideosActual = videosApartado1;
            const sub = document.getElementById('blogModalVideoSubtitle');
            if (sub) sub.textContent = 'Apartado 1: Las 4 fases del Aprendizaje Basado en Problemas';
            const b = document.getElementById('blogModalVideoBadge');
            if (b) {
                b.textContent = 'Videos ABP';
                b.className = 'badge bg-cyan text-navy fw-bold px-3 py-1';
            }
        }

        indiceVideoActual = 0;
        renderVideoSelectors();
        cargarVideoBlog(0);
    };

    window.abrirModalVideos = function (apartadoNum) {
        window.prepararModalVideos(apartadoNum);
        const modalEl = document.getElementById('modalBlogVideos');
        if (modalEl && typeof bootstrap !== 'undefined') {
            const instance = bootstrap.Modal.getOrCreateInstance(modalEl);
            if (!modalEl.classList.contains('show')) {
                instance.show();
            }
        }
    };

    function renderVideoSelectors() {
        const videoSelectors = document.getElementById('blogVideoSelectorButtons');
        if (!videoSelectors) return;
        videoSelectors.innerHTML = '';
        listaVideosActual.forEach((v, idx) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = `btn btn-sm rounded-pill px-3 py-1 ${idx === indiceVideoActual ? 'btn-navy fw-bold text-white shadow-2xs' : 'btn-outline-secondary'}`;
            btn.textContent = `Video ${idx + 1}`;
            btn.addEventListener('click', function () {
                cargarVideoBlog(idx);
            });
            videoSelectors.appendChild(btn);
        });
    }

    function cargarVideoBlog(idx) {
        if (idx < 0 || idx >= listaVideosActual.length) return;
        indiceVideoActual = idx;
        const v = listaVideosActual[idx];

        const videoTitle = document.getElementById('modalBlogVideosLabel');
        const videoIframe = document.getElementById('blogVideoIframe');
        const videoYtBtn = document.getElementById('blogVideoYoutubeBtn');
        const videoPrevBtn = document.getElementById('blogVideoPrevBtn');
        const videoNextBtn = document.getElementById('blogVideoNextBtn');
        const videoCounter = document.getElementById('blogVideoCounterIndicator');

        if (videoTitle) videoTitle.textContent = v.titulo;
        if (videoYtBtn) {
            videoYtBtn.href = v.url;
            videoYtBtn.setAttribute('title', 'Abrir ' + v.titulo + ' en YouTube');
        }
        if (videoIframe) {
            videoIframe.src = `https://www.youtube.com/embed/${v.id}?autoplay=1&rel=0`;
        }
        if (videoCounter) {
            videoCounter.textContent = `Video ${idx + 1} de ${listaVideosActual.length}`;
        }

        if (videoPrevBtn) videoPrevBtn.disabled = (idx === 0);
        if (videoNextBtn) videoNextBtn.disabled = (idx === listaVideosActual.length - 1);

        renderVideoSelectors();
    }

    window.blogNavegarVideo = function (delta) {
        cargarVideoBlog(indiceVideoActual + delta);
    };

    // --- Funciones para el Modal de PDF ---
    window.cargarModalPdf = function (key) {
        initPdfUrls();
        const item = pdfsData[key];
        if (!item || !item.url) return;

        const pdfTitle = document.getElementById('modalBlogPdfLabel');
        const pdfSubtitle = document.getElementById('blogPdfSubtitle');
        const pdfDownloadBtn = document.getElementById('blogPdfDownloadBtn');
        const pdfIframe = document.getElementById('blogPdfIframe');
        const pdfFallback = document.getElementById('blogPdfFallbackLink');

        if (pdfTitle) pdfTitle.textContent = item.titulo;
        if (pdfSubtitle) pdfSubtitle.textContent = item.archivo;
        if (pdfDownloadBtn) pdfDownloadBtn.href = item.url;
        if (pdfFallback) pdfFallback.href = item.url;
        if (pdfIframe) pdfIframe.src = item.url;

        // Actualizar pestañas activas dentro del modal de PDF
        document.querySelectorAll('[data-pdf-tab]').forEach(tab => {
            if (tab.getAttribute('data-pdf-tab') === key) {
                tab.className = 'btn btn-sm btn-cyan fw-bold rounded-pill px-3 shadow-2xs';
            } else {
                tab.className = 'btn btn-sm btn-outline-secondary rounded-pill px-3';
            }
        });
    };

    window.abrirModalPdf = function (key) {
        window.cargarModalPdf(key);
        const modalEl = document.getElementById('modalBlogPdf');
        if (modalEl && typeof bootstrap !== 'undefined') {
            const instance = bootstrap.Modal.getOrCreateInstance(modalEl);
            if (!modalEl.classList.contains('show')) {
                instance.show();
            }
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        initPdfUrls();

        const modalVideoEl = document.getElementById('modalBlogVideos');
        if (modalVideoEl) {
            modalVideoEl.addEventListener('show.bs.modal', function (event) {
                const trigger = event.relatedTarget;
                if (trigger) {
                    const apartadoAttr = trigger.getAttribute('data-blog-apartado') || trigger.getAttribute('data-apartado');
                    const num = parseInt(apartadoAttr, 10);
                    if (!isNaN(num)) {
                        window.prepararModalVideos(num);
                    }
                }
            });

            modalVideoEl.addEventListener('hidden.bs.modal', function () {
                const videoIframe = document.getElementById('blogVideoIframe');
                if (videoIframe) {
                    videoIframe.src = '';
                }
            });
        }

        const modalPdfEl = document.getElementById('modalBlogPdf');
        if (modalPdfEl) {
            modalPdfEl.addEventListener('show.bs.modal', function (event) {
                const trigger = event.relatedTarget;
                if (trigger) {
                    const key = trigger.getAttribute('data-blog-pdf') || trigger.getAttribute('data-pdf-key') || 'abp';
                    window.cargarModalPdf(key);
                }
            });

            modalPdfEl.addEventListener('hidden.bs.modal', function () {
                const pdfIframe = document.getElementById('blogPdfIframe');
                if (pdfIframe) {
                    pdfIframe.src = '';
                }
            });
        }

        // Navegación de video prev/next
        const prevBtn = document.getElementById('blogVideoPrevBtn');
        const nextBtn = document.getElementById('blogVideoNextBtn');
        if (prevBtn) prevBtn.addEventListener('click', () => window.blogNavegarVideo(-1));
        if (nextBtn) nextBtn.addEventListener('click', () => window.blogNavegarVideo(1));

        // Event delegation para botones data-blog-video
        document.querySelectorAll('[data-blog-video]').forEach(btn => {
            btn.addEventListener('click', function () {
                const apartado = parseInt(this.getAttribute('data-blog-video'), 10);
                window.abrirModalVideos(apartado);
            });
        });

        // Event delegation para botones data-blog-pdf
        document.querySelectorAll('[data-blog-pdf]').forEach(btn => {
            btn.addEventListener('click', function () {
                const key = this.getAttribute('data-blog-pdf');
                window.abrirModalPdf(key);
            });
        });

        // Pestañas dentro del modal PDF
        document.querySelectorAll('[data-pdf-tab]').forEach(tab => {
            tab.addEventListener('click', function () {
                const key = this.getAttribute('data-pdf-tab');
                window.cargarModalPdf(key);
            });
        });
    });
})();

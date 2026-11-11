/**
 * =====================================================================
 * Física: Causa, Efecto y Simulación · Controlador Frontend UI/UX
 * Maneja:
 *  1. Navegación Split-Screen (30% / 70%) y progreso por lección (0/4)
 *  2. Notas personales, acordeón de Tips ABP y re-renderizado MathJax
 *  3. Modal Pop-up con Backdrop Blur para vídeos de YouTube
 *  4. 5 Simuladores / Retos ABP interactivos (Vectores, Cañón, Newton DCL,
 *     Montaña Rusa de Joules y Mesa de Billar de Colisiones)
 *  5. Impresión / Exportación a PDF de las 5 Guías Visuales HTML/CSS
 *  6. Encuesta Escala Likert (1-5) y desbloqueo de Certificado PDF
 * =====================================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    const wrapper = document.getElementById('fisicaCursoApp');
    if (!wrapper) return;

    // -----------------------------------------------------------------
    // 1. Estado de Navegación y Progreso del Curso
    // -----------------------------------------------------------------
    const STORAGE_KEY = 'lv_fisica_completed_v1';
    const NOTES_STORAGE_KEY = 'lv_fisica_notes_v1';
    let completedItems = new Set();
    let savedNotes = {};

    try {
        const saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        if (Array.isArray(saved)) completedItems = new Set(saved);
    } catch (_) {}

    try {
        savedNotes = JSON.parse(localStorage.getItem(NOTES_STORAGE_KEY) || '{}') || {};
    } catch (_) {}

    const navButtons = wrapper.querySelectorAll('[data-fis-item]');
    const contentPanes = wrapper.querySelectorAll('[data-fis-pane]');
    const totalItemsCount = navButtons.length; // 22 elementos (5 lecciones * 4 + 2 cierre)

    function saveProgress() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(Array.from(completedItems)));
        } catch (_) {}
    }

    function typesetMath() {
        if (window.MathJax && typeof window.MathJax.typesetPromise === 'function') {
            window.MathJax.typesetPromise().catch(() => {});
        }
    }

    function updateProgressCounters() {
        navButtons.forEach(btn => {
            const itemId = btn.getAttribute('data-fis-item');
            if (completedItems.has(itemId)) {
                btn.classList.add('is-completed');
            }
        });

        for (let lesson = 1; lesson <= 6; lesson++) {
            const lessonBtns = wrapper.querySelectorAll(`[data-fis-lesson="${lesson}"]`);
            const badge = document.getElementById(`fisLessonCount-${lesson}`);
            if (badge && lessonBtns.length > 0) {
                let done = 0;
                lessonBtns.forEach(b => {
                    if (completedItems.has(b.getAttribute('data-fis-item'))) done++;
                });
                badge.textContent = `${done}/${lessonBtns.length} elementos`;
            }
        }

        const completedTotal = completedItems.size;
        const pct = totalItemsCount > 0 ? Math.round((completedTotal / totalItemsCount) * 100) : 0;
        const globalBar = document.getElementById('fisGlobalProgressBar');
        const globalText = document.getElementById('fisGlobalProgressText');
        if (globalBar) globalBar.style.width = `${pct}%`;
        if (globalText) globalText.textContent = `${completedTotal}/${totalItemsCount} elementos (${pct}%)`;
    }

    function activatePane(itemId, scrollWorkspace = true) {
        navButtons.forEach(btn => {
            btn.classList.toggle('is-active', btn.getAttribute('data-fis-item') === itemId);
        });

        contentPanes.forEach(pane => {
            pane.classList.toggle('is-active', pane.getAttribute('data-fis-pane') === itemId);
        });

        completedItems.add(itemId);
        saveProgress();
        updateProgressCounters();
        typesetMath();

        const activeBtn = wrapper.querySelector(`[data-fis-item="${itemId}"]`);
        if (activeBtn) {
            const collapseEl = activeBtn.closest('.accordion-collapse');
            if (collapseEl && !collapseEl.classList.contains('show') && window.bootstrap) {
                bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false }).show();
            }
        }

        if (scrollWorkspace && window.innerWidth < 992) {
            const mainPanel = document.getElementById('fisMainWorkspace');
            if (mainPanel) mainPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // -----------------------------------------------------------------
    // 2. Notas Personales ("📝 Guardar nota") y Botones Feedback (👍 👎)
    // -----------------------------------------------------------------
    wrapper.querySelectorAll('[data-fis-note-toggle]').forEach(btn => {
        const key = btn.getAttribute('data-fis-note-toggle');
        const drawer = document.getElementById(`fisNoteDrawer-${key}`);
        const textarea = document.getElementById(`fisNoteInput-${key}`);
        const saveBtn = document.getElementById(`fisNoteSave-${key}`);
        const statusEl = document.getElementById(`fisNoteStatus-${key}`);

        if (textarea && savedNotes[key]) {
            textarea.value = savedNotes[key];
        }

        btn.addEventListener('click', () => {
            if (drawer) drawer.classList.toggle('d-none');
        });

        if (saveBtn && textarea) {
            saveBtn.addEventListener('click', () => {
                savedNotes[key] = textarea.value;
                try {
                    localStorage.setItem(NOTES_STORAGE_KEY, JSON.stringify(savedNotes));
                } catch (_) {}
                if (statusEl) {
                    statusEl.textContent = '✓ Nota guardada en tu navegador';
                    setTimeout(() => { statusEl.textContent = ''; }, 2500);
                }
            });
        }
    });

    wrapper.querySelectorAll('[data-fis-feedback]').forEach(btn => {
        btn.addEventListener('click', () => {
            const group = btn.closest('.fis-feedback-group');
            if (group) {
                group.querySelectorAll('.fis-feedback-btn').forEach(b => b.classList.remove('is-selected'));
            }
            btn.classList.add('is-selected');
            const msg = group?.querySelector('.fis-feedback-msg');
            if (msg) msg.textContent = '¡Gracias por tu retroalimentación!';
        });
    });

    // -----------------------------------------------------------------
    // 3. Ventana Emergente (Modal con Backdrop Blur) para Vídeos YouTube
    // -----------------------------------------------------------------
    const videoModalEl = document.getElementById('fisYoutubeModal');
    const videoIframe = document.getElementById('fisYoutubeIframe');
    const videoModalTitle = document.getElementById('fisYoutubeModalTitle');
    const videoModalChannel = document.getElementById('fisYoutubeModalChannel');
    const videoModalDirectLink = document.getElementById('fisYoutubeDirectLink');
    const videoModalAltBtn = document.getElementById('fisYoutubeAltBtn');
    const videoModalPlaylistWrap = document.getElementById('fisYoutubePlaylistWrap');
    const videoModalPlaylistLink = document.getElementById('fisYoutubePlaylistLink');

    let currentVideoData = null;
    let showingAltVideo = false;

    function renderModalVideoState() {
        if (!currentVideoData || !videoIframe) return;
        const activeEmbed = showingAltVideo && currentVideoData.altEmbed ? currentVideoData.altEmbed : currentVideoData.embedUrl;
        const activeWatch = showingAltVideo && currentVideoData.altUrl ? currentVideoData.altUrl : currentVideoData.watchUrl;
        const activeTitle = showingAltVideo && currentVideoData.altTitle ? currentVideoData.altTitle : currentVideoData.title;
        const activeChannel = showingAltVideo && currentVideoData.altChannel ? currentVideoData.altChannel : currentVideoData.channel;

        if (videoModalTitle) videoModalTitle.textContent = activeTitle || 'Vídeo de la Lección';
        if (videoModalChannel) videoModalChannel.textContent = activeChannel || 'Canal Oficial';
        if (videoModalDirectLink) videoModalDirectLink.href = activeWatch || 'https://youtube.com';
        videoIframe.src = activeEmbed || '';

        if (videoModalAltBtn) {
            if (currentVideoData.altEmbed) {
                videoModalAltBtn.classList.remove('d-none');
                videoModalAltBtn.textContent = showingAltVideo
                    ? '↺ Volver al Video Recomendado Principal'
                    : `🎬 Ver Video Alternativo: ${currentVideoData.altShort || 'Explicación Corta'}`;
            } else {
                videoModalAltBtn.classList.add('d-none');
            }
        }

        if (currentVideoData.playlistUrl && videoModalPlaylistWrap && videoModalPlaylistLink) {
            videoModalPlaylistWrap.classList.remove('d-none');
            videoModalPlaylistLink.href = currentVideoData.playlistUrl;
        } else if (videoModalPlaylistWrap) {
            videoModalPlaylistWrap.classList.add('d-none');
        }
    }

    function openVideoModal(data, startAlt = false) {
        if (!videoModalEl || !videoIframe) return;
        currentVideoData = data;
        showingAltVideo = startAlt;
        renderModalVideoState();

        if (window.bootstrap) {
            const modal = bootstrap.Modal.getOrCreateInstance(videoModalEl);
            modal.show();
        }
    }

    if (videoModalAltBtn) {
        videoModalAltBtn.addEventListener('click', () => {
            showingAltVideo = !showingAltVideo;
            renderModalVideoState();
        });
    }

    if (videoModalEl && videoIframe) {
        videoModalEl.addEventListener('hidden.bs.modal', () => {
            videoIframe.src = '';
        });
    }

    navButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const itemId = btn.getAttribute('data-fis-item');
            const isVideo = btn.getAttribute('data-fis-type') === 'video';

            activatePane(itemId, !isVideo);

            if (isVideo) {
                openVideoModal({
                    title: btn.getAttribute('data-video-title'),
                    channel: btn.getAttribute('data-video-channel'),
                    embedUrl: btn.getAttribute('data-video-embed'),
                    watchUrl: btn.getAttribute('data-video-url'),
                    altTitle: btn.getAttribute('data-video-alt-title'),
                    altChannel: btn.getAttribute('data-video-alt-channel'),
                    altEmbed: btn.getAttribute('data-video-alt-embed'),
                    altUrl: btn.getAttribute('data-video-alt-url'),
                    altShort: btn.getAttribute('data-video-alt-short'),
                    playlistUrl: btn.getAttribute('data-video-playlist')
                }, false);
            }
        });
    });

    wrapper.querySelectorAll('[data-open-fis-video]').forEach(trigger => {
        trigger.addEventListener('click', () => {
            const targetItem = trigger.getAttribute('data-open-fis-video');
            const useAlt = trigger.getAttribute('data-video-mode') === 'alt';
            const sidebarBtn = wrapper.querySelector(`[data-fis-item="${targetItem}"]`);
            if (!sidebarBtn) return;

            activatePane(targetItem, false);
            openVideoModal({
                title: sidebarBtn.getAttribute('data-video-title'),
                channel: sidebarBtn.getAttribute('data-video-channel'),
                embedUrl: sidebarBtn.getAttribute('data-video-embed'),
                watchUrl: sidebarBtn.getAttribute('data-video-url'),
                altTitle: sidebarBtn.getAttribute('data-video-alt-title'),
                altChannel: sidebarBtn.getAttribute('data-video-alt-channel'),
                altEmbed: sidebarBtn.getAttribute('data-video-alt-embed'),
                altUrl: sidebarBtn.getAttribute('data-video-alt-url'),
                altShort: sidebarBtn.getAttribute('data-video-alt-short'),
                playlistUrl: sidebarBtn.getAttribute('data-video-playlist')
            }, useAlt);
        });
    });

    wrapper.querySelectorAll('[data-fis-goto]').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-fis-goto');
            const sidebarBtn = wrapper.querySelector(`[data-fis-item="${targetId}"]`);
            if (sidebarBtn) sidebarBtn.click();
        });
    });

    // Inicializar estado de progreso
    completedItems.add('l1-lectura');
    saveProgress();
    updateProgressCounters();

    // -----------------------------------------------------------------
    // 4. SIMULADOR LECCIÓN 1: Plano Cartesiano Interactivo (Vectores X, Y)
    // -----------------------------------------------------------------
    const l1MagSlider = document.getElementById('fisL1MagSlider');
    const l1AngleSlider = document.getElementById('fisL1AngleSlider');
    const l1Svg = document.getElementById('fisL1CartesianSvg');
    const l1VecLine = document.getElementById('fisL1VecLine');
    const l1VecHead = document.getElementById('fisL1VecHead');
    const l1CompXLine = document.getElementById('fisL1CompXLine');
    const l1CompYLine = document.getElementById('fisL1CompYLine');
    const l1ArcPath = document.getElementById('fisL1ArcPath');
    const l1MagBadge = document.getElementById('fisL1MagBadge');
    const l1AngleBadge = document.getElementById('fisL1AngleBadge');
    const l1QuadBadge = document.getElementById('fisL1QuadBadge');
    const l1VxDisplay = document.getElementById('fisL1VxDisplay');
    const l1VyDisplay = document.getElementById('fisL1VyDisplay');
    const l1PitDisplay = document.getElementById('fisL1PitDisplay');
    const l1Console = document.getElementById('fisL1Console');
    const l1StepBtn = document.getElementById('fisL1StepBtn');
    const l1ResetBtn = document.getElementById('fisL1ResetBtn');

    if (l1MagSlider && l1AngleSlider && l1Svg) {
        const originX = 160;
        const originY = 140;
        const scalePx = 1.15; // 100 N -> 115 px
        const demoVectors = [
            { v: 60, deg: 45 },
            { v: 80, deg: 135 },
            { v: 75, deg: 225 },
            { v: 90, deg: 315 },
            { v: 100, deg: 30 }
        ];
        let demoIdx = 0;

        function getQuadrantInfo(deg) {
            const norm = ((deg % 360) + 360) % 360;
            if (norm === 0 || norm === 90 || norm === 180 || norm === 270) {
                return { label: `Sobre Eje (${norm}°)`, color: 'bg-info text-dark' };
            }
            if (norm > 0 && norm < 90) return { label: 'Cuadrante I (+X, +Y)', color: 'bg-success text-white' };
            if (norm > 90 && norm < 180) return { label: 'Cuadrante II (-X, +Y)', color: 'bg-primary text-white' };
            if (norm > 180 && norm < 270) return { label: 'Cuadrante III (-X, -Y)', color: 'bg-warning text-dark' };
            return { label: 'Cuadrante IV (+X, -Y)', color: 'bg-danger text-white' };
        }

        function updateL1Vector(V, deg) {
            const rad = (deg * Math.PI) / 180;
            const vx = V * Math.cos(rad);
            const vy = V * Math.sin(rad);
            const endX = originX + vx * scalePx;
            const endY = originY - vy * scalePx;

            if (l1VecLine) {
                l1VecLine.setAttribute('x2', endX.toFixed(1));
                l1VecLine.setAttribute('y2', endY.toFixed(1));
            }
            if (l1VecHead) {
                l1VecHead.setAttribute('cx', endX.toFixed(1));
                l1VecHead.setAttribute('cy', endY.toFixed(1));
            }
            if (l1CompXLine) {
                l1CompXLine.setAttribute('x1', originX);
                l1CompXLine.setAttribute('y1', originY);
                l1CompXLine.setAttribute('x2', endX.toFixed(1));
                l1CompXLine.setAttribute('y2', originY);
            }
            if (l1CompYLine) {
                l1CompYLine.setAttribute('x1', endX.toFixed(1));
                l1CompYLine.setAttribute('y1', originY);
                l1CompYLine.setAttribute('x2', endX.toFixed(1));
                l1CompYLine.setAttribute('y2', endY.toFixed(1));
            }
            if (l1ArcPath) {
                const rArc = 26;
                const arcX = originX + rArc * Math.cos(rad);
                const arcY = originY - rArc * Math.sin(rad);
                const largeArc = deg > 180 ? 1 : 0;
                l1ArcPath.setAttribute('d', `M ${originX + rArc} ${originY} A ${rArc} ${rArc} 0 ${largeArc} 0 ${arcX.toFixed(1)} ${arcY.toFixed(1)}`);
            }

            const qInfo = getQuadrantInfo(deg);
            if (l1MagBadge) l1MagBadge.textContent = `|V| = ${V.toFixed(0)} N`;
            if (l1AngleBadge) l1AngleBadge.textContent = `θ = ${deg.toFixed(0)}°`;
            if (l1QuadBadge) {
                l1QuadBadge.className = `badge font-monospace ${qInfo.color}`;
                l1QuadBadge.textContent = qInfo.label;
            }

            if (l1VxDisplay) l1VxDisplay.textContent = `${vx >= 0 ? '+' : ''}${vx.toFixed(2)} N`;
            if (l1VyDisplay) l1VyDisplay.textContent = `${vy >= 0 ? '+' : ''}${vy.toFixed(2)} N`;
            const pit = Math.sqrt(vx * vx + vy * vy);
            if (l1PitDisplay) l1PitDisplay.textContent = `${pit.toFixed(2)} N`;

            if (l1Console) {
                l1Console.innerHTML = `
                    <div class="text-teal fw-bold mb-1">&gt; [Vector Activo] Magnitud V = ${V.toFixed(0)} N | Dirección θ = ${deg.toFixed(0)}° (${qInfo.label})</div>
                    <div>&gt; Descomposición rectangular: <strong>Vₓ = ${V.toFixed(0)} · cos(${deg.toFixed(0)}°) = ${vx.toFixed(2)} N</strong> | <strong>Vᵧ = ${V.toFixed(0)} · sin(${deg.toFixed(0)}°) = ${vy.toFixed(2)} N</strong></div>
                    <div class="text-emerald">&gt; Comprobación Teorema de Pitágoras: √((${vx.toFixed(2)})² + (${vy.toFixed(2)})²) = <strong>${pit.toFixed(2)} N</strong> ✓</div>
                `;
            }
        }

        l1MagSlider.addEventListener('input', () => {
            updateL1Vector(parseFloat(l1MagSlider.value), parseFloat(l1AngleSlider.value));
        });

        l1AngleSlider.addEventListener('input', () => {
            updateL1Vector(parseFloat(l1MagSlider.value), parseFloat(l1AngleSlider.value));
        });

        // Soporte para arrastrar la punta de la flecha directamente en el plano cartesiano SVG
        let draggingVec = false;
        function handleSvgPointer(e) {
            if (!draggingVec) return;
            const rect = l1Svg.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            const svgX = ((clientX - rect.left) / rect.width) * 320;
            const svgY = ((clientY - rect.top) / rect.height) * 280;

            const dx = (svgX - originX) / scalePx;
            const dy = (originY - svgY) / scalePx;
            let mag = Math.min(100, Math.max(10, Math.round(Math.sqrt(dx * dx + dy * dy))));
            let angle = Math.round((Math.atan2(dy, dx) * 180) / Math.PI);
            if (angle < 0) angle += 360;

            l1MagSlider.value = mag;
            l1AngleSlider.value = angle;
            updateL1Vector(mag, angle);
        }

        l1Svg.addEventListener('mousedown', () => { draggingVec = true; });
        window.addEventListener('mouseup', () => { draggingVec = false; });
        l1Svg.addEventListener('mousemove', handleSvgPointer);
        l1Svg.addEventListener('touchstart', () => { draggingVec = true; }, { passive: true });
        window.addEventListener('touchend', () => { draggingVec = false; });
        l1Svg.addEventListener('touchmove', handleSvgPointer, { passive: true });

        if (l1StepBtn) {
            l1StepBtn.addEventListener('click', () => {
                const sample = demoVectors[demoIdx % demoVectors.length];
                demoIdx++;
                l1MagSlider.value = sample.v;
                l1AngleSlider.value = sample.deg;
                updateL1Vector(sample.v, sample.deg);
            });
        }

        if (l1ResetBtn) {
            l1ResetBtn.addEventListener('click', () => {
                l1MagSlider.value = 60;
                l1AngleSlider.value = 45;
                updateL1Vector(60, 45);
            });
        }

        updateL1Vector(60, 45);
    }

    // -----------------------------------------------------------------
    // 5. RETO ABP LECCIÓN 2: El Cañón · Ecuaciones de Movimiento (Ref. PhET)
    // -----------------------------------------------------------------
    const l2VelSlider = document.getElementById('fisL2VelSlider');
    const l2AngleSlider = document.getElementById('fisL2AngleSlider');
    const l2TargetSelect = document.getElementById('fisL2TargetSelect');
    const l2FireBtn = document.getElementById('fisL2FireBtn');
    const l2SolveBtn = document.getElementById('fisL2SolveBtn');
    const l2TrajPath = document.getElementById('fisL2TrajPath');
    const l2ImpactCircle = document.getElementById('fisL2ImpactCircle');
    const l2TargetGroup = document.getElementById('fisL2TargetGroup');
    const l2XmaxBadge = document.getElementById('fisL2XmaxBadge');
    const l2YmaxBadge = document.getElementById('fisL2YmaxBadge');
    const l2TimeBadge = document.getElementById('fisL2TimeBadge');
    const l2VelLabel = document.getElementById('fisL2VelLabel');
    const l2AngleLabel = document.getElementById('fisL2AngleLabel');
    const l2Console = document.getElementById('fisL2Console');

    if (l2VelSlider && l2AngleSlider && l2FireBtn) {
        const g = 9.81;
        // SVG coordenadas: cañón en (35, 195), escala horizontal = 2.2 px/m (hasta 120m -> 299px)
        const cannonX = 35;
        const groundY = 195;
        const scaleM = 2.15;

        function simulateCannon() {
            const vi = parseFloat(l2VelSlider.value);
            const thetaDeg = parseFloat(l2AngleSlider.value);
            const targetM = parseFloat(l2TargetSelect ? l2TargetSelect.value : '88.3');
            const rad = (thetaDeg * Math.PI) / 180;

            if (l2VelLabel) l2VelLabel.textContent = `${vi} m/s`;
            if (l2AngleLabel) l2AngleLabel.textContent = `${thetaDeg}°`;

            const xMax = (vi * vi * Math.sin(2 * rad)) / g;
            const yMax = (vi * vi * Math.pow(Math.sin(rad), 2)) / (2 * g);
            const tFlight = (2 * vi * Math.sin(rad)) / g;

            if (l2XmaxBadge) l2XmaxBadge.textContent = `x_max = ${xMax.toFixed(1)} m`;
            if (l2YmaxBadge) l2YmaxBadge.textContent = `y_max = ${yMax.toFixed(1)} m`;
            if (l2TimeBadge) l2TimeBadge.textContent = `t = ${tFlight.toFixed(2)} s`;

            // Posicionar diana objetivo
            const targetSvgX = cannonX + targetM * scaleM;
            if (l2TargetGroup) {
                l2TargetGroup.setAttribute('transform', `translate(${targetSvgX.toFixed(1)}, ${groundY})`);
            }

            // Construir puntos de la parábola
            let pts = [];
            const steps = 30;
            for (let i = 0; i <= steps; i++) {
                const t = (tFlight * i) / steps;
                const x = vi * Math.cos(rad) * t;
                const y = vi * Math.sin(rad) * t - 0.5 * g * t * t;
                const sx = Math.min(315, cannonX + x * scaleM);
                const sy = Math.max(12, groundY - y * scaleM);
                pts.push(`${i === 0 ? 'M' : 'L'} ${sx.toFixed(1)} ${sy.toFixed(1)}`);
            }
            if (l2TrajPath) l2TrajPath.setAttribute('d', pts.join(' '));

            const impactSvgX = Math.min(315, cannonX + xMax * scaleM);
            if (l2ImpactCircle) {
                l2ImpactCircle.setAttribute('cx', impactSvgX.toFixed(1));
                l2ImpactCircle.setAttribute('cy', groundY);
            }

            const errorDist = Math.abs(xMax - targetM);
            const hit = errorDist <= 2.5;

            if (l2ImpactCircle) {
                l2ImpactCircle.setAttribute('fill', hit ? '#10b981' : '#f43f5e');
            }

            if (l2Console) {
                if (hit) {
                    l2Console.innerHTML = `
                        <div class="text-emerald fw-bold mb-1">&gt; 🎯 ¡IMPACTO DIRECTO EN EL OBJETIVO (${targetM} m)!</div>
                        <div>&gt; Condición cumplida: x_max = (${vi}² · sin(2·${thetaDeg}°)) / 9.81 = <strong>${xMax.toFixed(2)} m</strong> (Error: ${errorDist.toFixed(2)} m ≤ 2.5 m).</div>
                        <div class="text-teal">&gt; MRU en X: vₓ = ${(vi * Math.cos(rad)).toFixed(2)} m/s constante | MRUA en Y: vᵧ₀ = ${(vi * Math.sin(rad)).toFixed(2)} m/s con g = -9.81 m/s².</div>
                    `;
                } else {
                    const hint = xMax < targetM
                        ? 'El proyectil cayó corto. Aumenta la velocidad inicial vᵢ o acerca el ángulo θ a 45°.'
                        : 'El proyectil se pasó del objetivo. Reduce la velocidad inicial vᵢ o ajusta el ángulo θ.';
                    l2Console.innerHTML = `
                        <div class="text-warning fw-bold mb-1">&gt; Disparo en evaluación: Alcance logrado x_max = ${xMax.toFixed(2)} m vs Objetivo = ${targetM} m</div>
                        <div>&gt; Diferencia: ${errorDist.toFixed(2)} m. ${hint}</div>
                    `;
                }
            }
        }

        l2VelSlider.addEventListener('input', simulateCannon);
        l2AngleSlider.addEventListener('input', simulateCannon);
        if (l2TargetSelect) l2TargetSelect.addEventListener('change', simulateCannon);
        l2FireBtn.addEventListener('click', simulateCannon);

        if (l2SolveBtn) {
            l2SolveBtn.addEventListener('click', () => {
                const targetM = parseFloat(l2TargetSelect ? l2TargetSelect.value : '88.3');
                if (Math.abs(targetM - 88.3) < 1) {
                    l2VelSlider.value = 30;
                    l2AngleSlider.value = 38;
                } else if (Math.abs(targetM - 65.0) < 1) {
                    l2VelSlider.value = 26;
                    l2AngleSlider.value = 37;
                } else {
                    l2VelSlider.value = 32;
                    l2AngleSlider.value = 45;
                }
                simulateCannon();
            });
        }

        simulateCannon();
    }

    // -----------------------------------------------------------------
    // 6. SIMULADOR LECCIÓN 3: Leyes de Newton · Fricción Ajustable y DCL
    // -----------------------------------------------------------------
    const l3ForceSlider = document.getElementById('fisL3ForceSlider');
    const l3MassSlider = document.getElementById('fisL3MassSlider');
    const l3MuSelect = document.getElementById('fisL3MuSelect');
    const l3StepBtn = document.getElementById('fisL3StepBtn');
    const l3ResetBtn = document.getElementById('fisL3ResetBtn');
    const l3StateBadge = document.getElementById('fisL3StateBadge');
    const l3AccelBadge = document.getElementById('fisL3AccelBadge');
    const l3FnetBadge = document.getElementById('fisL3FnetBadge');
    const l3VecFapp = document.getElementById('fisL3VecFapp');
    const l3VecFr = document.getElementById('fisL3VecFr');
    const l3LabelFapp = document.getElementById('fisL3LabelFapp');
    const l3LabelFr = document.getElementById('fisL3LabelFr');
    const l3LabelWN = document.getElementById('fisL3LabelWN');
    const l3Console = document.getElementById('fisL3Console');

    if (l3ForceSlider && l3MassSlider && l3MuSelect) {
        const g = 9.81;

        function updateL3Newton() {
            const fApp = parseFloat(l3ForceSlider.value);
            const mass = parseFloat(l3MassSlider.value);
            const mu = parseFloat(l3MuSelect.value);

            const weight = mass * g;
            const normal = weight;
            const frMax = mu * normal;

            const moving = fApp > frMax;
            const frActual = moving ? frMax : fApp;
            const fNet = moving ? fApp - frMax : 0;
            const accel = moving ? fNet / mass : 0;

            const fAppLen = Math.min(110, Math.max(18, (fApp / 400) * 105));
            const frLen = Math.min(110, Math.max(18, (frActual / 400) * 105));

            if (l3VecFapp) l3VecFapp.setAttribute('x2', (160 + fAppLen).toFixed(1));
            if (l3VecFr) l3VecFr.setAttribute('x2', (160 - frLen).toFixed(1));
            if (l3LabelFapp) l3LabelFapp.textContent = `F = ${fApp.toFixed(0)} N →`;
            if (l3LabelFr) l3LabelFr.textContent = `← f_r = ${frActual.toFixed(1)} N`;
            if (l3LabelWN) l3LabelWN.textContent = `N = W = ${weight.toFixed(1)} N`;

            if (l3StateBadge) {
                l3StateBadge.className = moving
                    ? 'badge bg-emerald text-white font-monospace'
                    : 'badge bg-warning text-dark font-monospace';
                l3StateBadge.textContent = moving ? 'ESTADO: EN MOVIMIENTO (2da Ley)' : 'ESTADO: EN REPOSO (1ra Ley Inercia)';
            }
            if (l3AccelBadge) l3AccelBadge.textContent = `a = ${accel.toFixed(2)} m/s²`;
            if (l3FnetBadge) l3FnetBadge.textContent = `∑F = ${fNet.toFixed(1)} N`;

            if (l3Console) {
                if (moving) {
                    l3Console.innerHTML = `
                        <div class="text-emerald fw-bold mb-1">&gt; [CONDICIÓN TRUE] if (F_empuje [${fApp} N] &gt; f_r_max [${frMax.toFixed(1)} N]) ⇒ estado = "EN MOVIMIENTO"</div>
                        <div>&gt; Fuerza Normal N = ${mass} kg · 9.81 m/s² = ${normal.toFixed(1)} N | Fricción máxima f_r = ${mu} · ${normal.toFixed(1)} = ${frMax.toFixed(1)} N</div>
                        <div class="text-teal">&gt; 2da Ley de Newton: ∑F = ${fApp} - ${frMax.toFixed(1)} = <strong>${fNet.toFixed(1)} N</strong> ⇒ Aceleración a = ∑F / m = <strong>${accel.toFixed(2)} m/s²</strong></div>
                    `;
                } else {
                    l3Console.innerHTML = `
                        <div class="text-warning fw-bold mb-1">&gt; [CONDICIÓN FALSE] if (F_empuje [${fApp} N] &lt;= f_r_max [${frMax.toFixed(1)} N]) ⇒ estado = "SIN CAMBIO (REPOSO)"</div>
                        <div>&gt; La fricción estática iguala tu empuje (${fApp} N). Necesitas superar <strong>${frMax.toFixed(1)} N</strong> para vencer la inercia.</div>
                    `;
                }
            }
        }

        l3ForceSlider.addEventListener('input', updateL3Newton);
        l3MassSlider.addEventListener('input', updateL3Newton);
        l3MuSelect.addEventListener('change', updateL3Newton);

        if (l3StepBtn) {
            l3StepBtn.addEventListener('click', () => {
                const mass = parseFloat(l3MassSlider.value);
                const mu = parseFloat(l3MuSelect.value);
                const needed = Math.ceil(mass * g * mu + 45);
                l3ForceSlider.value = Math.min(400, needed);
                updateL3Newton();
            });
        }

        if (l3ResetBtn) {
            l3ResetBtn.addEventListener('click', () => {
                l3ForceSlider.value = 80;
                l3MassSlider.value = 30;
                l3MuSelect.value = '0.35';
                updateL3Newton();
            });
        }

        updateL3Newton();
    }

    // -----------------------------------------------------------------
    // 7. RETO ABP LECCIÓN 4: Montaña Rusa · Conservación de Energía (Joules)
    // -----------------------------------------------------------------
    const l4HeightSlider = document.getElementById('fisL4HeightSlider');
    const l4PointSelect = document.getElementById('fisL4PointSelect');
    const l4StepBtn = document.getElementById('fisL4StepBtn');
    const l4CarMarker = document.getElementById('fisL4CarMarker');
    const l4EpBar = document.getElementById('fisL4EpBar');
    const l4EcBar = document.getElementById('fisL4EcBar');
    const l4EtotBar = document.getElementById('fisL4EtotBar');
    const l4EpVal = document.getElementById('fisL4EpVal');
    const l4EcVal = document.getElementById('fisL4EcVal');
    const l4EtotVal = document.getElementById('fisL4EtotVal');
    const l4VelBadge = document.getElementById('fisL4VelBadge');
    const l4LoopBadge = document.getElementById('fisL4LoopBadge');
    const l4Console = document.getElementById('fisL4Console');

    if (l4HeightSlider && l4PointSelect) {
        const mass = 100; // 100 kg carrito
        const g = 9.81;
        const loopRadius = 8; // Radio del bucle R = 8m -> Altura cima bucle = 16m, Altura mínima teórica = 2.5*R = 20m

        function updateL4Energy() {
            const hA = parseFloat(l4HeightSlider.value);
            const point = l4PointSelect.value; // 'A', 'B', 'C'
            const eTotal = mass * g * hA;

            let hCurrent = hA;
            let carX = 42;
            let carY = 165 - (hA / 35) * 130;

            if (point === 'B') {
                hCurrent = 0;
                carX = 135;
                carY = 168;
            } else if (point === 'C') {
                hCurrent = 16; // Cima del bucle (2R = 16 m)
                carX = 215;
                carY = 82;
            }

            const ep = Math.max(0, mass * g * hCurrent);
            const ec = Math.max(0, eTotal - ep);
            const vel = hA >= hCurrent ? Math.sqrt((2 * ec) / mass) : 0;
            const passesLoop = hA >= 2.5 * loopRadius; // h_min = 2.5 * 8 = 20 m

            if (l4CarMarker) {
                l4CarMarker.setAttribute('cx', carX.toFixed(1));
                l4CarMarker.setAttribute('cy', carY.toFixed(1));
            }

            const maxRefJ = 100 * 9.81 * 35; // 34335 J
            const epPct = Math.min(100, Math.round((ep / maxRefJ) * 100));
            const ecPct = Math.min(100, Math.round((ec / maxRefJ) * 100));
            const etPct = Math.min(100, Math.round((eTotal / maxRefJ) * 100));

            if (l4EpBar) l4EpBar.style.height = `${epPct}%`;
            if (l4EcBar) l4EcBar.style.height = `${ecPct}%`;
            if (l4EtotBar) l4EtotBar.style.height = `${etPct}%`;

            if (l4EpVal) l4EpVal.textContent = `${(ep / 1000).toFixed(1)} kJ`;
            if (l4EcVal) l4EcVal.textContent = `${(ec / 1000).toFixed(1)} kJ`;
            if (l4EtotVal) l4EtotVal.textContent = `${(eTotal / 1000).toFixed(1)} kJ`;
            if (l4VelBadge) l4VelBadge.textContent = `v = ${vel.toFixed(2)} m/s`;

            if (l4LoopBadge) {
                l4LoopBadge.className = passesLoop
                    ? 'badge bg-emerald text-white font-monospace'
                    : 'badge bg-danger text-white font-monospace';
                l4LoopBadge.textContent = passesLoop
                    ? `✓ Bucle Superado (h_A = ${hA}m ≥ 20m)`
                    : `⚠️ Riesgo en Bucle (h_A = ${hA}m < 20m)`;
            }

            if (l4Console) {
                l4Console.innerHTML = `
                    <div class="text-teal fw-bold mb-1">&gt; [Punto ${point}] Altura inicial h_A = ${hA} m | Masa m = ${mass} kg | Energía Total Constante = <strong>${eTotal.toFixed(0)} J</strong></div>
                    <div>&gt; Balance en Punto ${point} (h = ${hCurrent} m): <strong>E_p = ${ep.toFixed(0)} J</strong> + <strong>E_c = ${ec.toFixed(0)} J</strong> = <strong>${(ep + ec).toFixed(0)} J</strong> (100% conservada).</div>
                    <div class="${passesLoop ? 'text-emerald' : 'text-warning'}">&gt; ${passesLoop
                        ? '¡Reto superado! Con h_A ≥ 20 m (2.5·R), el carrito conserva suficiente energía cinética en la cima del bucle para no caer.'
                        : 'Atención: Para un bucle de radio R = 8 m, se requiere h_min = 2.5·R = 20 m para vencer la gravedad en la cima.'}</div>
                `;
            }
        }

        l4HeightSlider.addEventListener('input', updateL4Energy);
        l4PointSelect.addEventListener('change', updateL4Energy);

        if (l4StepBtn) {
            const pts = ['A', 'B', 'C'];
            let pIdx = 0;
            l4StepBtn.addEventListener('click', () => {
                if (parseFloat(l4HeightSlider.value) < 20) l4HeightSlider.value = 24;
                pIdx = (pIdx + 1) % pts.length;
                l4PointSelect.value = pts[pIdx];
                updateL4Energy();
            });
        }

        updateL4Energy();
    }

    // -----------------------------------------------------------------
    // 8. SIMULADOR LECCIÓN 5: Mesa de Billar Virtual (Colisiones y Momento)
    // -----------------------------------------------------------------
    const l5TypeSelect = document.getElementById('fisL5TypeSelect');
    const l5M1Input = document.getElementById('fisL5M1Input');
    const l5V1Input = document.getElementById('fisL5V1Input');
    const l5M2Input = document.getElementById('fisL5M2Input');
    const l5V2Input = document.getElementById('fisL5V2Input');
    const l5CollideBtn = document.getElementById('fisL5CollideBtn');
    const l5PInitialBadge = document.getElementById('fisL5PInitialBadge');
    const l5PFinalBadge = document.getElementById('fisL5PFinalBadge');
    const l5V1fDisplay = document.getElementById('fisL5V1fDisplay');
    const l5V2fDisplay = document.getElementById('fisL5V2fDisplay');
    const l5Console = document.getElementById('fisL5Console');

    if (l5TypeSelect && l5M1Input && l5V1Input && l5M2Input && l5V2Input) {
        function simulateCollision() {
            const type = l5TypeSelect.value; // 'elastica' | 'inelastica'
            const m1 = Math.max(0.1, parseFloat(l5M1Input.value) || 1);
            const v1i = parseFloat(l5V1Input.value) || 0;
            const m2 = Math.max(0.1, parseFloat(l5M2Input.value) || 1);
            const v2i = parseFloat(l5V2Input.value) || 0;

            const pInitial = m1 * v1i + m2 * v2i;
            let v1f = 0;
            let v2f = 0;

            if (type === 'elastica') {
                v1f = ((m1 - m2) / (m1 + m2)) * v1i + ((2 * m2) / (m1 + m2)) * v2i;
                v2f = ((2 * m1) / (m1 + m2)) * v1i + ((m2 - m1) / (m1 + m2)) * v2i;
            } else {
                const vfCommon = pInitial / (m1 + m2);
                v1f = vfCommon;
                v2f = vfCommon;
            }

            const pFinal = m1 * v1f + m2 * v2f;
            const ecInitial = 0.5 * m1 * v1i * v1i + 0.5 * m2 * v2i * v2i;
            const ecFinal = 0.5 * m1 * v1f * v1f + 0.5 * m2 * v2f * v2f;

            if (l5PInitialBadge) l5PInitialBadge.textContent = `p_inicial = ${pInitial.toFixed(2)} kg·m/s`;
            if (l5PFinalBadge) l5PFinalBadge.textContent = `p_final = ${pFinal.toFixed(2)} kg·m/s`;
            if (l5V1fDisplay) l5V1fDisplay.textContent = `${v1f >= 0 ? '+' : ''}${v1f.toFixed(2)} m/s`;
            if (l5V2fDisplay) l5V2fDisplay.textContent = `${v2f >= 0 ? '+' : ''}${v2f.toFixed(2)} m/s`;

            if (l5Console) {
                l5Console.innerHTML = `
                    <div class="text-teal fw-bold mb-1">&gt; [Comprobador de Impacto · Colisión ${type === 'elastica' ? 'ELÁSTICA (Rebote perfecto)' : 'INELÁSTICA (Acople)'}]</div>
                    <div>&gt; Cantidad de Movimiento: p_inicial = (${m1})(${v1i}) + (${m2})(${v2i}) = <strong>${pInitial.toFixed(2)} kg·m/s</strong> | p_final = <strong>${pFinal.toFixed(2)} kg·m/s</strong> ✓</div>
                    <div class="text-emerald">&gt; Velocidades finales: <strong>v₁f = ${v1f.toFixed(2)} m/s</strong>, <strong>v₂f = ${v2f.toFixed(2)} m/s</strong> | Energía Cinética: E_ci = ${ecInitial.toFixed(1)} J → E_cf = ${ecFinal.toFixed(1)} J</div>
                `;
            }
        }

        [l5TypeSelect, l5M1Input, l5V1Input, l5M2Input, l5V2Input].forEach(el => {
            el.addEventListener('input', simulateCollision);
            el.addEventListener('change', simulateCollision);
        });
        if (l5CollideBtn) l5CollideBtn.addEventListener('click', simulateCollision);

        simulateCollision();
    }

    // -----------------------------------------------------------------
    // 9. Impresión / Exportación a PDF de cada Guía Visual HTML/CSS
    // -----------------------------------------------------------------
    wrapper.querySelectorAll('[data-print-guide]').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-print-guide');
            const guideTitle = btn.getAttribute('data-guide-title') || 'Guía Visual de Física';
            const guideEl = document.getElementById(targetId);
            if (!guideEl) return;

            const printWin = window.open('', '_blank', 'width=980,height=760');
            if (!printWin) {
                window.print();
                return;
            }

            printWin.document.write(`<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>${guideTitle}</title>
    <script src="https://cdn.tailwindcss.com"><\/script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #ffffff; padding: 24px; color: #1f2937; }
        .code-font { font-family: 'JetBrains Mono', monospace; }
        .border-logic-if { border-left-color: #3b82f6; }
        .border-logic-else { border-left-color: #ef4444; }
        .border-logic-success { border-left-color: #10b981; }
        .math-box { background-color: #1e293b; color: #f8fafc; border-radius: 0.5rem; padding: 0.75rem; text-align: center; font-size: 1.1rem; }
    </style>
    <script>
        window.MathJax = { tex: { inlineMath: [['$', '$'], ['\\\\(', '\\\\)']], displayMath: [['$$', '$$'], ['\\\\[', '\\\\]']] } };
    <\/script>
    <script async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"><\/script>
</head>
<body>
    ${guideEl.innerHTML}
    <script>window.onload = function() { setTimeout(function() { window.print(); }, 700); };<\/script>
</body>
</html>`);
            printWin.document.close();
        });
    });

    // -----------------------------------------------------------------
    // 10. MÓDULO DE CIERRE: Encuesta Escala Likert (1-5) y Certificado PDF
    // -----------------------------------------------------------------
    const surveyForm = document.getElementById('fisLikertSurveyForm');
    const btnQuickFill = document.getElementById('fisSurveyQuickFillBtn');
    const btnCertSurvey = document.getElementById('fisBtnDownloadCertSurvey');
    const btnCertPane = document.getElementById('fisBtnDownloadCertPane');
    const surveyAlert = document.getElementById('fisSurveyStatusAlert');
    const certLockNotice = document.getElementById('fisCertLockNotice');

    const SURVEY_STORAGE_KEY = 'lv_fisica_survey_done_v1';

    function unlockCertificateButtons() {
        [btnCertSurvey, btnCertPane].forEach(btn => {
            if (!btn) return;
            btn.disabled = false;
            btn.removeAttribute('disabled');
            btn.classList.remove('btn-secondary', 'opacity-75');
            btn.classList.add('btn-teal', 'shadow-teal');
            btn.innerHTML = '🎓 Descargar Certificado Oficial de Física (PDF)';
        });

        if (certLockNotice) {
            certLockNotice.className = 'alert alert-success d-flex align-items-center gap-2 py-2 px-3 small mb-3';
            certLockNotice.innerHTML = '<span>✅</span> <div><strong>Encuesta completada:</strong> Tu certificado de acreditación de Física está desbloqueado y listo para descargar en PDF.</div>';
        }
    }

    try {
        if (localStorage.getItem(SURVEY_STORAGE_KEY) === '1') {
            unlockCertificateButtons();
        }
    } catch (_) {}

    if (btnQuickFill && surveyForm) {
        btnQuickFill.addEventListener('click', () => {
            for (let q = 1; q <= 10; q++) {
                const radio5 = surveyForm.querySelector(`input[name="fis_likert_${q}"][value="5"]`);
                if (radio5) radio5.checked = true;
            }
            const c1 = document.getElementById('fisComment1');
            const c2 = document.getElementById('fisComment2');
            if (c1 && !c1.value) c1.value = 'El plano cartesiano interactivo de vectores, el reto del cañón y la montaña rusa de Joules hicieron muy claro el enfoque de causa y efecto.';
            if (c2 && !c2.value) c2.value = 'Excelente integración de las guías visuales con código lógico y tablas de decisión.';
        });
    }

    if (surveyForm) {
        surveyForm.addEventListener('submit', (e) => {
            e.preventDefault();

            let missing = [];
            for (let q = 1; q <= 10; q++) {
                const checked = surveyForm.querySelector(`input[name="fis_likert_${q}"]:checked`);
                if (!checked) missing.push(q);
            }

            if (missing.length > 0) {
                if (surveyAlert) {
                    surveyAlert.className = 'alert alert-warning small py-2 px-3 mt-3';
                    surveyAlert.innerHTML = `⚠️ Por favor selecciona una opción en las 10 afirmaciones de la Escala Likert (faltan: #${missing.join(', #')}). Puedes usar "Autocompletar demostración".`;
                    surveyAlert.classList.remove('d-none');
                }
                return;
            }

            try {
                localStorage.setItem(SURVEY_STORAGE_KEY, '1');
            } catch (_) {}

            completedItems.add('cierre-encuesta');
            completedItems.add('cierre-certificado');
            saveProgress();
            updateProgressCounters();
            unlockCertificateButtons();

            if (surveyAlert) {
                surveyAlert.className = 'alert alert-success small py-2 px-3 mt-3';
                surveyAlert.innerHTML = '✅ <strong>¡Gracias por tu participación!</strong> La encuesta ha sido registrada y el botón <strong>"Descargar Certificado"</strong> ha sido habilitado.';
                surveyAlert.classList.remove('d-none');
            }
        });
    }

    function generatePhysicsCertificatePdf() {
        const certDataEl = document.getElementById('fisCertificateData');
        const nombreAlumno = certDataEl?.getAttribute('data-nombre-alumno') || 'Estudiante de Ingeniería';
        const moduloCompletado = certDataEl?.getAttribute('data-modulo-completado') || 'Física (Causa, Efecto y Simulación)';
        const fechaEmision = certDataEl?.getAttribute('data-fecha-emision') || new Date().toLocaleDateString('es-MX');
        const folio = certDataEl?.getAttribute('data-folio') || 'LV-FIS-2026-004';

        const printWin = window.open('', '_blank', 'width=960,height=720');
        if (!printWin) {
            window.print();
            return;
        }

        printWin.document.write(`<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado - ${moduloCompletado} - ${nombreAlumno}</title>
    <style>
        @page { size: landscape; margin: 12mm; }
        body { font-family: 'Inter', 'Segoe UI', Arial, sans-serif; background: #f0fdfa; margin: 0; padding: 20px; color: #042f2e; }
        .cert-box { max-width: 920px; margin: 0 auto; background: #ffffff; border: 10px double #0f766e; padding: 48px 56px; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-25deg); font-size: 68px; font-weight: 900; color: rgba(13, 148, 136, 0.05); pointer-events: none; white-space: nowrap; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ccfbf1; padding-bottom: 20px; margin-bottom: 28px; }
        .brand { font-size: 22px; font-weight: 800; color: #042f2e; }
        .sub { font-size: 13px; color: #475569; }
        .badge { background: #0d9488; color: #ffffff; padding: 6px 14px; border-radius: 999px; font-size: 12px; font-weight: 700; letter-spacing: 0.05em; }
        .body { text-align: center; padding: 10px 0 24px; }
        .pre { text-transform: uppercase; letter-spacing: 0.12em; font-size: 12px; color: #0f766e; margin-bottom: 10px; font-weight: 700; }
        .student { font-size: 36px; font-weight: 800; color: #0f172a; margin: 8px 0 18px; }
        .desc { font-size: 15px; color: #334155; max-width: 680px; margin: 0 auto 20px; line-height: 1.5; }
        .module-pill { display: inline-block; background: #f0fdfa; border: 2px solid #0d9488; color: #0f766e; padding: 12px 32px; border-radius: 12px; font-size: 22px; font-weight: 800; margin-bottom: 16px; }
        .footer { display: flex; justify-content: space-between; align-items: flex-end; border-top: 1px solid #e2e8f0; padding-top: 24px; margin-top: 20px; font-size: 12px; color: #475569; }
        .sign { border-top: 1px solid #0f766e; padding-top: 6px; width: 220px; text-align: center; font-weight: 700; color: #042f2e; }
        @media print { body { background: #ffffff; padding: 0; } .cert-box { box-shadow: none; } }
    </style>
</head>
<body>
    <div class="cert-box">
        <div class="watermark">FÍSICA · CAUSA Y EFECTO</div>
        <div class="header">
            <div>
                <div class="brand">⚛ Lógica Visual ABP · IT Ciudad Victoria</div>
                <div class="sub">Departamento de Ciencias Básicas e Ingeniería en Sistemas Computacionales</div>
            </div>
            <div style="text-align:right;">
                <span class="badge">ACREDITACIÓN OFICIAL</span>
                <div class="sub" style="margin-top:4px;">Folio: ${folio}</div>
            </div>
        </div>
        <div class="body">
            <div class="pre">Se otorga el presente Certificado de Acreditación a</div>
            <div class="student">${nombreAlumno}</div>
            <p class="desc">Por haber completado satisfactoriamente las 5 lecciones interactivas, simuladores físicos y retos de Aprendizaje Basado en Problemas (ABP) del módulo:</p>
            <div class="module-pill">${moduloCompletado}</div>
            <div class="sub">Validando competencias en Vectores y Sistemas de Referencia, Cinemática 1D/2D, Leyes de Newton, Conservación de la Energía y Colisiones.</div>
        </div>
        <div class="footer">
            <div class="sign">Comité Académico ABP<br><span style="font-weight:400;font-size:11px;">Instituto Tecnológico de Ciudad Victoria</span></div>
            <div class="sign">Coordinación de Ciencias Básicas<br><span style="font-weight:400;font-size:11px;">Validación Digital Verificada</span></div>
            <div style="text-align:right;">
                <div><strong>Fecha de finalización:</strong> ${fechaEmision}</div>
                <div><strong>Módulo:</strong> ${moduloCompletado}</div>
            </div>
        </div>
    </div>
    <script>window.onload = function() { setTimeout(function() { window.print(); }, 250); };<\/script>
</body>
</html>`);
        printWin.document.close();
    }

    if (btnCertSurvey) {
        btnCertSurvey.addEventListener('click', () => {
            activatePane('cierre-certificado', true);
            generatePhysicsCertificatePdf();
        });
    }
    if (btnCertPane) {
        btnCertPane.addEventListener('click', generatePhysicsCertificatePdf);
    }
});


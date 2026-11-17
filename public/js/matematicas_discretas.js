/**
 * =====================================================================
 * Matemáticas Discretas: Lógica, Estructuras y Simulación · Controlador UI/UX
 * Maneja:
 *  1. Navegación Split-Screen (30% / 70%) y progreso por lección (0/4)
 *  2. Notas personales, acordeón de Tips ABP y re-renderizado MathJax
 *  3. Modal Pop-up con Backdrop Blur para vídeos de YouTube
 *  4. 5 Simuladores / Retos ABP interactivos (Tablas de Verdad, Venn,
 *     Matriz de Relación 3x3, Red de Nodos/Árboles y Compuertas Digitales)
 *  5. Impresión / Exportación a PDF de las 5 Guías Visuales HTML/CSS
 *  6. Encuesta Escala Likert (1-5) y desbloqueo de Certificado PDF
 * =====================================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    const wrapper = document.getElementById('discretasCursoApp');
    if (!wrapper) return;

    // -----------------------------------------------------------------
    // 1. Estado de Navegación y Progreso del Curso
    // -----------------------------------------------------------------
    const STORAGE_KEY = 'lv_discretas_completed_v1';
    const NOTES_STORAGE_KEY = 'lv_discretas_notes_v1';
    const SURVEY_STORAGE_KEY = 'lv_discretas_survey_done_v1';

    let completedItems = new Set();
    let savedNotes = {};

    try {
        const saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        if (Array.isArray(saved)) completedItems = new Set(saved);
    } catch (_) {}

    try {
        savedNotes = JSON.parse(localStorage.getItem(NOTES_STORAGE_KEY) || '{}') || {};
    } catch (_) {}

    const navButtons = wrapper.querySelectorAll('[data-disc-item]');
    const contentPanes = wrapper.querySelectorAll('[data-disc-pane]');
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
            const itemId = btn.getAttribute('data-disc-item');
            if (completedItems.has(itemId)) {
                btn.classList.add('is-completed');
            }
        });

        for (let lesson = 1; lesson <= 6; lesson++) {
            const lessonBtns = wrapper.querySelectorAll(`[data-disc-lesson="${lesson}"]`);
            const badge = document.getElementById(`discLessonCount-${lesson}`);
            if (badge && lessonBtns.length > 0) {
                let done = 0;
                lessonBtns.forEach(b => {
                    if (completedItems.has(b.getAttribute('data-disc-item'))) done++;
                });
                badge.textContent = `${done}/${lessonBtns.length} elementos`;
            }
        }

        const completedTotal = completedItems.size;
        const pct = totalItemsCount > 0 ? Math.round((completedTotal / totalItemsCount) * 100) : 0;
        const globalBar = document.getElementById('discGlobalProgressBar');
        const globalText = document.getElementById('discGlobalProgressText');
        if (globalBar) globalBar.style.width = `${pct}%`;
        if (globalText) globalText.textContent = `${completedTotal}/${totalItemsCount} elementos (${pct}%)`;
    }

    function activatePane(itemId, scrollWorkspace = true) {
        navButtons.forEach(btn => {
            btn.classList.toggle('is-active', btn.getAttribute('data-disc-item') === itemId);
        });

        contentPanes.forEach(pane => {
            pane.classList.toggle('is-active', pane.getAttribute('data-disc-pane') === itemId);
        });

        completedItems.add(itemId);
        saveProgress();
        updateProgressCounters();
        typesetMath();

        const activeBtn = wrapper.querySelector(`[data-disc-item="${itemId}"]`);
        if (activeBtn) {
            const collapseEl = activeBtn.closest('.accordion-collapse');
            if (collapseEl && !collapseEl.classList.contains('show') && window.bootstrap) {
                bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false }).show();
            }
        }

        if (scrollWorkspace && window.innerWidth < 992) {
            const mainPanel = document.getElementById('discMainWorkspace');
            if (mainPanel) mainPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // -----------------------------------------------------------------
    // 2. Notas Personales ("📝 Guardar nota") y Botones Feedback (👍 👎)
    // -----------------------------------------------------------------
    wrapper.querySelectorAll('[data-disc-note-toggle]').forEach(btn => {
        const key = btn.getAttribute('data-disc-note-toggle');
        const drawer = document.getElementById(`discNoteDrawer-${key}`);
        const textarea = document.getElementById(`discNoteInput-${key}`);
        const saveBtn = document.getElementById(`discNoteSave-${key}`);
        const statusEl = document.getElementById(`discNoteStatus-${key}`);

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

    wrapper.querySelectorAll('[data-disc-feedback]').forEach(btn => {
        btn.addEventListener('click', () => {
            const group = btn.closest('.disc-feedback-group');
            if (group) {
                group.querySelectorAll('.disc-feedback-btn').forEach(b => b.classList.remove('is-selected'));
            }
            btn.classList.add('is-selected');
            const msg = group?.querySelector('.disc-feedback-msg');
            if (msg) msg.textContent = '¡Gracias por tu retroalimentación!';
        });
    });

    // -----------------------------------------------------------------
    // 3. Ventana Emergente (Modal con Backdrop Blur) para Vídeos YouTube
    // -----------------------------------------------------------------
    const videoModalEl = document.getElementById('discYoutubeModal');
    const videoIframe = document.getElementById('discYoutubeIframe');
    const videoModalTitle = document.getElementById('discYoutubeModalTitle');
    const videoModalChannel = document.getElementById('discYoutubeModalChannel');
    const videoModalDirectLink = document.getElementById('discYoutubeDirectLink');
    const videoModalAltBtn = document.getElementById('discYoutubeAltBtn');

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
            const itemId = btn.getAttribute('data-disc-item');
            const isVideo = btn.getAttribute('data-disc-type') === 'video';

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
                    altShort: btn.getAttribute('data-video-alt-short')
                }, false);
            }
        });
    });

    wrapper.querySelectorAll('[data-open-disc-video]').forEach(trigger => {
        trigger.addEventListener('click', () => {
            const targetItem = trigger.getAttribute('data-open-disc-video');
            const useAlt = trigger.getAttribute('data-video-mode') === 'alt';
            const sidebarBtn = wrapper.querySelector(`[data-disc-item="${targetItem}"]`);
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
                altShort: sidebarBtn.getAttribute('data-video-alt-short')
            }, useAlt);
        });
    });

    wrapper.querySelectorAll('[data-disc-goto]').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-disc-goto');
            const sidebarBtn = wrapper.querySelector(`[data-disc-item="${targetId}"]`);
            if (sidebarBtn) sidebarBtn.click();
        });
    });

    // Inicializar estado de progreso
    completedItems.add('l1-lectura');
    saveProgress();
    updateProgressCounters();

    // -----------------------------------------------------------------
    // 4. SIMULADOR LECCIÓN 1: Evaluador Interactivo de Tablas de Verdad
    // -----------------------------------------------------------------
    const l1OpSelect = document.getElementById('discL1OpSelect');
    const l1PSelect = document.getElementById('discL1PSelect');
    const l1QSelect = document.getElementById('discL1QSelect');
    const l1CycleBtn = document.getElementById('discL1CycleBtn');
    const l1TableBody = document.getElementById('discL1TableBody');
    const l1ExprBadge = document.getElementById('discL1ExprBadge');
    const l1ColHeader = document.getElementById('discL1ColHeader');
    const l1ResBadge = document.getElementById('discL1ResBadge');
    const l1Console = document.getElementById('discL1Console');

    if (l1OpSelect && l1PSelect && l1QSelect && l1TableBody) {
        const labels = {
            and: 'p ∧ q',
            or: 'p ∨ q',
            xor: 'p ⊕ q',
            imp: 'p → q',
            bic: 'p ↔ q',
            taut: 'p ∨ ¬p'
        };

        function evalOperator(op, pv, qv) {
            if (op === 'and') return pv && qv ? 1 : 0;
            if (op === 'or') return pv || qv ? 1 : 0;
            if (op === 'xor') return pv !== qv ? 1 : 0;
            if (op === 'imp') return (!pv || qv) ? 1 : 0;
            if (op === 'bic') return (pv === qv) ? 1 : 0;
            return 1;
        }

        function updateL1TruthTable() {
            const op = l1OpSelect.value;
            const p = Number(l1PSelect.value);
            const q = Number(l1QSelect.value);
            const res = evalOperator(op, p, q);

            if (l1ExprBadge) l1ExprBadge.textContent = labels[op];
            if (l1ColHeader) l1ColHeader.textContent = labels[op];
            if (l1ResBadge) {
                l1ResBadge.textContent = `Resultado: ${res ? 'V (1)' : 'F (0)'}`;
                l1ResBadge.className = `badge font-monospace ${res ? 'bg-success text-white' : 'bg-danger text-white'}`;
            }

            const combos = [[1, 1], [1, 0], [0, 1], [0, 0]];
            let allTrue = true;
            let allFalse = true;

            l1TableBody.innerHTML = combos.map(([cp, cq]) => {
                const cr = evalOperator(op, cp, cq);
                if (cr === 0) allTrue = false;
                if (cr === 1) allFalse = false;
                const active = (cp === p && cq === q) ? 'table-active fw-bold text-warning' : '';
                return `<tr class="${active}"><td>${cp ? 'V' : 'F'}</td><td>${cq ? 'V' : 'F'}</td><td>${cr ? 'V (1)' : 'F (0)'}</td></tr>`;
            }).join('');

            const tipo = allTrue
                ? 'TAUTOLOGÍA (siempre V)'
                : allFalse
                    ? 'CONTRADICCIÓN (siempre F)'
                    : 'CONTINGENCIA (depende de las entradas)';

            if (l1Console) {
                l1Console.innerHTML = `
                    <div class="fw-bold mb-1" style="color:#d8b4fe;">&gt; [Evaluación Activa] p = ${p ? 'V (1)' : 'F (0)'}, q = ${q ? 'V (1)' : 'F (0)'} | Conectivo: ${labels[op]}</div>
                    <div>&gt; Resultado lógico de la fila seleccionada = <strong>${res ? 'VERDADERO (1)' : 'FALSO (0)'}</strong></div>
                    <div class="text-info">&gt; Clasificación global de la tabla: <strong>${tipo}</strong> ✓</div>
                `;
            }
        }

        [l1OpSelect, l1PSelect, l1QSelect].forEach(el => el.addEventListener('change', updateL1TruthTable));

        if (l1CycleBtn) {
            l1CycleBtn.addEventListener('click', () => {
                const combos = [[1, 1], [1, 0], [0, 1], [0, 0]];
                const idx = combos.findIndex(([a, b]) => a === Number(l1PSelect.value) && b === Number(l1QSelect.value));
                const next = combos[(idx + 1) % 4];
                l1PSelect.value = String(next[0]);
                l1QSelect.value = String(next[1]);
                updateL1TruthTable();
            });
        }

        updateL1TruthTable();
    }

    // -----------------------------------------------------------------
    // 5. RETO ABP LECCIÓN 2: Analizador Visual de Conjuntos y Diagrama de Venn
    // -----------------------------------------------------------------
    const l2SetAInput = document.getElementById('discL2SetAInput');
    const l2SetBInput = document.getElementById('discL2SetBInput');
    const l2OpSelect = document.getElementById('discL2OpSelect');
    const l2EvalBtn = document.getElementById('discL2EvalBtn');
    const l2VennOnlyA = document.getElementById('discL2VennOnlyA');
    const l2VennInter = document.getElementById('discL2VennInter');
    const l2VennOnlyB = document.getElementById('discL2VennOnlyB');
    const l2CardBadge = document.getElementById('discL2CardBadge');
    const l2PotBadge = document.getElementById('discL2PotBadge');
    const l2Console = document.getElementById('discL2Console');

    if (l2SetAInput && l2SetBInput && l2OpSelect) {
        function parseSet(str) {
            return [...new Set(str.split(',').map(s => s.trim()).filter(Boolean))];
        }

        function updateL2Venn() {
            const setA = parseSet(l2SetAInput.value);
            const setB = parseSet(l2SetBInput.value);
            const op = l2OpSelect.value;

            const onlyA = setA.filter(x => !setB.includes(x));
            const inter = setA.filter(x => setB.includes(x));
            const onlyB = setB.filter(x => !setA.includes(x));
            const union = [...new Set([...setA, ...setB])];

            if (l2VennOnlyA) l2VennOnlyA.textContent = onlyA.join(', ') || '∅';
            if (l2VennInter) l2VennInter.textContent = inter.join(', ') || '∅';
            if (l2VennOnlyB) l2VennOnlyB.textContent = onlyB.join(', ') || '∅';

            let res = union;
            let label = 'A ∪ B';
            if (op === 'inter') { res = inter; label = 'A ∩ B'; }
            if (op === 'diffAB') { res = onlyA; label = 'A - B'; }
            if (op === 'diffBA') { res = onlyB; label = 'B - A'; }
            if (op === 'sym') { res = [...onlyA, ...onlyB]; label = 'A △ B'; }

            if (l2CardBadge) l2CardBadge.textContent = `|A ∪ B| = ${union.length}`;
            if (l2PotBadge) l2PotBadge.textContent = `|P(A)| = ${Math.pow(2, setA.length)}`;

            if (l2Console) {
                l2Console.innerHTML = `
                    <div class="fw-bold mb-1" style="color:#d8b4fe;">&gt; [Operación Seleccionada] ${label} = { ${res.join(', ') || '∅'} } (Cardinalidad = ${res.length})</div>
                    <div>&gt; Regiones Venn: Solo A = { ${onlyA.join(', ') || '∅'} } | A ∩ B = { ${inter.join(', ') || '∅'} } | Solo B = { ${onlyB.join(', ') || '∅'} }</div>
                    <div class="text-info">&gt; Inclusión-Exclusión: |A| + |B| - |A ∩ B| = ${setA.length} + ${setB.length} - ${inter.length} = <strong>${union.length}</strong> ✓</div>
                `;
            }
        }

        if (l2EvalBtn) l2EvalBtn.addEventListener('click', updateL2Venn);
        l2OpSelect.addEventListener('change', updateL2Venn);
        l2SetAInput.addEventListener('input', updateL2Venn);
        l2SetBInput.addEventListener('input', updateL2Venn);

        updateL2Venn();
    }

    // -----------------------------------------------------------------
    // 6. SIMULADOR LECCIÓN 3: Analizador de Matriz de Relación M_R (3x3)
    // -----------------------------------------------------------------
    const l3MatrixGrid = document.getElementById('discL3MatrixGrid');
    const l3ClassBadge = document.getElementById('discL3ClassBadge');
    const l3Console = document.getElementById('discL3Console');

    if (l3MatrixGrid) {
        let matR = [
            [1, 1, 0],
            [1, 1, 0],
            [0, 0, 1]
        ];

        function setPropBadge(id, name, ok) {
            const el = document.getElementById(id);
            if (!el) return;
            el.textContent = `${name}: ${ok ? 'SÍ' : 'NO'}`;
            el.className = `badge w-100 py-2 ${ok ? 'bg-success text-white' : 'bg-secondary text-white'}`;
        }

        function updateL3Matrix() {
            l3MatrixGrid.innerHTML = '';
            for (let i = 0; i < 3; i++) {
                for (let j = 0; j < 3; j++) {
                    const cellBtn = document.createElement('button');
                    cellBtn.type = 'button';
                    cellBtn.className = `btn btn-sm font-monospace fw-bold ${matR[i][j] ? 'btn-purple' : 'btn-outline-secondary text-white'}`;
                    cellBtn.textContent = String(matR[i][j]);
                    cellBtn.addEventListener('click', () => {
                        matR[i][j] = matR[i][j] ? 0 : 1;
                        updateL3Matrix();
                    });
                    l3MatrixGrid.appendChild(cellBtn);
                }
            }

            const ref = matR[0][0] === 1 && matR[1][1] === 1 && matR[2][2] === 1;
            let sym = true;
            let anti = true;
            let trans = true;

            for (let i = 0; i < 3; i++) {
                for (let j = 0; j < 3; j++) {
                    if (matR[i][j] !== matR[j][i]) sym = false;
                    if (i !== j && matR[i][j] === 1 && matR[j][i] === 1) anti = false;
                    if (matR[i][j] === 1) {
                        for (let k = 0; k < 3; k++) {
                            if (matR[j][k] === 1 && matR[i][k] !== 1) trans = false;
                        }
                    }
                }
            }

            setPropBadge('discL3PropRef', 'Reflexiva', ref);
            setPropBadge('discL3PropSym', 'Simétrica', sym);
            setPropBadge('discL3PropAnti', 'Antisimétrica', anti);
            setPropBadge('discL3PropTrans', 'Transitiva', trans);

            let classification = 'Relación General';
            if (ref && sym && trans) classification = 'Relación de Equivalencia';
            else if (ref && anti && trans) classification = 'Orden Parcial (Poset)';

            if (l3ClassBadge) l3ClassBadge.textContent = classification;

            if (l3Console) {
                l3Console.innerHTML = `
                    <div class="fw-bold mb-1" style="color:#d8b4fe;">&gt; [Diagnóstico Matricial M_R] Reflexiva=${ref ? 'SÍ' : 'NO'} | Simétrica=${sym ? 'SÍ' : 'NO'} | Antisimétrica=${anti ? 'SÍ' : 'NO'} | Transitiva=${trans ? 'SÍ' : 'NO'}</div>
                    <div class="text-info">&gt; Clasificación formal sobre A = {1, 2, 3}: <strong>${classification}</strong> ✓</div>
                `;
            }
        }

        wrapper.querySelectorAll('[data-disc-mat-preset]').forEach(btn => {
            btn.addEventListener('click', () => {
                const preset = btn.getAttribute('data-disc-mat-preset');
                if (preset === 'equiv') matR = [[1, 1, 0], [1, 1, 0], [0, 0, 1]];
                if (preset === 'poset') matR = [[1, 1, 1], [0, 1, 1], [0, 0, 1]];
                if (preset === 'identity') matR = [[1, 0, 0], [0, 1, 0], [0, 0, 1]];
                updateL3Matrix();
            });
        });

        updateL3Matrix();
    }

    // -----------------------------------------------------------------
    // 7. RETO ABP LECCIÓN 4: Red de Nodos — Verificador de Árboles (|V|=5)
    // -----------------------------------------------------------------
    const l4SvgEdges = document.getElementById('discL4SvgEdges');
    const l4SvgNodes = document.getElementById('discL4SvgNodes');
    const l4EdgeToggles = document.getElementById('discL4EdgeToggles');
    const l4SpanningBtn = document.getElementById('discL4SpanningBtn');
    const l4EdgesBadge = document.getElementById('discL4EdgesBadge');
    const l4DegBadge = document.getElementById('discL4DegBadge');
    const l4TreeBadge = document.getElementById('discL4TreeBadge');
    const l4Console = document.getElementById('discL4Console');

    if (l4SvgEdges && l4SvgNodes && l4EdgeToggles) {
        const nodesPos = [
            { id: 0, label: 'V1', x: 60, y: 50 },
            { id: 1, label: 'V2', x: 240, y: 50 },
            { id: 2, label: 'V3', x: 150, y: 100 },
            { id: 3, label: 'V4', x: 60, y: 160 },
            { id: 4, label: 'V5', x: 240, y: 160 }
        ];

        const allEdges = [
            { key: '0-1', u: 0, v: 1, active: false },
            { key: '0-2', u: 0, v: 2, active: true },
            { key: '1-2', u: 1, v: 2, active: true },
            { key: '2-3', u: 2, v: 3, active: true },
            { key: '2-4', u: 2, v: 4, active: true },
            { key: '3-4', u: 3, v: 4, active: false }
        ];

        function updateL4Graph() {
            l4SvgEdges.innerHTML = '';
            l4EdgeToggles.innerHTML = '';

            const deg = [0, 0, 0, 0, 0];
            let activeCount = 0;

            allEdges.forEach(edge => {
                if (edge.active) {
                    activeCount++;
                    deg[edge.u]++;
                    deg[edge.v]++;

                    const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                    line.setAttribute('x1', String(nodesPos[edge.u].x));
                    line.setAttribute('y1', String(nodesPos[edge.u].y));
                    line.setAttribute('x2', String(nodesPos[edge.v].x));
                    line.setAttribute('y2', String(nodesPos[edge.v].y));
                    line.setAttribute('stroke', '#c084fc');
                    line.setAttribute('stroke-width', '3');
                    l4SvgEdges.appendChild(line);
                }

                const toggleBtn = document.createElement('button');
                toggleBtn.type = 'button';
                toggleBtn.className = `btn btn-xs font-monospace ${edge.active ? 'btn-purple' : 'btn-outline-secondary text-white'}`;
                toggleBtn.textContent = `V${edge.u + 1}-V${edge.v + 1}`;
                toggleBtn.addEventListener('click', () => {
                    edge.active = !edge.active;
                    updateL4Graph();
                });
                l4EdgeToggles.appendChild(toggleBtn);
            });

            l4SvgNodes.innerHTML = nodesPos.map(n => `
                <circle cx="${n.x}" cy="${n.y}" r="18" fill="#2e1065" stroke="#a855f7" stroke-width="2.5"/>
                <text x="${n.x}" y="${n.y + 4}" font-size="11" fill="#ffffff" text-anchor="middle" font-weight="bold">${n.label}</text>
            `).join('');

            // Búsqueda en anchura (BFS) para comprobar conectividad
            const visited = new Set([0]);
            const queue = [0];
            while (queue.length > 0) {
                const curr = queue.shift();
                allEdges.forEach(e => {
                    if (!e.active) return;
                    const next = e.u === curr ? e.v : (e.v === curr ? e.u : null);
                    if (next !== null && !visited.has(next)) {
                        visited.add(next);
                        queue.push(next);
                    }
                });
            }

            const isConnected = visited.size === 5;
            const isTree = isConnected && activeCount === 4;
            const sumDeg = deg.reduce((acc, val) => acc + val, 0);

            if (l4EdgesBadge) l4EdgesBadge.textContent = `|E| = ${activeCount} aristas`;
            if (l4DegBadge) l4DegBadge.textContent = `∑deg(v) = ${sumDeg}`;
            if (l4TreeBadge) {
                l4TreeBadge.textContent = isTree ? '✓ ES UN ÁRBOL' : 'NO ES ÁRBOL';
                l4TreeBadge.className = `badge font-monospace ${isTree ? 'bg-emerald text-white' : 'bg-warning text-dark'}`;
            }

            if (l4Console) {
                l4Console.innerHTML = `
                    <div class="fw-bold mb-1" style="color:#d8b4fe;">&gt; [Apretón de Manos] Grados: [${deg.map((d, i) => `deg(V${i + 1})=${d}`).join(', ')}] ⇒ ∑deg(v) = ${sumDeg} = 2·|E| (${2 * activeCount})</div>
                    <div class="${isTree ? 'text-info' : 'text-warning'}">&gt; Conexo: ${isConnected ? 'SÍ' : 'NO'} | Condición |E| = |V| - 1 (4 aristas): ${activeCount === 4 ? 'SÍ' : 'NO'} ⇒ <strong>${isTree ? 'ÁRBOL CONEXO Y ACÍCLICO VÁLIDO ✓' : 'Grafo General (ajusta aristas para formar un árbol)'}</strong></div>
                `;
            }
        }

        if (l4SpanningBtn) {
            l4SpanningBtn.addEventListener('click', () => {
                allEdges.forEach(e => {
                    e.active = ['0-2', '1-2', '2-3', '2-4'].includes(e.key);
                });
                updateL4Graph();
            });
        }

        updateL4Graph();
    }

    // -----------------------------------------------------------------
    // 8. SIMULADOR LECCIÓN 5: Banco de Compuertas Lógicas Digitales
    // -----------------------------------------------------------------
    const l5GateSelect = document.getElementById('discL5GateSelect');
    const l5ToggleA = document.getElementById('discL5ToggleA');
    const l5ToggleB = document.getElementById('discL5ToggleB');
    const l5OutBadge = document.getElementById('discL5OutBadge');
    const l5Console = document.getElementById('discL5Console');

    if (l5GateSelect && l5ToggleA && l5ToggleB) {
        let bitA = 1;
        let bitB = 1;

        function updateL5LogicGate() {
            const gate = l5GateSelect.value;
            let y = 0;
            if (gate === 'AND') y = (bitA && bitB) ? 1 : 0;
            if (gate === 'OR') y = (bitA || bitB) ? 1 : 0;
            if (gate === 'XOR') y = (bitA !== bitB) ? 1 : 0;
            if (gate === 'NAND') y = !(bitA && bitB) ? 1 : 0;
            if (gate === 'NOR') y = !(bitA || bitB) ? 1 : 0;

            const gateLabel = document.getElementById('discL5GateLabel');
            const txtA = document.getElementById('discL5TxtA');
            const txtB = document.getElementById('discL5TxtB');
            const txtY = document.getElementById('discL5TxtY');
            const wireA = document.getElementById('discL5WireA');
            const wireB = document.getElementById('discL5WireB');
            const wireY = document.getElementById('discL5WireY');
            const led = document.getElementById('discL5Led');

            if (gateLabel) gateLabel.textContent = gate;
            if (txtA) txtA.textContent = `A=${bitA}`;
            if (txtB) txtB.textContent = `B=${bitB}`;
            if (txtY) txtY.textContent = `Y=${y}`;

            l5ToggleA.textContent = `Entrada A: ${bitA}`;
            l5ToggleB.textContent = `Entrada B: ${bitB}`;

            if (wireA) wireA.setAttribute('stroke', bitA ? '#10b981' : '#475569');
            if (wireB) wireB.setAttribute('stroke', bitB ? '#10b981' : '#475569');
            if (wireY) wireY.setAttribute('stroke', y ? '#10b981' : '#475569');
            if (led) led.setAttribute('fill', y ? '#10b981' : '#334155');

            if (l5OutBadge) {
                l5OutBadge.textContent = `SALIDA Y = ${y} (${y ? 'ENCENDIDO' : 'APAGADO'})`;
                l5OutBadge.className = `badge font-monospace ${y ? 'bg-emerald text-white' : 'bg-secondary text-white'}`;
            }

            if (l5Console) {
                l5Console.innerHTML = `
                    <div class="fw-bold mb-1" style="color:#d8b4fe;">&gt; [Compuerta Digital ${gate}] Entradas binarias: A = ${bitA}, B = ${bitB}</div>
                    <div class="text-info">&gt; Señal de salida booleana: <strong>Y = ${y}</strong> (${y ? 'Nivel Alto / 5V · LED Encendido' : 'Nivel Bajo / 0V · LED Apagado'}) ✓</div>
                `;
            }
        }

        l5ToggleA.addEventListener('click', () => {
            bitA = bitA ? 0 : 1;
            updateL5LogicGate();
        });

        l5ToggleB.addEventListener('click', () => {
            bitB = bitB ? 0 : 1;
            updateL5LogicGate();
        });

        l5GateSelect.addEventListener('change', updateL5LogicGate);

        updateL5LogicGate();
    }

    // -----------------------------------------------------------------
    // 9. Impresión / Exportación a PDF de cada Guía Visual HTML/CSS
    // -----------------------------------------------------------------
    wrapper.querySelectorAll('[data-print-guide]').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-print-guide');
            const guideTitle = btn.getAttribute('data-guide-title') || 'Guía Visual de Matemáticas Discretas';
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #ffffff; padding: 24px; color: #1f2937; }
        .code-font { font-family: 'JetBrains Mono', monospace; }
        .math-box { background-color: #2e1065; color: #f8fafc; border-radius: 0.5rem; padding: 0.75rem; text-align: center; font-size: 1.1rem; }
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
    const surveyForm = document.getElementById('discLikertSurveyForm');
    const btnQuickFill = document.getElementById('discSurveyQuickFillBtn');
    const btnCertSurvey = document.getElementById('discBtnDownloadCertSurvey');
    const btnCertPane = document.getElementById('discBtnDownloadCertPane');
    const surveyAlert = document.getElementById('discSurveyStatusAlert');
    const certLockNotice = document.getElementById('discCertLockNotice');

    function unlockCertificateButtons() {
        [btnCertSurvey, btnCertPane].forEach(btn => {
            if (!btn) return;
            btn.disabled = false;
            btn.removeAttribute('disabled');
            btn.classList.remove('btn-secondary', 'opacity-75');
            btn.classList.add('btn-purple', 'shadow-purple');
            btn.innerHTML = '🎓 Descargar Certificado Oficial de Discretas (PDF)';
        });

        if (certLockNotice) {
            certLockNotice.className = 'alert alert-success d-flex align-items-center gap-2 py-2 px-3 small mb-3';
            certLockNotice.innerHTML = '<span>✅</span> <div><strong>Encuesta completada:</strong> Tu certificado de acreditación de Matemáticas Discretas está desbloqueado y listo para descargar en PDF.</div>';
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
                const radio5 = surveyForm.querySelector(`input[name="disc_likert_${q}"][value="5"]`);
                if (radio5) radio5.checked = true;
            }
            const c1 = document.getElementById('discComment1');
            const c2 = document.getElementById('discComment2');
            if (c1 && !c1.value) c1.value = 'El evaluador de tablas de verdad, el diagrama de Venn interactivo y el simulador de compuertas lógicas hicieron muy clara la teoría discreta.';
            if (c2 && !c2.value) c2.value = 'Excelente integración de los simuladores visuales con las guías imprimibles.';
        });
    }

    if (surveyForm) {
        surveyForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const missing = [];
            for (let q = 1; q <= 10; q++) {
                const checked = surveyForm.querySelector(`input[name="disc_likert_${q}"]:checked`);
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

    function generateDiscretasCertificatePdf() {
        const certDataEl = document.getElementById('discCertificateData');
        const nombreAlumno = certDataEl?.getAttribute('data-nombre-alumno') || 'Estudiante de Ingeniería';
        const moduloCompletado = certDataEl?.getAttribute('data-modulo-completado') || 'Matemáticas Discretas (Lógica, Estructuras y Simulación)';
        const fechaEmision = certDataEl?.getAttribute('data-fecha-emision') || new Date().toLocaleDateString('es-MX');
        const folio = certDataEl?.getAttribute('data-folio') || 'LV-DISC-2026-005';

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
        body { font-family: 'Inter', 'Segoe UI', Arial, sans-serif; background: #faf5ff; margin: 0; padding: 20px; color: #1e1035; }
        .cert-box { max-width: 920px; margin: 0 auto; background: #ffffff; border: 10px double #6b21a8; padding: 48px 56px; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-25deg); font-size: 56px; font-weight: 900; color: rgba(126, 34, 206, 0.05); pointer-events: none; white-space: nowrap; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f3e8ff; padding-bottom: 20px; margin-bottom: 28px; }
        .brand { font-size: 22px; font-weight: 800; color: #1e1035; }
        .sub { font-size: 13px; color: #475569; }
        .badge { background: #7e22ce; color: #ffffff; padding: 6px 14px; border-radius: 999px; font-size: 12px; font-weight: 700; letter-spacing: 0.05em; }
        .body { text-align: center; padding: 10px 0 24px; }
        .pre { text-transform: uppercase; letter-spacing: 0.12em; font-size: 12px; color: #6b21a8; margin-bottom: 10px; font-weight: 700; }
        .student { font-size: 36px; font-weight: 800; color: #0f172a; margin: 8px 0 18px; }
        .desc { font-size: 15px; color: #334155; max-width: 680px; margin: 0 auto 20px; line-height: 1.5; }
        .module-pill { display: inline-block; background: #faf5ff; border: 2px solid #7e22ce; color: #6b21a8; padding: 12px 32px; border-radius: 12px; font-size: 20px; font-weight: 800; margin-bottom: 16px; }
        .footer { display: flex; justify-content: space-between; align-items: flex-end; border-top: 1px solid #e2e8f0; padding-top: 24px; margin-top: 20px; font-size: 12px; color: #475569; }
        .sign { border-top: 1px solid #6b21a8; padding-top: 6px; width: 220px; text-align: center; font-weight: 700; color: #1e1035; }
        @media print { body { background: #ffffff; padding: 0; } .cert-box { box-shadow: none; } }
    </style>
</head>
<body>
    <div class="cert-box">
        <div class="watermark">MATEMÁTICAS DISCRETAS · ABP</div>
        <div class="header">
            <div>
                <div class="brand">⛖ Lógica Visual ABP · IT Ciudad Victoria</div>
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
            <p class="desc">Por haber completado satisfactoriamente las 5 lecciones interactivas, simuladores lógicos y retos de Aprendizaje Basado en Problemas (ABP) del módulo:</p>
            <div class="module-pill">${moduloCompletado}</div>
            <div class="sub">Validando competencias en Lógica Proposicional, Teoría de Conjuntos, Relaciones Binarias, Grafos y Árboles, y Álgebra Booleana.</div>
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
            generateDiscretasCertificatePdf();
        });
    }
    if (btnCertPane) {
        btnCertPane.addEventListener('click', generateDiscretasCertificatePdf);
    }
});
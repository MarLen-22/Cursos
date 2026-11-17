/**
 * =====================================================================
 * Fundamentos de Programación · Controlador Interactivo Split-Screen
 * Conecta con #programacionCursoApp:
 *  - Navegación entre las 22 vistas y acordeones laterales
 *  - Progreso en verde (por lección y barra global) guardado en localStorage
 *  - 5 Simuladores / Retos ABP interactivos en tiempo real
 *  - Modal de YouTube con cambio entre video principal y alternativo
 *  - Notas personales, guías PDF imprimibles, encuesta Likert y certificado
 * =====================================================================
 */
(function () {
    'use strict';

    function initProgramacionApp() {
        const app = document.getElementById('programacionCursoApp');
        if (!app) return;

        // =================================================================
        // 1. NAVEGACIÓN ENTRE LECCIONES Y PROGRESO EN VERDE
        // =================================================================
        const certDataEl = document.getElementById('progCertificateData');
        const folio = certDataEl ? certDataEl.dataset.folio : 'LV-PROG-00001';
        const STORAGE_PROGRESS_KEY = 'lv_prog_progress_' + folio;
        const STORAGE_NOTES_KEY = 'lv_prog_notes_' + folio;

        const navItems = Array.from(app.querySelectorAll('.prog-nav-item[data-prog-item]'));
        const contentPanes = Array.from(app.querySelectorAll('.prog-content-pane[data-prog-pane]'));
        const totalItems = navItems.length; // 22 elementos

        let completedItems = new Set();
        try {
            const saved = JSON.parse(localStorage.getItem(STORAGE_PROGRESS_KEY) || '[]');
            if (Array.isArray(saved)) {
                completedItems = new Set(saved);
            }
        } catch (_) {
            completedItems = new Set();
        }

        function persistProgress() {
            try {
                localStorage.setItem(STORAGE_PROGRESS_KEY, JSON.stringify(Array.from(completedItems)));
            } catch (_) { /* Ignorar si localStorage no está disponible */ }
        }

        function refreshProgressUI() {
            const totalsByLesson = { '1': 4, '2': 4, '3': 4, '4': 4, '5': 4, '6': 2 };
            const doneByLesson = { '1': 0, '2': 0, '3': 0, '4': 0, '5': 0, '6': 0 };

            navItems.forEach((btn) => {
                const key = btn.dataset.progItem;
                const lesson = btn.dataset.progLesson;
                const isDone = completedItems.has(key);

                btn.classList.toggle('is-completed', isDone);
                if (isDone && doneByLesson[lesson] !== undefined) {
                    doneByLesson[lesson]++;
                }
            });

            Object.keys(totalsByLesson).forEach((lessonNum) => {
                const label = document.getElementById('progLessonCount-' + lessonNum);
                if (label) {
                    label.textContent = `${doneByLesson[lessonNum]}/${totalsByLesson[lessonNum]} elementos`;
                }
            });

            const completedCount = completedItems.size;
            const percent = totalItems > 0 ? Math.round((completedCount / totalItems) * 100) : 0;

            const globalText = document.getElementById('progGlobalProgressText');
            const globalBar = document.getElementById('progGlobalProgressBar');
            if (globalText) globalText.textContent = `${completedCount}/${totalItems} elementos (${percent}%)`;
            if (globalBar) {
                globalBar.style.width = `${percent}%`;
                if (percent === 100) {
                    globalBar.style.backgroundColor = '#10b981';
                }
            }
        }

        function markCompleted(itemKey) {
            if (!itemKey) return;
            completedItems.add(itemKey);
            persistProgress();
            refreshProgressUI();
        }

        function expandLessonAccordion(lessonNum) {
            const collapseEl = document.getElementById('progCollapseL' + lessonNum);
            if (!collapseEl || collapseEl.classList.contains('show')) return;

            if (window.bootstrap && window.bootstrap.Collapse) {
                window.bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false }).show();
            } else {
                collapseEl.classList.add('show');
                const trigger = app.querySelector(`[data-bs-target="#progCollapseL${lessonNum}"]`);
                if (trigger) {
                    trigger.classList.remove('collapsed');
                    trigger.setAttribute('aria-expanded', 'true');
                }
            }
        }

        function activatePane(itemKey) {
            const targetPane = app.querySelector(`.prog-content-pane[data-prog-pane="${itemKey}"]`);
            if (!targetPane) return;

            contentPanes.forEach((pane) => pane.classList.remove('is-active'));
            navItems.forEach((btn) => btn.classList.remove('is-active'));

            targetPane.classList.add('is-active');

            const targetNav = app.querySelector(`.prog-nav-item[data-prog-item="${itemKey}"]`);
            if (targetNav) {
                targetNav.classList.add('is-active');
                expandLessonAccordion(targetNav.dataset.progLesson);
            }

            if (itemKey !== 'cierre-certificado') {
                markCompleted(itemKey);
            }

            const workspace = document.getElementById('progMainWorkspace');
            if (workspace) {
                workspace.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // Clics en el menú lateral izquierdo
        navItems.forEach((btn) => {
            btn.addEventListener('click', () => {
                activatePane(btn.dataset.progItem);
            });
        });

        // Clics en todos los botones "Siguiente / Anterior / Ir a..."
        app.querySelectorAll('[data-prog-goto]').forEach((btn) => {
            btn.addEventListener('click', () => {
                activatePane(btn.dataset.progGoto);
            });
        });

        // Respaldo para acordeones si Bootstrap JS tarda en cargar
        if (!window.bootstrap || !window.bootstrap.Collapse) {
            app.querySelectorAll('.prog-lesson-accordion .accordion-button').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const targetSel = btn.getAttribute('data-bs-target');
                    const target = targetSel ? document.querySelector(targetSel) : null;
                    if (!target) return;
                    const willOpen = !target.classList.contains('show');
                    target.classList.toggle('show', willOpen);
                    btn.classList.toggle('collapsed', !willOpen);
                });
            });
        }

        // Inicializar primer elemento en verde
        if (completedItems.size === 0) {
            markCompleted('l1-lectura');
        } else {
            refreshProgressUI();
        }

        // =================================================================
        // 2. NOTAS PERSONALES Y BOTONES DE UTILIDAD (👍 / 👎)
        // =================================================================
        let notesStore = {};
        try {
            notesStore = JSON.parse(localStorage.getItem(STORAGE_NOTES_KEY) || '{}');
        } catch (_) {
            notesStore = {};
        }

        ['l1', 'l2', 'l3', 'l4', 'l5'].forEach((lessonKey) => {
            const toggleBtn = app.querySelector(`[data-prog-note-toggle="${lessonKey}"]`);
            const drawer = document.getElementById(`progNoteDrawer-${lessonKey}`);
            const input = document.getElementById(`progNoteInput-${lessonKey}`);
            const saveBtn = document.getElementById(`progNoteSave-${lessonKey}`);
            const status = document.getElementById(`progNoteStatus-${lessonKey}`);

            if (input && notesStore[lessonKey]) {
                input.value = notesStore[lessonKey];
            }
            if (toggleBtn && drawer) {
                toggleBtn.addEventListener('click', () => drawer.classList.toggle('d-none'));
            }
            if (saveBtn && input) {
                saveBtn.addEventListener('click', () => {
                    notesStore[lessonKey] = input.value.trim();
                    try {
                        localStorage.setItem(STORAGE_NOTES_KEY, JSON.stringify(notesStore));
                    } catch (_) { }
                    if (status) {
                        status.textContent = '✓ Apunte guardado correctamente';
                        setTimeout(() => { status.textContent = ''; }, 2800);
                    }
                });
            }
        });

        app.querySelectorAll('.prog-feedback-group').forEach((group) => {
            const buttons = group.querySelectorAll('.prog-feedback-btn');
            const msg = group.querySelector('.prog-feedback-msg');
            buttons.forEach((btn) => {
                btn.addEventListener('click', () => {
                    buttons.forEach((b) => b.classList.remove('is-selected'));
                    btn.classList.add('is-selected');
                    if (msg) {
                        msg.textContent = btn.dataset.progFeedback === 'up'
                            ? '¡Gracias por tu valoración!'
                            : 'Gracias, tomaremos en cuenta tu opinión.';
                    }
                });
            });
        });

        // =================================================================
        // 3. MODAL POP-UP DE VÍDEOS YOUTUBE
        // =================================================================
        const ytModalEl = document.getElementById('progYoutubeModal');
        const ytIframe = document.getElementById('progYoutubeIframe');
        const ytTitle = document.getElementById('progYoutubeModalTitle');
        const ytChannel = document.getElementById('progYoutubeModalChannel');
        const ytDirectLink = document.getElementById('progYoutubeDirectLink');
        const ytAltBtn = document.getElementById('progYoutubeAltBtn');

        let activeVideoMeta = null;
        let activeVideoMode = 'main';

        function setModalVideo(meta, mode) {
            if (!meta || !ytIframe) return;
            activeVideoMeta = meta;
            activeVideoMode = mode;

            const useAlt = mode === 'alt' && meta.altEmbed;
            if (ytTitle) ytTitle.textContent = (useAlt ? meta.altTitle : meta.title) || 'Reproductor de Vídeo';
            if (ytChannel) ytChannel.textContent = (useAlt ? meta.altChannel : meta.channel) || 'Canal Educativo';
            if (ytDirectLink) ytDirectLink.href = (useAlt ? meta.altUrl : meta.url) || '#';

            const embedSrc = useAlt ? meta.altEmbed : meta.embed;
            ytIframe.src = embedSrc ? `${embedSrc}?autoplay=1&rel=0` : '';

            if (ytAltBtn) {
                if (meta.altEmbed) {
                    ytAltBtn.classList.remove('d-none');
                    ytAltBtn.textContent = useAlt
                        ? '🎬 Ver Vídeo Principal'
                        : `🎬 Ver Alternativo (${meta.altShort || 'Opción 2'})`;
                } else {
                    ytAltBtn.classList.add('d-none');
                }
            }
        }

        app.querySelectorAll('[data-open-prog-video]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const itemKey = btn.dataset.openProgVideo;
                const mode = btn.dataset.videoMode || 'main';
                const navData = app.querySelector(`.prog-nav-item[data-prog-item="${itemKey}"]`);
                if (!navData) return;

                markCompleted(itemKey);
                setModalVideo({
                    title: navData.dataset.videoTitle,
                    channel: navData.dataset.videoChannel,
                    embed: navData.dataset.videoEmbed,
                    url: navData.dataset.videoUrl,
                    altTitle: navData.dataset.videoAltTitle,
                    altChannel: navData.dataset.videoAltChannel,
                    altEmbed: navData.dataset.videoAltEmbed,
                    altUrl: navData.dataset.videoAltUrl,
                    altShort: navData.dataset.videoAltShort
                }, mode);

                if (ytModalEl && window.bootstrap && window.bootstrap.Modal) {
                    window.bootstrap.Modal.getOrCreateInstance(ytModalEl).show();
                } else if (navData.dataset.videoUrl) {
                    window.open(navData.dataset.videoUrl, '_blank', 'noopener');
                }
            });
        });

        if (ytAltBtn) {
            ytAltBtn.addEventListener('click', () => {
                if (!activeVideoMeta) return;
                setModalVideo(activeVideoMeta, activeVideoMode === 'main' ? 'alt' : 'main');
            });
        }

        if (ytModalEl) {
            ytModalEl.addEventListener('hidden.bs.modal', () => {
                if (ytIframe) ytIframe.src = '';
            });
        }

        // =================================================================
        // 4. IMPRIMIR / GUARDAR PDF DE LAS 5 GUÍAS VISUALES
        // =================================================================
        app.querySelectorAll('[data-print-guide]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const guideId = btn.dataset.printGuide;
                const title = btn.dataset.guideTitle || 'Guía Visual';
                const guideEl = document.getElementById(guideId);
                if (!guideEl) return;

                const printWin = window.open('', '_blank', 'width=960,height=780');
                if (!printWin) {
                    window.print();
                    return;
                }
                printWin.document.write(`<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>${title}</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>body{padding:24px;font-family:system-ui,sans-serif;}</style>
</head>
<body>${guideEl.outerHTML}<script>window.onload=function(){window.print();};<\/script></body>
</html>`);
                printWin.document.close();
            });
        });

        // =================================================================
        // 5. SIMULADOR LECCIÓN 1: TRAZA ALGORÍTMICA (ENTRADA -> PROCESO -> SALIDA)
        // =================================================================
        (function setupSimL1() {
            const n1In = document.getElementById('progL1N1');
            const n2In = document.getElementById('progL1N2');
            const n3In = document.getElementById('progL1N3');
            const stepBtn = document.getElementById('progL1StepBtn');
            const resetBtn = document.getElementById('progL1ResetBtn');
            const consoleEl = document.getElementById('progL1Console');
            if (!n1In || !stepBtn || !consoleEl) return;

            let currentStep = 1;

            function renderL1() {
                const n1 = parseFloat(n1In.value) || 0;
                const n2 = parseFloat(n2In.value) || 0;
                const n3 = parseFloat(n3In.value) || 0;
                const sum = n1 + n2 + n3;
                const avg = sum / 3;
                const isApproved = avg >= 70;

                document.getElementById('progL1SumDisplay').textContent = sum.toFixed(0);
                document.getElementById('progL1AvgDisplay').textContent = avg.toFixed(2);
                document.getElementById('progL1OutDisplay').textContent = isApproved ? 'Aprobado' : 'Reprobado';
                document.getElementById('progL1AvgBadge').textContent = `promedio = ${avg.toFixed(1)}`;

                const statusBadge = document.getElementById('progL1StatusBadge');
                statusBadge.textContent = isApproved ? 'APROBADO' : 'REPROBADO';
                statusBadge.className = `badge font-monospace ${isApproved ? 'bg-success text-white' : 'bg-danger text-white'}`;

                const labels = {
                    1: 'Paso 1/4: Entrada',
                    2: 'Paso 2/4: Proceso',
                    3: 'Paso 3/4: Decisión',
                    4: 'Paso 4/4: Salida'
                };
                document.getElementById('progL1StepBadge').textContent = labels[currentStep];

                const nodeInput = document.getElementById('progL1NodeInput');
                const nodeProc = document.getElementById('progL1NodeProcess');
                const nodeDec = document.getElementById('progL1NodeDecision');
                const nodePass = document.getElementById('progL1NodePass');
                const nodeFail = document.getElementById('progL1NodeFail');

                if (nodeInput) nodeInput.setAttribute('fill', currentStep === 1 ? '#2563eb' : '#1e293b');
                if (nodeProc) nodeProc.setAttribute('fill', currentStep === 2 ? '#0284c7' : '#0f172a');
                if (nodeDec) nodeDec.setAttribute('fill', currentStep === 3 ? '#b45309' : '#0f172a');
                if (nodePass) nodePass.setAttribute('opacity', currentStep === 4 && isApproved ? '1' : '0.45');
                if (nodeFail) nodeFail.setAttribute('opacity', currentStep === 4 && !isApproved ? '1' : '0.45');

                const lines = [`> [INICIO] Prueba de escritorio con n1=${n1}, n2=${n2}, n3=${n3}`];
                if (currentStep >= 1) lines.push(`> [Paso 1 · ENTRADA] LEER n1=${n1}, n2=${n2}, n3=${n3}`);
                if (currentStep >= 2) lines.push(`> [Paso 2 · PROCESO] prom = (${n1} + ${n2} + ${n3}) / 3 = ${avg.toFixed(2)}`);
                if (currentStep >= 3) lines.push(`> [Paso 3 · DECISIÓN] ¿${avg.toFixed(2)} >= 70? -> ${isApproved ? 'TRUE (Rama SÍ)' : 'FALSE (Rama NO)'}`);
                if (currentStep >= 4) lines.push(`> [Paso 4 · SALIDA] ESCRIBIR "${isApproved ? 'APROBADO' : 'REPROBADO'}"`);

                consoleEl.innerHTML = lines.join('<br>');
                stepBtn.textContent = currentStep < 4 ? 'Ejecutar siguiente paso ▷' : 'Reiniciar traza ↻';
            }

            stepBtn.addEventListener('click', () => {
                currentStep = currentStep < 4 ? currentStep + 1 : 1;
                markCompleted('l1-simulador');
                renderL1();
            });

            if (resetBtn) {
                resetBtn.addEventListener('click', () => {
                    currentStep = 1;
                    renderL1();
                });
            }

            [n1In, n2In, n3In].forEach((el) => el.addEventListener('input', renderL1));
            renderL1();
        })();

        // =================================================================
        // 6. RETO ABP LECCIÓN 2: EVALUADOR DE EXPRESIONES Y PRECEDENCIA
        // =================================================================
        (function setupRetoL2() {
            const selectEl = document.getElementById('progL2ExprSelect');
            const varA = document.getElementById('progL2VarA');
            const varB = document.getElementById('progL2VarB');
            const varC = document.getElementById('progL2VarC');
            const evalBtn = document.getElementById('progL2EvalBtn');
            const consoleEl = document.getElementById('progL2Console');
            if (!selectEl || !evalBtn || !consoleEl) return;

            function evaluateExpr() {
                const a = parseInt(varA.value, 10) || 0;
                const b = parseInt(varB.value, 10) || 0;
                let c = parseInt(varC.value, 10);
                if (isNaN(c) || c === 0) c = 1;

                const typeBadge = document.getElementById('progL2TypeBadge');
                const resBadge = document.getElementById('progL2ResultBadge');
                const out = [`> Variables en RAM: a = ${a}, b = ${b}, c = ${c}`];

                if (selectEl.value === 'expr1') {
                    const mult = b * c;
                    const total = a + mult;
                    out.push(`> Expresión: resultado = a + b * c`);
                    out.push(`> 1° Multiplicación (*): ${b} * ${c} = ${mult}`);
                    out.push(`> 2° Suma (+): ${a} + ${mult} = ${total}`);
                    typeBadge.textContent = 'Tipo: int';
                    resBadge.textContent = `Resultado = ${total}`;
                } else if (selectEl.value === 'expr2') {
                    const sum = a + b;
                    const total = sum * c;
                    out.push(`> Expresión: resultado = (a + b) * c`);
                    out.push(`> 1° Paréntesis (): (${a} + ${b}) = ${sum}`);
                    out.push(`> 2° Multiplicación (*): ${sum} * ${c} = ${total}`);
                    typeBadge.textContent = 'Tipo: int';
                    resBadge.textContent = `Resultado = ${total}`;
                } else if (selectEl.value === 'expr3') {
                    const prod = a * b;
                    const rem = prod % c;
                    out.push(`> Expresión: residuo = (a * b) % c`);
                    out.push(`> 1° Paréntesis (): ${a} * ${b} = ${prod}`);
                    out.push(`> 2° Módulo (%): ${prod} % ${c} = ${rem}`);
                    typeBadge.textContent = 'Tipo: int';
                    resBadge.textContent = `Residuo = ${rem}`;
                } else if (selectEl.value === 'expr4') {
                    const r1 = (a + b) > 15;
                    const r2 = c !== 0;
                    const finalBool = r1 && r2;
                    out.push(`> Expresión: valido = (a + b > 15) && (c != 0)`);
                    out.push(`> 1° Relacional 1: (${a + b} > 15) -> ${r1}`);
                    out.push(`> 2° Relacional 2: (${c} != 0) -> ${r2}`);
                    out.push(`> 3° Lógico AND (&&): ${r1} && ${r2} = ${finalBool}`);
                    typeBadge.textContent = 'Tipo: bool';
                    resBadge.textContent = `valido = ${finalBool}`;
                }

                consoleEl.innerHTML = out.join('<br>');
            }

            evalBtn.addEventListener('click', () => {
                markCompleted('l2-reto');
                evaluateExpr();
            });
            [selectEl, varA, varB, varC].forEach((el) => el.addEventListener('change', evaluateExpr));
            evaluateExpr();
        })();

        // =================================================================
        // 7. SIMULADOR LECCIÓN 3: DEPURADOR DE RUTAS CONDICIONALES
        // =================================================================
        (function setupSimL3() {
            const gradeSlider = document.getElementById('progL3GradeSlider');
            const attendSlider = document.getElementById('progL3AttendSlider');
            const testBtn = document.getElementById('progL3TestBtn');
            const consoleEl = document.getElementById('progL3Console');
            if (!gradeSlider || !attendSlider || !consoleEl) return;

            const l1 = document.getElementById('progL3CodeLine1');
            const l2 = document.getElementById('progL3CodeLine2');
            const l3 = document.getElementById('progL3CodeLine3');
            const highlightClasses = ['bg-primary', 'bg-opacity-25', 'border-start', 'border-3', 'border-info'];

            function evaluateL3() {
                const calif = parseInt(gradeSlider.value, 10);
                const asist = parseInt(attendSlider.value, 10);

                document.getElementById('progL3GradeVal').textContent = calif;
                document.getElementById('progL3AttendVal').textContent = `${asist}%`;

                [l1, l2, l3].forEach((el) => el && el.classList.remove(...highlightClasses));

                const branchBadge = document.getElementById('progL3BranchBadge');
                const boolBadge = document.getElementById('progL3BoolBadge');

                const isExcelencia = calif >= 90 && asist >= 85;
                const isOrdinario = calif >= 70 && asist >= 80;

                const logs = [`> Evaluando calif = ${calif}, asist = ${asist}%`];
                logs.push(`> 1. if (${calif} >= 90 && ${asist} >= 85) -> ${isExcelencia}`);

                if (isExcelencia) {
                    if (l1) l1.classList.add(...highlightClasses);
                    branchBadge.textContent = 'Rama: if (Excelencia)';
                    boolBadge.textContent = 'estatus = "EXCELENCIA"';
                    boolBadge.className = 'badge bg-success text-white font-monospace';
                    logs.push(`> ✓ Entra al bloque IF -> estatus = "EXCELENCIA"`);
                } else {
                    logs.push(`> 2. else if (${calif} >= 70 && ${asist} >= 80) -> ${isOrdinario}`);
                    if (isOrdinario) {
                        if (l2) l2.classList.add(...highlightClasses);
                        branchBadge.textContent = 'Rama: else if (Acreditado)';
                        boolBadge.textContent = 'estatus = "ACREDITADO"';
                        boolBadge.className = 'badge bg-info text-dark font-monospace';
                        logs.push(`> ✓ Entra al bloque ELSE IF -> estatus = "ACREDITADO"`);
                    } else {
                        if (l3) l3.classList.add(...highlightClasses);
                        branchBadge.textContent = 'Rama: else (Recursamiento)';
                        boolBadge.textContent = 'estatus = "RECURSAMIENTO"';
                        boolBadge.className = 'badge bg-danger text-white font-monospace';
                        logs.push(`> ✕ Entra al bloque ELSE -> estatus = "RECURSAMIENTO"`);
                    }
                }

                consoleEl.innerHTML = logs.join('<br>');
            }

            gradeSlider.addEventListener('input', () => { markCompleted('l3-simulador'); evaluateL3(); });
            attendSlider.addEventListener('input', () => { markCompleted('l3-simulador'); evaluateL3(); });

            if (testBtn) {
                testBtn.addEventListener('click', () => {
                    const cases = [[95, 82], [93, 91], [69, 95], [88, 75]];
                    const selected = cases[Math.floor(Math.random() * cases.length)];
                    gradeSlider.value = selected[0];
                    attendSlider.value = selected[1];
                    markCompleted('l3-simulador');
                    evaluateL3();
                });
            }

            evaluateL3();
        })();

        // =================================================================
        // 8. RETO ABP LECCIÓN 4: SIMULADOR DE TRAZAS DE BUCLE (FOR)
        // =================================================================
        (function setupRetoL4() {
            const limitSlider = document.getElementById('progL4LimitInput');
            const nextBtn = document.getElementById('progL4NextIterBtn');
            const resetBtn = document.getElementById('progL4ResetBtn');
            const consoleEl = document.getElementById('progL4Console');
            if (!limitSlider || !nextBtn || !consoleEl) return;

            let i = 1;
            let suma = 1;
            let logs = [];

            function renderL4() {
                const n = parseInt(limitSlider.value, 10);
                document.getElementById('progL4CounterVal').textContent = i;
                document.getElementById('progL4AccumVal').textContent = suma;
                document.getElementById('progL4IterBadge').textContent = i <= n ? `Iteración i = ${i}` : `Fin (i > ${n})`;
                document.getElementById('progL4SumBadge').textContent = `suma = ${suma}`;
                consoleEl.innerHTML = logs.join('<br>');
                consoleEl.scrollTop = consoleEl.scrollHeight;
            }

            function resetCycle() {
                const n = parseInt(limitSlider.value, 10);
                i = 1;
                suma = 1;
                logs = [`> Inicio: N = ${n} | Iteración 1: i = 1 <= ${n} (true) -> suma = 1`];
                nextBtn.disabled = false;
                renderL4();
            }

            nextBtn.addEventListener('click', () => {
                const n = parseInt(limitSlider.value, 10);
                markCompleted('l4-reto');
                if (i < n) {
                    i++;
                    suma += i;
                    logs.push(`> Iteración ${i}: (${i} <= ${n} es true) -> suma = ${suma - i} + ${i} = ${suma}`);
                } else {
                    logs.push(`> Parada: i++ (${i + 1} <= ${n} es FALSE) -> Bucle finalizado. suma = ${suma}`);
                    nextBtn.disabled = true;
                }
                renderL4();
            });

            if (resetBtn) resetBtn.addEventListener('click', resetCycle);
            limitSlider.addEventListener('input', resetCycle);
            resetCycle();
        })();

        // =================================================================
        // 9. SIMULADOR LECCIÓN 5: INSPECTOR DE ARREGLOS Y FUNCIONES
        // =================================================================
        (function setupSimL5() {
            const cells = Array.from(app.querySelectorAll('.prog-l5-cell'));
            const maxBtn = document.getElementById('progL5MaxBtn');
            const sumBtn = document.getElementById('progL5SumBtn');
            const avgBtn = document.getElementById('progL5AvgBtn');
            const consoleEl = document.getElementById('progL5Console');
            const retBadge = document.getElementById('progL5ReturnBadge');
            if (!cells.length || !consoleEl) return;

            function getArray() {
                return cells.map((c) => parseFloat(c.value) || 0);
            }

            function clearCellHighlights() {
                cells.forEach((c) => {
                    const box = c.closest('.p-2');
                    if (box) box.style.borderColor = '';
                });
            }

            function runMax() {
                clearCellHighlights();
                const arr = getArray();
                let max = arr[0];
                let maxIdx = 0;
                arr.forEach((v, idx) => {
                    if (v > max) {
                        max = v;
                        maxIdx = idx;
                    }
                });
                const maxBox = cells[maxIdx]?.closest('.p-2');
                if (maxBox) maxBox.style.borderColor = '#facc15';
                if (retBadge) retBadge.textContent = `return: ${max}`;
                consoleEl.innerHTML = [
                    `> Ejecutando obtenerMaximo([${arr.join(', ')}])`,
                    `> Recorriendo índices [0..${arr.length - 1}]`,
                    `> Máximo hallado en notas[${maxIdx}] = ${max}`,
                    `> return ${max};`
                ].join('<br>');
            }

            function runSum() {
                clearCellHighlights();
                const arr = getArray();
                const total = arr.reduce((acc, v) => acc + v, 0);
                if (retBadge) retBadge.textContent = `return: ${total}`;
                consoleEl.innerHTML = [
                    `> Ejecutando sumarArreglo([${arr.join(', ')}])`,
                    `> Suma: ${arr.join(' + ')} = ${total}`,
                    `> return ${total};`
                ].join('<br>');
            }

            function runAvg() {
                clearCellHighlights();
                const arr = getArray();
                const total = arr.reduce((acc, v) => acc + v, 0);
                const avg = (total / arr.length).toFixed(2);
                if (retBadge) retBadge.textContent = `return: ${avg}`;
                consoleEl.innerHTML = [
                    `> Ejecutando calcularPromedio(notas) [notas.length = ${arr.length}]`,
                    `> Promedio: ${total} / ${arr.length} = ${avg}`,
                    `> return ${avg};`
                ].join('<br>');
            }

            if (maxBtn) maxBtn.addEventListener('click', () => { markCompleted('l5-simulador'); runMax(); });
            if (sumBtn) sumBtn.addEventListener('click', () => { markCompleted('l5-simulador'); runSum(); });
            if (avgBtn) avgBtn.addEventListener('click', () => { markCompleted('l5-simulador'); runAvg(); });
            runMax();
        })();

        // =================================================================
        // 10. MÓDULO DE CIERRE: ENCUESTA LIKERT Y CERTIFICADO PDF
        // =================================================================
        const surveyForm = document.getElementById('progLikertSurveyForm');
        const quickFillBtn = document.getElementById('progSurveyQuickFillBtn');
        const surveyAlert = document.getElementById('progSurveyStatusAlert');
        const certBtn1 = document.getElementById('progBtnDownloadCertSurvey');
        const certBtn2 = document.getElementById('progBtnDownloadCertPane');
        const lockNotice = document.getElementById('progCertLockNotice');

        function enableCertificates() {
            [certBtn1, certBtn2].forEach((btn) => {
                if (!btn) return;
                btn.disabled = false;
                btn.classList.remove('btn-secondary', 'opacity-75');
                btn.classList.add('btn-success');
                btn.textContent = '🎓 Descargar Certificado PDF';
            });
            if (lockNotice) {
                lockNotice.className = 'alert alert-success d-flex align-items-center gap-2 py-2 px-3 small mb-3';
                lockNotice.innerHTML = '<span>✅</span><div><strong>¡Certificado desbloqueado!</strong> Encuesta validada correctamente. Ya puedes descargar tu constancia.</div>';
            }
        }

        if (quickFillBtn && surveyForm) {
            quickFillBtn.addEventListener('click', () => {
                for (let i = 1; i <= 10; i++) {
                    const opt = surveyForm.querySelector(`input[name="prog_likert_${i}"][value="5"]`);
                    if (opt) opt.checked = true;
                }
                const c1 = document.getElementById('progComment1');
                if (c1 && !c1.value) {
                    c1.value = 'Los simuladores interactivos facilitaron comprender cada paso lógico sin memorizar sintaxis.';
                }
            });
        }

        if (surveyForm) {
            surveyForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const missing = [];
                for (let i = 1; i <= 10; i++) {
                    if (!surveyForm.querySelector(`input[name="prog_likert_${i}"]:checked`)) {
                        missing.push(i);
                    }
                }

                if (missing.length > 0) {
                    if (surveyAlert) {
                        surveyAlert.className = 'alert alert-warning small py-2 px-3 mb-3';
                        surveyAlert.textContent = `Responde todos los criterios de la escala Likert (faltan: ${missing.join(', ')}).`;
                    }
                    return;
                }

                markCompleted('cierre-encuesta');
                enableCertificates();

                if (surveyAlert) {
                    surveyAlert.className = 'alert alert-success small py-2 px-3 mb-3';
                    surveyAlert.innerHTML = '<strong>✓ Encuesta enviada con éxito.</strong> Tu certificado de acreditación ha sido desbloqueado.';
                }
            });
        }

        function printCertificate() {
            markCompleted('cierre-certificado');
            const alumno = certDataEl ? certDataEl.dataset.nombreAlumno : 'Estudiante de Ingeniería';
            const modulo = certDataEl ? certDataEl.dataset.moduloCompletado : 'Fundamentos de Programación';
            const fecha = certDataEl ? certDataEl.dataset.fechaEmision : new Date().toLocaleDateString('es-MX');
            const folioCert = certDataEl ? certDataEl.dataset.folio : 'LV-PROG-00001-0001';

            const win = window.open('', '_blank', 'width=980,height=720');
            if (!win) {
                window.print();
                return;
            }
            win.document.write(`<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Certificado - ${alumno}</title>
<style>
  body { margin: 0; padding: 32px; font-family: 'Segoe UI', system-ui, sans-serif; background: #f4f7fb; }
  .cert { max-width: 840px; margin: 0 auto; background: #fff; border: 8px double #1d4ed8; padding: 48px; text-align: center; }
  .inst { color: #1d4ed8; font-weight: 800; font-size: 13px; letter-spacing: 2px; text-transform: uppercase; }
  h1 { font-size: 28px; color: #0f172a; margin: 14px 0 22px; }
  .name { font-size: 30px; font-weight: 800; color: #1e3a8a; border-bottom: 2px solid #93c5fd; display: inline-block; padding: 0 20px 6px; margin: 14px 0; }
  .mod { background: #eff6ff; border: 2px solid #2563eb; color: #1e40af; font-weight: 700; font-size: 18px; padding: 12px 24px; border-radius: 10px; display: inline-block; margin: 18px 0 28px; }
  .meta { display: flex; justify-content: space-between; border-top: 1px solid #cbd5e1; padding-top: 16px; font-size: 13px; color: #475569; margin-top: 20px; }
  @media print { body { background: #fff; padding: 0; } }
</style>
</head>
<body>
  <div class="cert">
    <div class="inst">Instituto Tecnológico de Ciudad Victoria · Lógica Visual ABP</div>
    <h1>CERTIFICADO DE ACREDITACIÓN</h1>
    <p>Se otorga el presente reconocimiento a:</p>
    <div class="name">${alumno}</div>
    <p>Por haber acreditado satisfactoriamente las 5 lecciones interactivas, simuladores de lógica y retos de Aprendizaje Basado en Problemas del módulo:</p>
    <div class="mod">${modulo}</div>
    <div class="meta">
      <div><strong>Fecha de finalización:</strong> ${fecha}</div>
      <div><strong>Folio de verificación:</strong> ${folioCert}</div>
    </div>
  </div>
  <script>window.onload = function(){ window.print(); };<\/script>
</body>
</html>`);
            win.document.close();
        }

        [certBtn1, certBtn2].forEach((btn) => {
            if (btn) btn.addEventListener('click', printCertificate);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProgramacionApp);
    } else {
        initProgramacionApp();
    }
})();
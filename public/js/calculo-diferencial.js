/**
 * =====================================================================
 * Cálculo Diferencial (Enfoque Lógico-Visual) · Controlador Frontend UI/UX
 * Maneja:
 *  1. Navegación Split-Screen y progreso por lección (0/4 elementos)
 *  2. Pop-up Modal centrado de YouTube (no embebido en panel principal)
 *  3. Simuladores interactivos y Retos ABP con botón "Ejecutar paso"
 *  4. Encuesta Escala Likert (1-5) y desbloqueo de Certificado PDF
 * =====================================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    const wrapper = document.getElementById('calculoDiferencialApp');
    if (!wrapper) return;

    // -----------------------------------------------------------------
    // 1. Estado de Navegación y Progreso del Curso
    // -----------------------------------------------------------------
    const STORAGE_KEY = 'lv_calculo_diferencial_completed_v1';
    let completedItems = new Set();
    try {
        const saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        if (Array.isArray(saved)) {
            completedItems = new Set(saved);
        }
    } catch (_) {}

    const navButtons = wrapper.querySelectorAll('[data-cd-item]');
    const contentPanes = wrapper.querySelectorAll('[data-cd-pane]');
    const totalItemsCount = navButtons.length; // 22 items (5 lecciones * 4 + 2 cierre)

    function saveProgress() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(Array.from(completedItems)));
        } catch (_) {}
    }

    function updateProgressCounters() {
        // Actualizar checks individuales
        navButtons.forEach(btn => {
            const itemId = btn.getAttribute('data-cd-item');
            if (completedItems.has(itemId)) {
                btn.classList.add('is-completed');
            }
        });

        // Actualizar contadores por lección (ej. "0/4 elementos")
        for (let lesson = 1; lesson <= 6; lesson++) {
            const lessonBtns = wrapper.querySelectorAll(`[data-cd-lesson="${lesson}"]`);
            const badge = document.getElementById(`cdLessonCount-${lesson}`);
            if (badge && lessonBtns.length > 0) {
                let done = 0;
                lessonBtns.forEach(b => {
                    if (completedItems.has(b.getAttribute('data-cd-item'))) done++;
                });
                badge.textContent = `${done}/${lessonBtns.length} elementos`;
            }
        }

        // Actualizar barra general
        const completedTotal = completedItems.size;
        const pct = totalItemsCount > 0 ? Math.round((completedTotal / totalItemsCount) * 100) : 0;
        const globalBar = document.getElementById('cdGlobalProgressBar');
        const globalText = document.getElementById('cdGlobalProgressText');
        if (globalBar) globalBar.style.width = `${pct}%`;
        if (globalText) globalText.textContent = `${completedTotal}/${totalItemsCount} elementos (${pct}%)`;
    }

    function activatePane(itemId, scrollWorkspace = true) {
        navButtons.forEach(btn => {
            btn.classList.toggle('is-active', btn.getAttribute('data-cd-item') === itemId);
        });

        contentPanes.forEach(pane => {
            pane.classList.toggle('is-active', pane.getAttribute('data-cd-pane') === itemId);
        });

        completedItems.add(itemId);
        saveProgress();
        updateProgressCounters();

        // Abrir el acordeón correspondiente si está cerrado
        const activeBtn = wrapper.querySelector(`[data-cd-item="${itemId}"]`);
        if (activeBtn) {
            const collapseEl = activeBtn.closest('.accordion-collapse');
            if (collapseEl && !collapseEl.classList.contains('show') && window.bootstrap) {
                bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false }).show();
            }
        }

        if (scrollWorkspace && window.innerWidth < 992) {
            const mainPanel = document.getElementById('cdMainWorkspace');
            if (mainPanel) mainPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // -----------------------------------------------------------------
    // 2. Pop-up Modal de Vídeo de YouTube
    // -----------------------------------------------------------------
    const videoModalEl = document.getElementById('cdYoutubeModal');
    const videoIframe = document.getElementById('cdYoutubeIframe');
    const videoModalTitle = document.getElementById('cdYoutubeModalTitle');
    const videoModalChannel = document.getElementById('cdYoutubeModalChannel');
    const videoModalDirectLink = document.getElementById('cdYoutubeDirectLink');
    const videoModalPlaylistWrap = document.getElementById('cdYoutubePlaylistWrap');
    const videoModalPlaylistLink = document.getElementById('cdYoutubePlaylistLink');

    function openVideoModal({ title, channel, embedUrl, watchUrl, playlistUrl }) {
        if (!videoModalEl || !videoIframe) return;
        if (videoModalTitle) videoModalTitle.textContent = title || 'Vídeo de la Lección';
        if (videoModalChannel) videoModalChannel.textContent = channel || 'Canal Oficial';
        if (videoModalDirectLink) videoModalDirectLink.href = watchUrl || 'https://youtube.com';

        if (playlistUrl && videoModalPlaylistWrap && videoModalPlaylistLink) {
            videoModalPlaylistWrap.classList.remove('d-none');
            videoModalPlaylistLink.href = playlistUrl;
        } else if (videoModalPlaylistWrap) {
            videoModalPlaylistWrap.classList.add('d-none');
        }

        videoIframe.src = embedUrl;
        if (window.bootstrap) {
            const modal = bootstrap.Modal.getOrCreateInstance(videoModalEl);
            modal.show();
        }
    }

    if (videoModalEl && videoIframe) {
        videoModalEl.addEventListener('hidden.bs.modal', () => {
            videoIframe.src = '';
        });
    }

    // Eventos de clic en ítems del sidebar
    navButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const itemId = btn.getAttribute('data-cd-item');
            const isVideo = btn.getAttribute('data-cd-type') === 'video';

            activatePane(itemId, !isVideo);

            if (isVideo) {
                openVideoModal({
                    title: btn.getAttribute('data-video-title'),
                    channel: btn.getAttribute('data-video-channel'),
                    embedUrl: btn.getAttribute('data-video-embed'),
                    watchUrl: btn.getAttribute('data-video-url'),
                    playlistUrl: btn.getAttribute('data-video-playlist')
                });
            }
        });
    });

    // Botones disparadores de modal de video desde tarjetas de respaldo
    wrapper.querySelectorAll('[data-open-video-modal]').forEach(trigger => {
        trigger.addEventListener('click', () => {
            const targetItem = trigger.getAttribute('data-open-video-modal');
            const sidebarBtn = wrapper.querySelector(`[data-cd-item="${targetItem}"]`);
            if (sidebarBtn) sidebarBtn.click();
        });
    });

    // Botones de navegación "Siguiente / Anterior" dentro de los paneles
    wrapper.querySelectorAll('[data-cd-goto]').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-cd-goto');
            const sidebarBtn = wrapper.querySelector(`[data-cd-item="${targetId}"]`);
            if (sidebarBtn) sidebarBtn.click();
        });
    });

    // Marcar el primer elemento activo al iniciar
    completedItems.add('l1-lectura');
    saveProgress();
    updateProgressCounters();

    // -----------------------------------------------------------------
    // 3. SIMULADOR LECCIÓN 1: Funciones como Cajas Negras (Inputs / Outputs)
    // -----------------------------------------------------------------
    const l1FuncSelect = document.getElementById('l1FuncSelect');
    const l1InputX = document.getElementById('l1InputX');
    const l1StepBtn = document.getElementById('l1StepBtn');
    const l1ResetBtn = document.getElementById('l1ResetBtn');
    const l1BoxInputDisplay = document.getElementById('l1BoxInputDisplay');
    const l1BoxRuleDisplay = document.getElementById('l1BoxRuleDisplay');
    const l1BoxOutputDisplay = document.getElementById('l1BoxOutputDisplay');
    const l1Console = document.getElementById('l1Console');
    const l1StepBadge = document.getElementById('l1StepBadge');

    if (l1FuncSelect && l1InputX && l1StepBtn) {
        const testSequence = [-2, 0, 2, 4, 6];
        let l1StepIndex = 0;

        function evaluateL1Function(fnKey, x) {
            switch (fnKey) {
                case 'lineal':
                    return {
                        rule: 'f(x) = 2x + 3',
                        domain: 'ℝ (-∞, +∞)',
                        valid: true,
                        y: +(2 * x + 3).toFixed(4),
                        explanation: `Sustituyendo x = ${x}: f(${x}) = 2(${x}) + 3 = ${2 * x + 3}`
                    };
                case 'cuadratica':
                    return {
                        rule: 'f(x) = x² - 1',
                        domain: 'ℝ (-∞, +∞)',
                        valid: true,
                        y: +(x * x - 1).toFixed(4),
                        explanation: `Sustituyendo x = ${x}: f(${x}) = (${x})² - 1 = ${x * x - 1}`
                    };
                case 'racional':
                    if (Math.abs(x - 2) < 1e-9) {
                        return {
                            rule: 'f(x) = 1 / (x - 2)',
                            domain: 'ℝ \\ {2}',
                            valid: false,
                            y: 'ERROR (Div/0)',
                            explanation: `¡Excepción de Dominio! En x = 2 el denominador (x - 2) es 0. x = 2 NO pertenece al dominio.`
                        };
                    }
                    return {
                        rule: 'f(x) = 1 / (x - 2)',
                        domain: 'ℝ \\ {2}',
                        valid: true,
                        y: +(1 / (x - 2)).toFixed(4),
                        explanation: `Sustituyendo x = ${x}: f(${x}) = 1 / (${x} - 2) = ${(1 / (x - 2)).toFixed(4)}`
                    };
                case 'radical':
                    if (x + 4 < 0) {
                        return {
                            rule: 'f(x) = √(x + 4)',
                            domain: '[-4, +∞)',
                            valid: false,
                            y: 'ERROR (√neg)',
                            explanation: `¡Excepción de Dominio! Para x = ${x}, el radicando (${x} + 4 = ${x + 4}) es negativo.`
                        };
                    }
                    return {
                        rule: 'f(x) = √(x + 4)',
                        domain: '[-4, +∞)',
                        valid: true,
                        y: +Math.sqrt(x + 4).toFixed(4),
                        explanation: `Sustituyendo x = ${x}: f(${x}) = √(${x} + 4) = ${Math.sqrt(x + 4).toFixed(4)}`
                    };
                default:
                    return { rule: 'f(x) = 2x + 3', domain: 'ℝ', valid: true, y: 2 * x + 3, explanation: '' };
            }
        }

        function renderL1(xVal, stepLabel) {
            const res = evaluateL1Function(l1FuncSelect.value, xVal);
            if (l1BoxInputDisplay) l1BoxInputDisplay.textContent = `x = ${xVal}`;
            if (l1BoxRuleDisplay) l1BoxRuleDisplay.textContent = res.rule;
            if (l1BoxOutputDisplay) {
                l1BoxOutputDisplay.textContent = res.valid ? `y = ${res.y}` : res.y;
                l1BoxOutputDisplay.className = res.valid
                    ? 'fw-bold fs-5 text-cyan font-monospace'
                    : 'fw-bold fs-6 text-danger font-monospace';
            }
            if (l1Console) {
                const statusColor = res.valid ? 'text-cyan' : 'text-danger';
                l1Console.innerHTML = `
                    <div class="mb-1"><span class="${statusColor}">&gt;</span> [${stepLabel}] <strong>Input (Variable Independiente):</strong> x = ${xVal}</div>
                    <div class="mb-1"><span class="${statusColor}">&gt;</span> <strong>Regla de correspondencia:</strong> ${res.rule} | Dominio: ${res.domain}</div>
                    <div class="${statusColor}">&gt; <strong>Output (Variable Dependiente):</strong> ${res.explanation}</div>
                `;
            }
        }

        l1InputX.addEventListener('input', () => {
            const val = parseFloat(l1InputX.value);
            if (!isNaN(val)) renderL1(val, 'Tiempo Real');
        });

        l1FuncSelect.addEventListener('change', () => {
            const val = parseFloat(l1InputX.value) || 0;
            renderL1(val, 'Cambio de Función');
        });

        l1StepBtn.addEventListener('click', () => {
            const xVal = testSequence[l1StepIndex % testSequence.length];
            l1StepIndex++;
            l1InputX.value = xVal;
            if (l1StepBadge) l1StepBadge.textContent = `Paso ${l1StepIndex} / ${testSequence.length}`;
            renderL1(xVal, `Ejecución Paso ${l1StepIndex}`);
        });

        if (l1ResetBtn) {
            l1ResetBtn.addEventListener('click', () => {
                l1StepIndex = 0;
                l1InputX.value = 1;
                if (l1StepBadge) l1StepBadge.textContent = 'Paso 0 / 5';
                renderL1(1, 'Reinicio');
            });
        }
    }

    // -----------------------------------------------------------------
    // 4. RETO ABP LECCIÓN 2: Programar Condición de Paro en Límites
    // -----------------------------------------------------------------
    const l2EpsilonSelect = document.getElementById('l2EpsilonSelect');
    const l2StepBtn = document.getElementById('l2StepBtn');
    const l2ResetBtn = document.getElementById('l2ResetBtn');
    const l2StepBadge = document.getElementById('l2StepBadge');
    const l2Console = document.getElementById('l2Console');
    const l2TableBody = document.getElementById('l2IterTableBody');

    if (l2StepBtn && l2Console) {
        const iterations = [
            { step: 1, xLeft: 1.5, yLeft: 3.5, xRight: 2.5, yRight: 4.5, delta: 0.5 },
            { step: 2, xLeft: 1.9, yLeft: 3.9, xRight: 2.1, yRight: 4.1, delta: 0.1 },
            { step: 3, xLeft: 1.99, yLeft: 3.99, xRight: 2.01, yRight: 4.01, delta: 0.01 },
            { step: 4, xLeft: 1.999, yLeft: 3.999, xRight: 2.001, yRight: 4.001, delta: 0.001 },
            { step: 5, xLeft: 1.9999, yLeft: 3.9999, xRight: 2.0001, yRight: 4.0001, delta: 0.0001 }
        ];
        let l2Current = 0;

        function resetL2() {
            l2Current = 0;
            if (l2StepBadge) l2StepBadge.textContent = 'Iteración 0 / 5';
            if (l2TableBody) l2TableBody.innerHTML = '';
            l2StepBtn.disabled = false;
            l2StepBtn.textContent = 'Ejecutar paso ▷';
            l2Console.innerHTML = `<span class="text-cyan">&gt;</span> Ciclo listo. Función: g(x) = (x² - 4)/(x - 2) cuando x → 2. Selecciona ε y presiona "Ejecutar paso".`;
        }

        l2StepBtn.addEventListener('click', () => {
            const eps = parseFloat(l2EpsilonSelect ? l2EpsilonSelect.value : '0.001');
            if (l2Current < iterations.length) {
                const it = iterations[l2Current];
                l2Current++;
                if (l2StepBadge) l2StepBadge.textContent = `Iteración ${l2Current} / ${iterations.length}`;

                const reached = it.delta <= eps;
                if (l2TableBody) {
                    const tr = document.createElement('tr');
                    if (reached) tr.className = 'table-success fw-bold';
                    tr.innerHTML = `
                        <td>#${it.step}</td>
                        <td>${it.xLeft}</td>
                        <td>${it.yLeft}</td>
                        <td>${it.xRight}</td>
                        <td>${it.yRight}</td>
                        <td>${it.delta}</td>
                        <td>${reached ? '✅ PARO (|f(x)-4| ≤ ε)' : '🔄 Continúa'}</td>
                    `;
                    l2TableBody.appendChild(tr);
                }

                if (reached || l2Current === iterations.length) {
                    l2Console.innerHTML = `
                        <div class="text-success fw-bold mb-1">&gt; [CONDICIÓN DE PARO ALCANZADA en Iteración #${it.step}]</div>
                        <div>&gt; |x - 2| = ${it.delta} ≤ ε (${eps}) ⇒ lim(x→2⁻) g(x) = ${it.yLeft} ≈ 4 y lim(x→2⁺) g(x) = ${it.yRight} ≈ 4.</div>
                        <div class="text-cyan">&gt; Conclusión lógica: Como ambos límites laterales coinciden, lim(x→2) g(x) = 4 existe (discontinuidad evitable en x=2).</div>
                    `;
                    l2StepBtn.disabled = true;
                    l2StepBtn.textContent = 'Condición de paro cumplida ✓';
                } else {
                    l2Console.innerHTML = `
                        <div class="text-cyan mb-1">&gt; [Iteración #${it.step}] Evaluando laterales: x⁻ = ${it.xLeft} → g(x⁻) = ${it.yLeft} | x⁺ = ${it.xRight} → g(x⁺) = ${it.yRight}</div>
                        <div class="text-warning">&gt; Error actual |f(x) - L| = ${it.delta} > ε (${eps}). El ciclo while continúa aproximándose...</div>
                    `;
                }
            }
        });

        if (l2ResetBtn) l2ResetBtn.addEventListener('click', resetL2);
        if (l2EpsilonSelect) l2EpsilonSelect.addEventListener('change', resetL2);
    }

    // -----------------------------------------------------------------
    // 5. SIMULADOR LECCIÓN 3: Deslizador Visual Reduciendo h a 0
    // -----------------------------------------------------------------
    const l3SliderH = document.getElementById('l3SliderH');
    const l3StepBtn = document.getElementById('l3StepBtn');
    const l3ResetBtn = document.getElementById('l3ResetBtn');
    const l3HBadge = document.getElementById('l3HBadge');
    const l3SlopeBadge = document.getElementById('l3SlopeBadge');
    const l3SecantLine = document.getElementById('l3SecantLine');
    const l3MovingPoint = document.getElementById('l3MovingPoint');
    const l3MovingLabel = document.getElementById('l3MovingLabel');
    const l3Console = document.getElementById('l3Console');

    if (l3SliderH && l3StepBtn) {
        // Curva f(x) = 0.25 * x^2 en x0 = 2 => f(2) = 1, derivada exacta f'(2) = 0.5 * 2 = 1.0
        // Coordenadas SVG: origen en (40, 190), escalaX = 45 px/u, escalaY = 30 px/u
        const hSteps = [2.0, 1.5, 1.0, 0.5, 0.2, 0.05, 0.001];
        let l3Idx = 0;

        function updateL3Visual(hVal) {
            const x0 = 2;
            const y0 = 0.25 * x0 * x0; // 1
            const x1 = x0 + hVal;
            const y1 = 0.25 * x1 * x1;
            const mSec = (y1 - y0) / (x1 - x0); // = 1 + 0.25*h

            const svgX0 = 40 + x0 * 45; // 130
            const svgY0 = 190 - y0 * 30; // 160
            const svgX1 = 40 + x1 * 45;
            const svgY1 = 190 - y1 * 30;

            // Extender la recta secante a lo largo del lienzo SVG (x desde 0.4 hasta 4.6)
            const xStart = 0.4;
            const yStart = y0 + mSec * (xStart - x0);
            const xEnd = 4.6;
            const yEnd = y0 + mSec * (xEnd - x0);

            if (l3SecantLine) {
                l3SecantLine.setAttribute('x1', (40 + xStart * 45).toFixed(1));
                l3SecantLine.setAttribute('y1', (190 - yStart * 30).toFixed(1));
                l3SecantLine.setAttribute('x2', (40 + xEnd * 45).toFixed(1));
                l3SecantLine.setAttribute('y2', (190 - yEnd * 30).toFixed(1));
                l3SecantLine.setAttribute('stroke', hVal <= 0.01 ? '#10b981' : '#f59e0b');
            }
            if (l3MovingPoint) {
                l3MovingPoint.setAttribute('cx', svgX1.toFixed(1));
                l3MovingPoint.setAttribute('cy', svgY1.toFixed(1));
            }
            if (l3MovingLabel) {
                l3MovingLabel.setAttribute('x', (svgX1 + 8).toFixed(1));
                l3MovingLabel.setAttribute('y', (svgY1 - 8).toFixed(1));
                l3MovingLabel.textContent = hVal <= 0.01 ? 'Q ≈ P (Tangente)' : `Q(2+${hVal.toFixed(2)})`;
            }

            if (l3HBadge) l3HBadge.textContent = `h = ${hVal.toFixed(3)}`;
            if (l3SlopeBadge) l3SlopeBadge.textContent = `m_sec = ${mSec.toFixed(4)} (Exacta f'(2) = 1.0000)`;

            if (l3Console) {
                if (hVal <= 0.01) {
                    l3Console.innerHTML = `
                        <div class="text-success fw-bold mb-1">&gt; ¡LÍMITE ALCANZADO (h → 0)! La Recta Secante se convirtió en Recta Tangente.</div>
                        <div>&gt; f'(2) = lim(h→0) [f(2+h) - f(2)] / h = ${mSec.toFixed(4)} ≈ 1.0000 (Tasa de cambio instantánea).</div>
                    `;
                } else {
                    l3Console.innerHTML = `
                        <div class="text-cyan mb-1">&gt; Distancia actual entre puntos: h = ${hVal.toFixed(3)} | Punto Q(${x1.toFixed(3)}, ${y1.toFixed(3)})</div>
                        <div class="text-warning">&gt; Pendiente Secante (velocidad promedio): m = (${y1.toFixed(3)} - ${y0}) / ${hVal.toFixed(3)} = ${mSec.toFixed(4)}</div>
                    `;
                }
            }
        }

        l3SliderH.addEventListener('input', () => {
            updateL3Visual(parseFloat(l3SliderH.value));
        });

        l3StepBtn.addEventListener('click', () => {
            l3Idx = Math.min(l3Idx + 1, hSteps.length - 1);
            const hVal = hSteps[l3Idx];
            l3SliderH.value = hVal;
            updateL3Visual(hVal);
        });

        if (l3ResetBtn) {
            l3ResetBtn.addEventListener('click', () => {
                l3Idx = 0;
                l3SliderH.value = 2.0;
                updateL3Visual(2.0);
            });
        }
    }

    // -----------------------------------------------------------------
    // 6. RETO ABP LECCIÓN 4: Bloques Lógicos para Regla de la Cadena
    // -----------------------------------------------------------------
    const l4BlocksPool = document.getElementById('l4BlocksPool');
    const l4Slot = document.getElementById('l4AssemblySlot');
    const l4StepBtn = document.getElementById('l4StepBtn');
    const l4ResetBtn = document.getElementById('l4ResetBtn');
    const l4Console = document.getElementById('l4Console');
    const l4StepBadge = document.getElementById('l4StepBadge');

    if (l4BlocksPool && l4Slot && l4StepBtn) {
        const correctOrder = [
            { id: 'ext', label: 'f\'(g(x)) = 4(3x + 1)³' },
            { id: 'mul', label: '·' },
            { id: 'int', label: 'g\'(x) = d/dx(3x + 1) = 3' },
            { id: 'res', label: '= 12(3x + 1)³' }
        ];
        let assembled = [];

        function renderL4Slot() {
            if (assembled.length === 0) {
                l4Slot.innerHTML = '<span class="text-slate-400 small">Haz clic en los bloques lógicos de arriba o presiona "Ejecutar paso" para ensamblar d/dx[(3x + 1)⁴]...</span>';
            } else {
                l4Slot.innerHTML = assembled
                    .map(item => `<span class="badge bg-info text-dark px-3 py-2 fs-6 font-monospace">${item.label}</span>`)
                    .join(' ');
            }
            if (l4StepBadge) l4StepBadge.textContent = `Bloques: ${assembled.length} / 4`;
        }

        l4BlocksPool.querySelectorAll('[data-block-id]').forEach(chip => {
            chip.addEventListener('click', () => {
                const id = chip.getAttribute('data-block-id');
                const expected = correctOrder[assembled.length];
                if (!expected) return;

                if (id === expected.id) {
                    assembled.push(expected);
                    chip.classList.add('is-placed');
                    renderL4Slot();
                    if (assembled.length === correctOrder.length) {
                        l4Console.innerHTML = `
                            <div class="text-success fw-bold mb-1">&gt; ¡Árbol lógico de la Regla de la Cadena completado con éxito!</div>
                            <div class="text-cyan">&gt; d/dx[(3x + 1)⁴] = 4(3x + 1)³ · 3 = 12(3x + 1)³. Y para el ejemplo combinado y = x²(3x+1)⁴: y' = 2x(3x+1)⁴ + 12x²(3x+1)³.</div>
                        `;
                    } else {
                        l4Console.innerHTML = `<div class="text-cyan">&gt; Bloque correcto [${expected.label}]. Selecciona el siguiente eslabón de la cadena.</div>`;
                    }
                } else {
                    l4Console.innerHTML = `<div class="text-warning">&gt; Orden lógico incorrecto: recuerda derivar primero la función exterior dejando el paquete interior intacto, y luego multiplicar por la derivada interior.</div>`;
                }
            });
        });

        l4StepBtn.addEventListener('click', () => {
            if (assembled.length < correctOrder.length) {
                const nextBlock = correctOrder[assembled.length];
                const chip = l4BlocksPool.querySelector(`[data-block-id="${nextBlock.id}"]`);
                if (chip) chip.click();
            }
        });

        if (l4ResetBtn) {
            l4ResetBtn.addEventListener('click', () => {
                assembled = [];
                l4BlocksPool.querySelectorAll('[data-block-id]').forEach(c => c.classList.remove('is-placed'));
                renderL4Slot();
                l4Console.innerHTML = `<span class="text-cyan">&gt;</span> Reiniciado. Arma la derivada de f(g(x)) = (3x + 1)⁴ de afuera hacia adentro.`;
            });
        }
    }

    // -----------------------------------------------------------------
    // 7. RETO ABP LECCIÓN 5: Optimización de Caja (Máximos y Mínimos)
    // -----------------------------------------------------------------
    const l5SliderX = document.getElementById('l5SliderX');
    const l5StepBtn = document.getElementById('l5StepBtn');
    const l5ResetBtn = document.getElementById('l5ResetBtn');
    const l5XBadge = document.getElementById('l5XBadge');
    const l5VolBadge = document.getElementById('l5VolBadge');
    const l5DerivBadge = document.getElementById('l5DerivBadge');
    const l5PointMarker = document.getElementById('l5PointMarker');
    const l5Console = document.getElementById('l5Console');

    if (l5SliderX && l5StepBtn) {
        // Lámina de 24 cm x 16 cm. Se recortan cuadrados de lado x en las 4 esquinas:
        // V(x) = x(24 - 2x)(16 - 2x) = 4x³ - 80x² + 384x, con 0 < x < 8
        // V'(x) = 12x² - 160x + 384 = 0 => x = (40 - 8√7)/6 ≈ 3.1390 cm, V_max ≈ 546.08 cm³
        const xSteps = [1.0, 2.0, 2.5, 3.0, 3.14];
        let l5Idx = 0;

        function evalBox(x) {
            const vol = 4 * Math.pow(x, 3) - 80 * Math.pow(x, 2) + 384 * x;
            const d1 = 12 * Math.pow(x, 2) - 160 * x + 384;
            const d2 = 24 * x - 160;
            return { vol, d1, d2 };
        }

        function updateL5(xVal) {
            const { vol, d1, d2 } = evalBox(xVal);
            if (l5XBadge) l5XBadge.textContent = `Corte x = ${xVal.toFixed(2)} cm`;
            if (l5VolBadge) l5VolBadge.textContent = `V(x) = ${vol.toFixed(2)} cm³`;
            if (l5DerivBadge) l5DerivBadge.textContent = `V'(x) = ${d1.toFixed(2)}`;

            if (l5PointMarker) {
                // SVG X: 40 + (x / 8) * 240, SVG Y: 185 - (vol / 600) * 140
                const cx = 40 + (xVal / 8) * 240;
                const cy = 185 - (vol / 600) * 140;
                l5PointMarker.setAttribute('cx', cx.toFixed(1));
                l5PointMarker.setAttribute('cy', cy.toFixed(1));
            }

            if (l5Console) {
                const isOptimum = Math.abs(xVal - 3.14) < 0.05;
                if (isOptimum) {
                    l5Console.innerHTML = `
                        <div class="text-success fw-bold mb-1">&gt; ¡PUNTO CRÍTICO ÓPTIMO ALCANZADO (V'(x) ≈ 0)!</div>
                        <div>&gt; En x ≈ 3.14 cm: V'(3.14) ≈ 0 y Segunda Derivada V''(3.14) = ${d2.toFixed(1)} &lt; 0 (Cóncava hacia abajo ⇒ MÁXIMO).</div>
                        <div class="text-cyan">&gt; Dimensiones óptimas de la caja: Alto = 3.14 cm, Largo = 17.72 cm, Ancho = 9.72 cm → Volumen Máximo = ${vol.toFixed(2)} cm³.</div>
                    `;
                } else {
                    const trend = d1 > 0 ? 'V\'(x) > 0 (El volumen sigue creciendo al aumentar x)' : 'V\'(x) < 0 (Pasaste el máximo, el volumen decrece)';
                    l5Console.innerHTML = `
                        <div class="text-cyan mb-1">&gt; Evaluando restricción x = ${xVal.toFixed(2)} cm → Volumen V(x) = ${vol.toFixed(2)} cm³</div>
                        <div class="text-warning">&gt; Primera derivada V'(x) = ${d1.toFixed(2)} → ${trend}.</div>
                    `;
                }
            }
        }

        l5SliderX.addEventListener('input', () => {
            updateL5(parseFloat(l5SliderX.value));
        });

        l5StepBtn.addEventListener('click', () => {
            l5Idx = Math.min(l5Idx + 1, xSteps.length - 1);
            const val = xSteps[l5Idx];
            l5SliderX.value = val;
            updateL5(val);
        });

        if (l5ResetBtn) {
            l5ResetBtn.addEventListener('click', () => {
                l5Idx = 0;
                l5SliderX.value = 1.0;
                updateL5(1.0);
            });
        }
    }

    // -----------------------------------------------------------------
    // 8. MÓDULO DE CIERRE: Encuesta Escala Likert (1-5) y Certificado PDF
    // -----------------------------------------------------------------
    const surveyForm = document.getElementById('cdLikertSurveyForm');
    const btnQuickFill = document.getElementById('cdSurveyQuickFillBtn');
    const btnCertSurvey = document.getElementById('cdBtnDownloadCertSurvey');
    const btnCertPane = document.getElementById('cdBtnDownloadCertPane');
    const surveyAlert = document.getElementById('cdSurveyStatusAlert');
    const certLockNotice = document.getElementById('cdCertLockNotice');

    const SURVEY_STORAGE_KEY = 'lv_calculo_diferencial_survey_done_v1';

    function unlockCertificateButtons() {
        [btnCertSurvey, btnCertPane].forEach(btn => {
            if (!btn) return;
            btn.disabled = false;
            btn.removeAttribute('disabled');
            btn.classList.remove('btn-secondary', 'opacity-75');
            btn.classList.add('btn-cyan', 'shadow-cyan');
            btn.innerHTML = '🎓 Descargar Certificado Oficial (PDF)';
        });

        if (certLockNotice) {
            certLockNotice.className = 'alert alert-success d-flex align-items-center gap-2 py-2 px-3 small mb-3';
            certLockNotice.innerHTML = '<span>✅</span> <div><strong>Encuesta completada:</strong> Tu certificado de acreditación está desbloqueado y listo para descargar en PDF.</div>';
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
                const radio5 = surveyForm.querySelector(`input[name="likert_${q}"][value="5"]`);
                if (radio5) radio5.checked = true;
            }
            const c1 = document.getElementById('cdComment1');
            const c2 = document.getElementById('cdComment2');
            if (c1 && !c1.value) c1.value = 'Los simuladores de cajas negras, condición de paro en límites y el deslizador de la recta tangente hicieron muy visual cada concepto.';
            if (c2 && !c2.value) c2.value = 'Excelente organización de las 5 lecciones y las guías PDF descargables.';
        });
    }

    if (surveyForm) {
        surveyForm.addEventListener('submit', (e) => {
            e.preventDefault();

            // Verificar que las 10 dimensiones de la Escala Likert estén respondidas
            let missing = [];
            for (let q = 1; q <= 10; q++) {
                const checked = surveyForm.querySelector(`input[name="likert_${q}"]:checked`);
                if (!checked) missing.push(q);
            }

            if (missing.length > 0) {
                if (surveyAlert) {
                    surveyAlert.className = 'alert alert-warning small py-2 px-3 mt-3';
                    surveyAlert.innerHTML = `⚠️ Por favor selecciona una opción en todas las afirmaciones de la Escala Likert (faltan: #${missing.join(', #')}). Puedes usar el botón "Autocompletar demostración" para probar rápidamente.`;
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
                surveyAlert.innerHTML = '✅ <strong>¡Gracias por tu participación!</strong> La encuesta ha sido registrada y el botón <strong>"Descargar Certificado"</strong> ha sido desbloqueado.';
                surveyAlert.classList.remove('d-none');
            }
        });
    }

    // =====================================================================
    // GENERACIÓN DE CERTIFICADO PDF CON PARÁMETROS DINÁMICOS DE SESIÓN
    // =====================================================================
    // NOTA DE ARQUITECTURA (INYECCIÓN DE PARÁMETROS DINÁMICOS DE SESIÓN):
    // En el contenedor #cdCertificateData (renderizado en PHP desde $_SESSION / Auth::user()),
    // se inyectan los atributos dinámicos de sesión del estudiante autenticado:
    //   - data-nombre-alumno     -> Nombre_Alumno     (ej. $usuario->nombre)
    //   - data-modulo-completado -> Modulo_Completado (ej. "Cálculo Diferencial (Enfoque Lógico-Visual)")
    //   - data-fecha-emision     -> Fecha             (ej. date('d/m/Y'))
    //   - data-folio             -> Folio único de validación
    // Si se integra con un generador PDF backend (como Dompdf / TCPDF en AprendizajeController::constancia)
    // o cliente (window.print / jsPDF), estos 3 parámetros se leen directamente de aquí.
    // =====================================================================
    function generateAndDownloadCertificatePdf() {
        const certDataEl = document.getElementById('cdCertificateData');
        const nombreAlumno = certDataEl?.getAttribute('data-nombre-alumno') || 'Estudiante de Ingeniería';
        const moduloCompletado = certDataEl?.getAttribute('data-modulo-completado') || 'Cálculo Diferencial (Enfoque Lógico-Visual)';
        const fechaEmision = certDataEl?.getAttribute('data-fecha-emision') || new Date().toLocaleDateString('es-MX');
        const folio = certDataEl?.getAttribute('data-folio') || 'LV-CD-2026-001';

        // Actualizar vista previa en el DOM antes de abrir ventana de impresión PDF
        const nameNode = document.getElementById('cdCertStudentName');
        const modNode = document.getElementById('cdCertModuleName');
        const dateNode = document.getElementById('cdCertDate');
        if (nameNode) nameNode.textContent = nombreAlumno;
        if (modNode) modNode.textContent = moduloCompletado;
        if (dateNode) dateNode.textContent = fechaEmision;

        // Abrir ventana de impresión / guardado en PDF nativa con formato oficial
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
        body { font-family: 'Inter', 'Segoe UI', Arial, sans-serif; background: #f8fafc; margin: 0; padding: 20px; color: #0a1128; }
        .cert-box { max-width: 920px; margin: 0 auto; background: #ffffff; border: 10px double #0a1128; padding: 48px 56px; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-25deg); font-size: 72px; font-weight: 900; color: rgba(8, 145, 178, 0.05); pointer-events: none; white-space: nowrap; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 28px; }
        .brand { font-size: 22px; font-weight: 800; color: #0a1128; }
        .sub { font-size: 13px; color: #475569; }
        .badge { background: #0891b2; color: #ffffff; padding: 6px 14px; border-radius: 999px; font-size: 12px; font-weight: 700; letter-spacing: 0.05em; }
        .body { text-align: center; padding: 10px 0 24px; }
        .pre { text-transform: uppercase; letter-spacing: 0.12em; font-size: 12px; color: #64748b; margin-bottom: 10px; }
        .student { font-size: 36px; font-weight: 800; color: #0a1128; margin: 8px 0 18px; }
        .desc { font-size: 15px; color: #334155; max-width: 680px; margin: 0 auto 20px; line-height: 1.5; }
        .module-pill { display: inline-block; background: #f0f9ff; border: 2px solid #0284c7; color: #0369a1; padding: 12px 32px; border-radius: 12px; font-size: 22px; font-weight: 800; margin-bottom: 16px; }
        .footer { display: flex; justify-content: space-between; align-items: flex-end; border-top: 1px solid #e2e8f0; padding-top: 24px; margin-top: 20px; font-size: 12px; color: #475569; }
        .sign { border-top: 1px solid #0a1128; padding-top: 6px; width: 220px; text-align: center; font-weight: 700; color: #0a1128; }
        @media print { body { background: #ffffff; padding: 0; } .cert-box { box-shadow: none; } }
    </style>
</head>
<body>
    <div class="cert-box">
        <div class="watermark">LÓGICA VISUAL ABP</div>
        <div class="header">
            <div>
                <div class="brand">{ } Lógica Visual ABP · IT Ciudad Victoria</div>
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
            <p class="desc">Por haber completado satisfactoriamente las 5 lecciones interactivas, simuladores de iteración y retos de Aprendizaje Basado en Problemas (ABP) del módulo:</p>
            <div class="module-pill">${moduloCompletado}</div>
            <div class="sub">Validando competencias en Mapeo de Funciones, Límites y Continuidad, Razón de Cambio Instantánea, Reglas de Derivación y Optimización.</div>
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
            generateAndDownloadCertificatePdf();
        });
    }
    if (btnCertPane) {
        btnCertPane.addEventListener('click', generateAndDownloadCertificatePdf);
    }
});


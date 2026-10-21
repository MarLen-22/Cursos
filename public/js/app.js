/**
 * Lógica Visual ABP · Lógica de interfaz de usuario
 */

document.addEventListener('DOMContentLoaded', () => {
    // -----------------------------------------------------------------
    // 1. Auto-desvanecer alertas después de 6 segundos
    // -----------------------------------------------------------------
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }, 6000);
    });

    // -----------------------------------------------------------------
    // 2. Simulador Interactivo de Lógica (en vista de detalle de módulo)
    // -----------------------------------------------------------------
    const simStepBadge = document.getElementById('simStepBadge');
    const simOutputLog = document.getElementById('simOutputLog');
    const simNextBtn = document.getElementById('simNextBtn');
    const simResetBtn = document.getElementById('simResetBtn');

    if (simStepBadge && simOutputLog && simNextBtn && simResetBtn) {
        let currentStep = 0;
        const totalSteps = 4;

        const stepDescriptions = [
            { text: 'Estado inicial: inicializando variables de entrada (paso = 0).', status: 'en_progreso' },
            { text: 'Paso 1: evaluando hipótesis lógica (0 < 4 → continúa el ciclo).', status: 'en_progreso' },
            { text: 'Paso 2: ejecutando transformación algorítmica y acumulador.', status: 'en_progreso' },
            { text: 'Paso 3: aproximando condición de paro (3 < 4).', status: 'en_progreso' },
            { text: 'Paso 4: ¡Condición alcanzada! (paso >= 4 → estado: "completado"). Solución fundamentada con éxito.', status: 'completado' }
        ];

        function renderSimulator() {
            simStepBadge.textContent = `${currentStep} / ${totalSteps}`;
            const info = stepDescriptions[currentStep];

            const colorClass = info.status === 'completado' ? 'text-success' : 'text-cyan';
            simOutputLog.innerHTML = `
                <div class="mb-1"><span class="${colorClass}">&gt;</span> [Iteración ${currentStep}] ${info.text}</div>
                <div class="text-slate-400">&gt; Variable estado: <span class="${colorClass}">${JSON.stringify({ resolucion: info.status, iteraciones: currentStep })}</span></div>
            `;

            if (currentStep >= totalSteps) {
                simNextBtn.disabled = true;
                simNextBtn.textContent = 'Reto Completado ✓';
                simNextBtn.classList.replace('btn-cyan', 'btn-success');
            } else {
                simNextBtn.disabled = false;
                simNextBtn.textContent = 'Ejecutar siguiente paso ▷';
                simNextBtn.classList.replace('btn-success', 'btn-cyan');
            }
        }

        simNextBtn.addEventListener('click', () => {
            if (currentStep < totalSteps) {
                currentStep++;
                renderSimulator();
            }
        });

        simResetBtn.addEventListener('click', () => {
            currentStep = 0;
            renderSimulator();
        });
    }

    // -----------------------------------------------------------------
    // 3. Generación automática de Slugs en formulario administrativo
    // -----------------------------------------------------------------
    const tituloInput = document.getElementById('titulo');
    const slugInput = document.getElementById('slug');

    if (tituloInput && slugInput && slugInput.value === '') {
        tituloInput.addEventListener('input', () => {
            slugInput.value = tituloInput.value
                .toLowerCase()
                .trim()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // Quita acentos
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        });
    }
});

/**
 * Lógica Visual ABP · Interactividad del Alumno
 * Maneja Modo Oscuro, filtros de Mi Aprendizaje, búsqueda y atajos.
 */

document.addEventListener('DOMContentLoaded', () => {
    // -----------------------------------------------------------------
    // 1. Modo Oscuro / Claro interactivo
    // -----------------------------------------------------------------
    const themeToggles = document.querySelectorAll('[data-theme-toggle]');

    function setTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        document.documentElement.setAttribute('data-theme', theme);
        try {
            localStorage.setItem('lv_theme', theme);
        } catch (_) {}

        themeToggles.forEach(btn => {
            btn.setAttribute('aria-label', `Cambiar a modo ${theme === 'dark' ? 'claro' : 'oscuro'}`);
        });
    }

    themeToggles.forEach(btn => {
        btn.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-bs-theme') || 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            setTheme(next);
        });
    });

    // -----------------------------------------------------------------
    // 2. Atajo global de búsqueda ("/")
    // -----------------------------------------------------------------
    const searchInput = document.getElementById('headerSearch');
    if (searchInput) {
        window.addEventListener('keydown', (e) => {
            if (e.key === '/' && !['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) {
                e.preventDefault();
                searchInput.focus();
                searchInput.select();
            }
        });
    }

    // -----------------------------------------------------------------
    // 3. Paneles del Mega Menú "Explora"
    // -----------------------------------------------------------------
    const exploraItems = document.querySelectorAll('[data-explora-panel]');
    exploraItems.forEach(item => {
        const targetPanelId = item.getAttribute('data-explora-panel');
        const showPanel = () => {
            exploraItems.forEach(i => i.classList.remove('is-active'));
            item.classList.add('is-active');

            document.querySelectorAll('.explora-panel').forEach(panel => {
                panel.classList.remove('is-active');
            });
            const targetPanel = document.getElementById(targetPanelId);
            if (targetPanel) {
                targetPanel.classList.add('is-active');
            }
        };

        item.addEventListener('mouseenter', showPanel);
        item.addEventListener('focus', showPanel);
    });

    // -----------------------------------------------------------------
    // 4. Filtros interactivos en "Mi aprendizaje"
    // -----------------------------------------------------------------
    const filterTipo = document.getElementById('filterTipoContenido');
    const filterEstado = document.getElementById('filterEstado');
    const filterSearch = document.getElementById('filterBusqueda');
    const tableBody = document.getElementById('learningTableBody');

    if (tableBody && (filterTipo || filterEstado || filterSearch)) {
        function applyFilters() {
            const selectedTipo = filterTipo ? filterTipo.value.toLowerCase().trim() : '';
            const selectedEstado = filterEstado ? filterEstado.value.toLowerCase().trim() : '';
            const query = filterSearch ? filterSearch.value.toLowerCase().trim() : '';

            const rows = tableBody.querySelectorAll('tr[data-learning-row]');
            let visibleCount = 0;

            rows.forEach(row => {
                const tipo = (row.getAttribute('data-tipo') || '').toLowerCase();
                const estado = (row.getAttribute('data-estado') || '').toLowerCase();
                const text = row.textContent.toLowerCase();

                const matchTipo = !selectedTipo || tipo.includes(selectedTipo);
                const matchEstado = !selectedEstado || estado === selectedEstado;
                const matchQuery = !query || text.includes(query);

                if (matchTipo && matchEstado && matchQuery) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const noResultsRow = document.getElementById('learningNoResults');
            if (noResultsRow) {
                noResultsRow.style.display = visibleCount === 0 ? '' : 'none';
            }
        }

        if (filterTipo) filterTipo.addEventListener('change', applyFilters);
        if (filterEstado) filterEstado.addEventListener('change', applyFilters);
        if (filterSearch) filterSearch.addEventListener('input', applyFilters);
    }

    // -----------------------------------------------------------------
    // 5. Botón "Volver arriba"
    // -----------------------------------------------------------------
    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTop.classList.add('is-visible');
            } else {
                backToTop.classList.remove('is-visible');
            }
        });

        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // -----------------------------------------------------------------
    // 6. Conmutador de visibilidad de contraseñas
    // -----------------------------------------------------------------
    const togglePasswordBtns = document.querySelectorAll('[data-toggle-password]');
    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-toggle-password');
            const input = document.getElementById(targetId);
            if (!input) return;

            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            btn.setAttribute('aria-label', isPassword ? 'Ocultar contraseña' : 'Ver contraseña');
        });
    });

    // -----------------------------------------------------------------
    // 7. Navegación en "Mi cuenta" (Tabs laterales)
    // -----------------------------------------------------------------
    const accountNavButtons = document.querySelectorAll('[data-account-tab]');
    accountNavButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const tabName = btn.getAttribute('data-account-tab');
            accountNavButtons.forEach(b => b.classList.remove('is-active'));
            btn.classList.add('is-active');

            document.querySelectorAll('[data-account-pane]').forEach(pane => {
                pane.classList.add('d-none');
            });
            const activePane = document.getElementById(`pane-${tabName}`);
            if (activePane) {
                activePane.classList.remove('d-none');
            }
        });
    });

    // -----------------------------------------------------------------
    // 8. Sistema de Validaciones en Tiempo Real y Notificaciones
    // -----------------------------------------------------------------
    function mostrarAlerta(container, mensaje, tipo = 'info', autoCerrar = 5000) {
        if (!container) return;
        const icono = tipo === 'success' ? '✅' : (tipo === 'danger' ? '⚠️' : 'ℹ️');
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${tipo} alert-dismissible fade show d-flex align-items-center gap-2 shadow-xs mb-3`;
        alertDiv.setAttribute('role', 'alert');
        alertDiv.innerHTML = `
            <span class="fs-5">${icono}</span>
            <div class="flex-grow-1 small leading-relaxed">${mensaje}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        `;
        container.innerHTML = '';
        container.appendChild(alertDiv);

        if (autoCerrar > 0) {
            setTimeout(() => {
                if (alertDiv && alertDiv.parentNode) {
                    alertDiv.classList.remove('show');
                    setTimeout(() => alertDiv.remove(), 250);
                }
            }, autoCerrar);
        }
    }

    // Regla de caracteres permitidos para tipo String (solo letras y espacios con acentos y caracteres del español)
    const REGEX_SOLO_STRING = /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\.\'\-]+$/;
    const REGEX_SOLO_STRING_GLOBAL = /[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\.\'\-]/g;

    // A) Restricción activa en campos tipo "only-string" (Nombre, Ciudad, etc.)
    document.querySelectorAll('[data-type="only-string"]').forEach(input => {
        let advertenciaMostrada = false;

        input.addEventListener('input', function () {
            if (REGEX_SOLO_STRING_GLOBAL.test(this.value)) {
                this.value = this.value.replace(REGEX_SOLO_STRING_GLOBAL, '');
                if (!advertenciaMostrada) {
                    advertenciaMostrada = true;
                    const container = input.closest('form')?.querySelector('[id$="NotificationContainer"]') || null;
                    if (container) {
                        mostrarAlerta(container, '<strong>Campo de texto:</strong> No se permiten números ni símbolos raros, solo caracteres de tipo String (letras y espacios).', 'warning', 3500);
                    }
                    setTimeout(() => { advertenciaMostrada = false; }, 3000);
                }
            }
            validarCampo(this);
        });

        input.addEventListener('blur', function () {
            validarCampo(this);
        });
    });

    // B) Formateo y validación de Número de Control
    document.querySelectorAll('[data-type="control-number"]').forEach(input => {
        input.addEventListener('input', function () {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            validarCampo(this);
        });
        input.addEventListener('blur', function () {
            validarCampo(this);
        });
    });

    // C) Validación de Correo Institucional @tecvictoria.edu.mx
    document.querySelectorAll('[data-type="tecvictoria-email"]').forEach(input => {
        input.addEventListener('input', function () {
            validarCampo(this);
        });
        input.addEventListener('blur', function () {
            validarCampo(this);
        });
    });

    // Botón de autocompletado rápido del dominio @tecvictoria.edu.mx
    const btnAutofill = document.getElementById('btnAutofillTecDomain');
    if (btnAutofill) {
        btnAutofill.addEventListener('click', () => {
            const emailInput = document.getElementById('email');
            if (!emailInput) return;
            let val = emailInput.value.trim();
            if (val.includes('@')) {
                val = val.split('@')[0];
            }
            if (val === '') {
                val = '22380123';
            }
            emailInput.value = val + '@tecvictoria.edu.mx';
            validarCampo(emailInput);
            emailInput.focus();
        });
    }

    // D) Validación de Teléfonos
    document.querySelectorAll('[data-type="phone"]').forEach(input => {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9+\s\-()]/g, '');
            validarCampo(this);
        });
        input.addEventListener('blur', function () {
            validarCampo(this);
        });
    });

    // E) Validación de Código Postal
    document.querySelectorAll('[data-type="postal-code"]').forEach(input => {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
            validarCampo(this);
        });
        input.addEventListener('blur', function () {
            validarCampo(this);
        });
    });

    // F) Función genérica de validación por campo individual
    function validarCampo(input) {
        const val = input.value.trim();
        const type = input.getAttribute('data-type');
        const required = input.hasAttribute('required');
        let esValido = true;
        let mensaje = '';

        if (required && val === '') {
            esValido = false;
            mensaje = 'Este campo es obligatorio.';
        } else if (val !== '') {
            if (type === 'only-string') {
                if (!REGEX_SOLO_STRING.test(val) || val.length < 3) {
                    esValido = false;
                    mensaje = 'Solo se permiten letras y espacios (mínimo 3 caracteres).';
                }
            } else if (type === 'control-number') {
                if (!/^[A-Z0-9]{6,20}$/.test(val)) {
                    esValido = false;
                    mensaje = 'Debe tener entre 6 y 20 caracteres alfanuméricos.';
                }
            } else if (type === 'tecvictoria-email') {
                const lower = val.toLowerCase();
                const esDominio = lower.endsWith('@tecvictoria.edu.mx') || lower.startsWith('@tecvictoria.edu.mx');
                const esEmailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(lower);
                if (!esDominio || !esEmailValido) {
                    esValido = false;
                    mensaje = 'El correo debe ser institucional y terminar en @tecvictoria.edu.mx.';
                }
            } else if (type === 'email') {
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                    esValido = false;
                    mensaje = 'Ingresa un formato de correo válido.';
                }
            } else if (type === 'phone') {
                const digitos = val.replace(/[^0-9]/g, '');
                if (digitos.length < 10) {
                    esValido = false;
                    mensaje = 'El teléfono debe contener al menos 10 dígitos.';
                }
            } else if (type === 'postal-code') {
                if (!/^[0-9]{5}$/.test(val)) {
                    esValido = false;
                    mensaje = 'El código postal debe contener exactamente 5 números.';
                }
            } else if (type === 'semester') {
                const sem = parseInt(val, 10);
                if (isNaN(sem) || sem < 1 || sem > 14) {
                    esValido = false;
                    mensaje = 'Semestre inválido (debe estar entre 1 y 14).';
                }
            } else if (type === 'score') {
                const num = parseFloat(val);
                if (isNaN(num) || num < 0 || num > 100) {
                    esValido = false;
                    mensaje = 'El promedio debe estar entre 0.00 y 100.00.';
                }
            }
        }

        if (!esValido) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            const feedback = input.parentNode.querySelector('.invalid-feedback');
            if (feedback && mensaje) {
                feedback.textContent = mensaje;
            }
        } else if (val !== '') {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        } else {
            input.classList.remove('is-invalid');
            input.classList.remove('is-valid');
        }

        return esValido;
    }

    // G) Validación y Alerta al Enviar el Formulario de Actualizar Perfil (#formEditarPerfil)
    const formPerfil = document.getElementById('formEditarPerfil');
    if (formPerfil) {
        formPerfil.addEventListener('submit', function (e) {
            const notifContainer = document.getElementById('perfilNotificationContainer');
            const statusIndicator = document.getElementById('perfilFormValidationStatus');
            const inputs = formPerfil.querySelectorAll('input, select');
            let todosValidos = true;
            let primerInvalido = null;

            inputs.forEach(input => {
                const valido = validarCampo(input);
                if (!valido && todosValidos) {
                    todosValidos = false;
                    primerInvalido = input;
                }
            });

            if (!todosValidos) {
                e.preventDefault();
                mostrarAlerta(
                    notifContainer,
                    '<strong>Validación incompleta:</strong> Por favor verifica los datos en rojo. Asegúrate de que el nombre contenga solo letras y todos los campos requeridos estén completos.',
                    'danger',
                    6000
                );
                if (statusIndicator) {
                    statusIndicator.innerHTML = '<span class="text-danger fw-bold">⚠️ Hay campos con errores pendientes.</span>';
                }
                if (primerInvalido) {
                    primerInvalido.focus();
                    primerInvalido.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                mostrarAlerta(
                    notifContainer,
                    '<strong>¡Datos correctos!</strong> Todos los campos han sido validados exitosamente. Guardando en la base de datos...',
                    'success',
                    0
                );
                if (statusIndicator) {
                    statusIndicator.innerHTML = '<span class="text-success fw-bold">✅ Datos validados. Guardando...</span>';
                }
            }
        });
    }

    // H) Validación y Alerta al Enviar el Formulario de Registro (#formRegistro)
    const formRegistro = document.getElementById('formRegistro');
    if (formRegistro) {
        formRegistro.addEventListener('submit', function (e) {
            const notifContainer = document.getElementById('registroNotificationContainer');
            const inputs = formRegistro.querySelectorAll('input');
            let todosValidos = true;
            let primerInvalido = null;

            inputs.forEach(input => {
                const valido = validarCampo(input);
                if (!valido && todosValidos) {
                    todosValidos = false;
                    primerInvalido = input;
                }
            });

            // Validar contraseñas coincidentes
            const pass = document.getElementById('password');
            const passConf = document.getElementById('password_confirmation');
            if (pass && passConf && pass.value !== passConf.value) {
                todosValidos = false;
                passConf.classList.add('is-invalid');
                if (!primerInvalido) primerInvalido = passConf;
            }

            if (!todosValidos) {
                e.preventDefault();
                mostrarAlerta(
                    notifContainer,
                    '<strong>Error en el registro:</strong> Revisa que el nombre solo tenga caracteres tipo String, que el correo termine obligatoriamente en <strong>@tecvictoria.edu.mx</strong> y que las contraseñas coincidan.',
                    'danger',
                    7000
                );
                if (primerInvalido) {
                    primerInvalido.focus();
                }
            } else {
                mostrarAlerta(
                    notifContainer,
                    '<strong>¡Excelente!</strong> Datos verificados con éxito. Creando tu cuenta institucional...',
                    'success',
                    0
                );
            }
        });
    }
});


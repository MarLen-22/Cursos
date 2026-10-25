/**
 * Lógica Visual ABP · Inicialización inmediata de tema
 * Ejecuta antes de que se dibuje el DOM para evitar parpadeos (FOUC).
 */
(() => {
    try {
        const stored = localStorage.getItem('lv_theme');
        const theme = stored === 'dark' || stored === 'light'
            ? stored
            : (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

        document.documentElement.setAttribute('data-bs-theme', theme);
        document.documentElement.setAttribute('data-theme', theme);
    } catch (_) {
        document.documentElement.setAttribute('data-bs-theme', 'light');
    }
})();

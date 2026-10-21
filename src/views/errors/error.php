<section class="py-5 bg-navy text-white min-vh-75 d-flex align-items-center position-relative">
    <div class="bg-grid position-absolute top-0 start-0 w-100 h-100 pointer-events-none"></div>
    <div class="container-fluid max-w-lg mx-auto px-4 text-center position-relative z-1">
        <div class="display-1 fw-bold text-cyan mb-2 font-monospace"><?= (int) $status ?></div>
        <h1 class="h3 fw-bold text-white mb-3"><?= e($title) ?></h1>
        <p class="text-slate-400 mb-4 leading-relaxed"><?= e($message) ?></p>

        <?php if (!empty($detail)): ?>
            <div class="text-start bg-slate-900 border border-slate-700 rounded-3 p-3 mb-4 font-monospace text-xs text-danger overflow-auto max-h-300">
                <div class="text-warning mb-2 fw-bold">// Modo Debug Activado (APP_DEBUG=true):</div>
                <pre class="m-0 text-slate-300 text-xs"><code><?= e($detail) ?></code></pre>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-center gap-3">
            <a href="<?= url('/') ?>" class="btn btn-cyan fw-bold px-4 py-2 shadow-cyan">
                Volver al Inicio
            </a>
            <a href="<?= url('/modulos') ?>" class="btn btn-outline-light px-4 py-2">
                Ver Módulos
            </a>
        </div>
    </div>
</section>

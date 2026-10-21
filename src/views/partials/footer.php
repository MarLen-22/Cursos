<footer class="app-footer bg-navy text-white pt-5 pb-4 px-4 px-md-5 border-top border-navy-light mt-auto">
    <div class="container-fluid max-w-7xl mx-auto">
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="brand-badge">{ }</span>
                    <span class="fw-semibold fs-5">Lógica Visual ABP</span>
                </div>
                <p class="text-slate-400 small leading-relaxed">
                    Instituto Tecnológico de Ciudad Victoria – Ingeniería en Sistemas Computacionales.
                    Plataforma para el razonamiento lógico y la resolución de problemas mediante ABP.
                </p>
            </div>
            <div class="col-12 col-md-4">
                <h6 class="fw-bold text-uppercase tracking-wider text-cyan fs-7 mb-3">Contacto Institucional</h6>
                <p class="small text-slate-400 mb-1">hola@logicavisual.edu.mx</p>
                <p class="small text-slate-400 mb-1">(834) 000 0000</p>
                <p class="small text-slate-400">Ciudad Victoria, Tamaulipas</p>
            </div>
            <div class="col-12 col-md-4">
                <h6 class="fw-bold text-uppercase tracking-wider text-cyan fs-7 mb-3">Navegación Rápida</h6>
                <div class="d-flex flex-column gap-2 small">
                    <a href="<?= url('/modulos') ?>" class="text-slate-400 text-decoration-none hover-cyan">Módulos Interactivos</a>
                    <a href="<?= url('/metodologia') ?>" class="text-slate-400 text-decoration-none hover-cyan">Metodología ABP</a>
                    <a href="<?= url('/evaluacion') ?>" class="text-slate-400 text-decoration-none hover-cyan">Evaluación Formativa</a>
                </div>
                <div class="d-flex gap-3 mt-3 text-slate-400">
                    <span class="hover-cyan cursor-pointer">in</span>
                    <span class="hover-cyan cursor-pointer">▶</span>
                    <span class="hover-cyan cursor-pointer">◎</span>
                </div>
            </div>
        </div>
        <div class="pt-4 border-top border-navy-light d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 small text-slate-500">
            <div>© <?= date('Y') ?> Lógica Visual ABP · Aprender es investigar, experimentar y explicar.</div>
            <div class="d-flex gap-3">
                <span class="badge bg-navy-light text-slate-400">Ambiente: <?= e(App\Core\Env::get('APP_ENV', 'produccion')) ?></span>
                <span class="badge bg-navy-light text-slate-400">MySQL / Azure Ready</span>
            </div>
        </div>
    </div>
</footer>

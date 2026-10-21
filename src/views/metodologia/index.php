<section class="py-5 bg-white border-bottom border-slate-100">
    <div class="container-fluid max-w-7xl mx-auto px-4 text-center">
        <p class="text-cyan text-uppercase fw-bold small tracking-wider mb-2">Metodología ABP</p>
        <h1 class="h2 fw-bold mb-3">Una metodología para pensar, probar y explicar</h1>
        <p class="text-slate-500 mb-5 max-w-2xl mx-auto">
            El Aprendizaje Basado en Problemas (ABP) pone los retos al centro y convierte la curiosidad en una estrategia activa de aprendizaje.
        </p>

        <div class="row g-4 text-start mb-5">
            <div class="col-12 col-md-4">
                <div class="p-4 border border-slate-100 rounded-4 shadow-xs h-100 hover-card">
                    <div class="icon-circle bg-cyan-subtle text-cyan fs-4 mb-3">👁️</div>
                    <h2 class="h5 fw-bold mb-2">Visualiza para comprender</h2>
                    <p class="small text-slate-500 mb-0">Los simuladores reducen la carga cognitiva al convertir conceptos abstractos en experiencias manipulables y claras.</p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="p-4 border border-slate-100 rounded-4 shadow-xs h-100 hover-card">
                    <div class="icon-circle bg-cyan-subtle text-cyan fs-4 mb-3">🧩</div>
                    <h2 class="h5 fw-bold mb-2">Resuelve problemas reales</h2>
                    <p class="small text-slate-500 mb-0">El ABP usa retos contextualizados, colaboración, formulación de hipótesis y razonamiento para construir soluciones duraderas.</p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="p-4 border border-slate-100 rounded-4 shadow-xs h-100 hover-card">
                    <div class="icon-circle bg-cyan-subtle text-cyan fs-4 mb-3">📈</div>
                    <h2 class="h5 fw-bold mb-2">Impacto medible</h2>
                    <p class="small text-slate-500 mb-0">La práctica guiada, la retroalimentación y la visualización ayudan a disminuir la reprobación y fortalecer la confianza académica.</p>
                </div>
            </div>
        </div>

        <!-- Fases del Proceso ABP -->
        <div class="bg-navy rounded-4 p-4 p-md-5 text-white position-relative">
            <h2 class="h4 fw-bold text-center text-white mb-5">Tu proceso de aprendizaje paso a paso</h2>

            <div class="row g-4 justify-content-center">
                <?php foreach ($etapas as $etapa): ?>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="d-flex flex-column align-items-center text-center p-3">
                            <div class="abp-step-badge bg-cyan text-navy fw-bold mb-3 fs-5 shadow-cyan">
                                <?= e($etapa->orden) ?>
                            </div>
                            <h3 class="h6 fw-bold text-white mb-2"><?= e($etapa->nombre) ?></h3>
                            <p class="text-slate-400 small mb-0"><?= e($etapa->descripcion) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

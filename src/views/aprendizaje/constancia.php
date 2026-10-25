<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="<?= url('/vendor/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/styles.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/alumno.css') ?>">
    <style>
        body { background-color: #f1f5f9; font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body>
    <div class="no-print bg-navy py-3 px-4 text-white d-flex justify-content-between align-items-center shadow-sm">
        <div class="d-flex align-items-center gap-2">
            <a href="<?= url('/mi-aprendizaje') ?>" class="btn btn-sm btn-outline-light">← Volver a Mi aprendizaje</a>
            <span class="small text-slate-300">Folio: <strong><?= e($folio) ?></strong></span>
        </div>
        <button type="button" class="btn btn-cyan btn-sm fw-bold px-4" onclick="window.print()">
            Imprimir / Guardar en PDF 🖨️
        </button>
    </div>

    <div class="certificate-container">
        <div class="certificate-watermark">LÓGICA VISUAL ABP</div>

        <div class="d-flex justify-content-between align-items-center border-bottom pb-4 mb-4">
            <div class="d-flex align-items-center gap-2">
                <span class="brand-badge fs-4">{ }</span>
                <div>
                    <h1 class="h5 fw-bold m-0 tracking-tight text-navy">Lógica Visual ABP</h1>
                    <div class="text-muted small">Instituto Tecnológico de Ciudad Victoria</div>
                </div>
            </div>
            <div class="text-end">
                <span class="badge bg-success text-white px-3 py-2 fw-semibold">CERTIFICADO OFICIAL</span>
                <div class="text-muted small mt-1">Modalidad Aprendizaje Basado en Problemas</div>
            </div>
        </div>

        <div class="text-center py-4">
            <p class="text-uppercase tracking-wider text-muted small mb-2">Se otorga la presente constancia a</p>
            <h2 class="display-6 fw-bold text-navy mb-4"><?= e($usuario->nombre) ?></h2>
            <p class="lead text-slate-700 max-w-2xl mx-auto mb-4">
                Por haber completado satisfactoriamente los retos, simulaciones algorítmicas y reflexiones críticas del módulo académico:
            </p>
            <div class="p-3 bg-light rounded-3 d-inline-block px-5 border mb-4">
                <h3 class="h4 fw-bold text-navy m-0"><?= e($inscripcion->titulo) ?></h3>
                <span class="badge bg-cyan text-navy mt-2"><?= e($inscripcion->nivel->etiqueta()) ?></span>
            </div>
            <p class="text-muted small max-w-lg mx-auto">
                Validando competencias de formulación de hipótesis, experimentación en simuladores lógicos y argumentación fundamentada.
            </p>
        </div>

        <div class="row pt-5 mt-4 border-top text-center text-md-start align-items-end g-4">
            <div class="col-12 col-md-4">
                <div class="border-top pt-2 mx-auto" style="max-width: 200px;">
                    <div class="fw-bold small text-navy">Comité Académico ABP</div>
                    <div class="text-muted fs-7">Dirección de Docencia e Investigación</div>
                </div>
            </div>
            <div class="col-12 col-md-4 text-center">
                <div class="border-top pt-2 mx-auto" style="max-width: 200px;">
                    <div class="fw-bold small text-navy">Plataforma Lógica Visual</div>
                    <div class="text-muted fs-7">Verificación Criptográfica Hash</div>
                </div>
            </div>
            <div class="col-12 col-md-4 text-md-end">
                <div class="text-muted fs-7">Fecha de emisión: <?= fecha($inscripcion->finalizadoEn, 'd/m/Y') ?></div>
                <div class="text-muted fs-7 font-monospace">Folio: <?= e($folio) ?></div>
            </div>
        </div>
    </div>
</body>
</html>

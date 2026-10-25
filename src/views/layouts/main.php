<!DOCTYPE html>
<html lang="es" class="h-100" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Lógica Visual ABP') ?></title>
    <meta name="description" content="<?= e($description ?? 'Plataforma del Tec de Ciudad Victoria para desarrollar el razonamiento lógico mediante simuladores y Aprendizaje Basado en Problemas.') ?>">
    <meta name="color-scheme" content="light dark">
    <!-- Applies the saved theme before first paint (external file: the CSP forbids inline scripts) -->
    <script src="<?= asset('js/theme-init.js') ?>"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('/vendor/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/styles.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/alumno.css') ?>">
</head>
<body class="d-flex flex-column h-100 bg-slate-50 text-slate-900">
    <a href="#contenido" class="skip-link">Saltar al contenido principal</a>

    <?= partial('partials/header', ['activeNav' => $activeNav ?? '', 'authAware' => $authAware ?? true]) ?>

    <main id="contenido" class="flex-shrink-0" tabindex="-1">
        <div class="container-fluid max-w-7xl mx-auto px-3 px-md-4 pt-3">
            <?= partial('partials/alerts') ?>
        </div>
        <?= $content ?>
    </main>

    <?= partial('partials/footer') ?>

    <button type="button" class="back-to-top" id="backToTop" aria-label="Volver arriba">
        <?= icon('chevron-down', 'rotate-180') ?>
    </button>

    <script src="<?= url('/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
    <script src="<?= asset('js/alumno.js') ?>"></script>
</body>
</html>

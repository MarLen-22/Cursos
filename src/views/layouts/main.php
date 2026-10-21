<!DOCTYPE html>
<html lang="es" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Lógica Visual ABP') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('/vendor/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/styles.css') ?>">
</head>
<body class="d-flex flex-column h-100 bg-slate-50 text-slate-900">
    <?= partial('partials/header', ['activeNav' => $activeNav ?? '']) ?>

    <main class="flex-shrink-0">
        <div class="container-fluid max-w-7xl mx-auto px-3 px-md-4 pt-3">
            <?= partial('partials/alerts') ?>
        </div>
        <?= $content ?>
    </main>

    <?= partial('partials/footer') ?>

    <script src="<?= url('/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>

<?php

use App\Core\Session;

$tipos = [
    'success' => 'alert-success',
    'error'   => 'alert-danger',
    'warning' => 'alert-warning',
    'info'    => 'alert-info',
];

foreach ($tipos as $clave => $clase):
    $mensaje = Session::getFlash($clave);
    if ($mensaje):
?>
    <div class="alert <?= $clase ?> alert-dismissible fade show shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center">
            <span class="me-2">
                <?= match($clave) {
                    'success' => '✓',
                    'error'   => '⚠',
                    'warning' => '!',
                    default   => 'ℹ'
                } ?>
            </span>
            <div><?= e($mensaje) ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
<?php
    endif;
endforeach;

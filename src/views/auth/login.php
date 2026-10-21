<section class="py-5 bg-slate-50 min-vh-75 d-flex align-items-center">
    <div class="container-fluid max-w-md mx-auto px-4">
        <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 bg-white">
            <div class="text-center mb-4">
                <span class="brand-badge fs-4 mb-3 d-inline-block">{ }</span>
                <h1 class="h3 fw-bold text-navy mb-1">Iniciar Sesión</h1>
                <p class="text-slate-500 small">Ingresa tus credenciales para acceder a la plataforma</p>
            </div>

            <form action="<?= url('/login') ?>" method="POST" novalidate>
                <?= csrf_field() ?>
                <?php if (!empty($next)): ?>
                    <input type="hidden" name="next" value="<?= e($next) ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold text-slate-700">Correo Electrónico</label>
                    <input type="email" class="form-control<?= invalid_class('email') ?>" id="email" name="email"
                           value="<?= e(old('email')) ?>" required autocomplete="email" autofocus
                           placeholder="nombre@ejemplo.com">
                    <?php if ($error = field_error('email')): ?>
                        <div class="invalid-feedback"><?= e($error) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label for="password" class="form-label small fw-semibold text-slate-700">Contraseña</label>
                    </div>
                    <input type="password" class="form-control<?= invalid_class('password') ?>" id="password" name="password"
                           required autocomplete="current-password" placeholder="••••••••">
                    <?php if ($error = field_error('password')): ?>
                        <div class="invalid-feedback"><?= e($error) ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-cyan w-100 fw-bold py-2 mt-2 shadow-cyan">
                    Ingresar a la Plataforma
                </button>
            </form>

            <div class="mt-4 pt-3 border-top text-center small text-slate-500">
                ¿Aún no tienes una cuenta? <a href="<?= url('/registro') ?>" class="text-cyan fw-bold text-decoration-none">Regístrate aquí</a>
            </div>

            <div class="mt-3 p-3 bg-slate-100 rounded-3 small text-slate-600">
                <div class="fw-semibold mb-1">ℹ Acceso Administrativo</div>
                <div>Solo los usuarios con rol autorizado en la base de datos (tabla <code>administrador</code>) tienen acceso al panel de administración.</div>
            </div>
        </div>
    </div>
</section>

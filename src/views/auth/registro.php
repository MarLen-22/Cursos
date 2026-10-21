<section class="py-5 bg-slate-50 min-vh-75 d-flex align-items-center">
    <div class="container-fluid max-w-md mx-auto px-4">
        <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 bg-white">
            <div class="text-center mb-4">
                <span class="brand-badge fs-4 mb-3 d-inline-block">{ }</span>
                <h1 class="h3 fw-bold text-navy mb-1">Crear Cuenta</h1>
                <p class="text-slate-500 small">Comienza a resolver problemas con razonamiento lógico</p>
            </div>

            <form action="<?= url('/registro') ?>" method="POST" novalidate>
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="nombre" class="form-label small fw-semibold text-slate-700">Nombre Completo</label>
                    <input type="text" class="form-control<?= invalid_class('nombre') ?>" id="nombre" name="nombre"
                           value="<?= e(old('nombre')) ?>" required autocomplete="name" autofocus
                           placeholder="Juan Pérez">
                    <?php if ($error = field_error('nombre')): ?>
                        <div class="invalid-feedback"><?= e($error) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold text-slate-700">Correo Electrónico</label>
                    <input type="email" class="form-control<?= invalid_class('email') ?>" id="email" name="email"
                           value="<?= e(old('email')) ?>" required autocomplete="email"
                           placeholder="estudiante@tecvictoria.edu.mx">
                    <?php if ($error = field_error('email')): ?>
                        <div class="invalid-feedback"><?= e($error) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label small fw-semibold text-slate-700">Contraseña</label>
                    <input type="password" class="form-control<?= invalid_class('password') ?>" id="password" name="password"
                           required autocomplete="new-password" placeholder="Mínimo 8 caracteres (letras y números)">
                    <?php if ($error = field_error('password')): ?>
                        <div class="invalid-feedback"><?= e($error) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label small fw-semibold text-slate-700">Confirmar Contraseña</label>
                    <input type="password" class="form-control<?= invalid_class('password_confirmation') ?>" id="password_confirmation"
                           name="password_confirmation" required autocomplete="new-password" placeholder="Repite tu contraseña">
                    <?php if ($error = field_error('password_confirmation')): ?>
                        <div class="invalid-feedback"><?= e($error) ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-cyan w-100 fw-bold py-2 mt-2 shadow-cyan">
                    Registrarse
                </button>
            </form>

            <div class="mt-4 pt-3 border-top text-center small text-slate-500">
                ¿Ya tienes una cuenta? <a href="<?= url('/login') ?>" class="text-cyan fw-bold text-decoration-none">Inicia sesión</a>
            </div>
        </div>
    </div>
</section>

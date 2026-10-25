<section class="py-5 bg-slate-50 min-vh-75 d-flex align-items-center">
    <div class="container-fluid max-w-md mx-auto px-4">
        <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 bg-white">
            <div class="text-center mb-4">
                <span class="brand-badge fs-4 mb-3 d-inline-block">{ }</span>
                <h1 class="h3 fw-bold text-navy mb-1">Crear Cuenta</h1>
                <p class="text-slate-500 small">Comienza a resolver problemas con razonamiento lógico</p>
            </div>

            <!-- Notificación dinámica en tiempo real del formulario -->
            <div id="registroNotificationContainer" class="mb-3"></div>

            <form action="<?= url('/registro') ?>" method="POST" id="formRegistro" novalidate>
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="nombre" class="form-label small fw-semibold text-slate-700">Nombre Completo *</label>
                    <input type="text" class="form-control<?= invalid_class('nombre') ?>" id="nombre" name="nombre"
                           value="<?= e(old('nombre')) ?>" required autocomplete="name" autofocus
                           placeholder="Johan Gael Castillo"
                           data-type="only-string"
                           data-label="Nombre Completo">
                    <div class="form-text fs-7 text-muted">Solo caracteres de tipo String (letras y espacios). No se admiten números ni símbolos raros.</div>
                    <?php if ($error = field_error('nombre')): ?>
                        <div class="invalid-feedback d-block"><?= e($error) ?></div>
                    <?php else: ?>
                        <div class="invalid-feedback">El nombre es obligatorio y solo puede contener letras y espacios.</div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="numero_control" class="form-label small fw-semibold text-slate-700">No. De Control / Matrícula *</label>
                    <input type="text" class="form-control text-uppercase font-monospace<?= invalid_class('numero_control') ?>" id="numero_control" name="numero_control"
                           value="<?= e(old('numero_control')) ?>" required
                           placeholder="22380123" maxlength="20"
                           data-type="control-number"
                           data-label="Número de Control">
                    <div class="form-text fs-7 text-muted">Tu identificador oficial de estudiante (6 a 20 caracteres alfanuméricos).</div>
                    <?php if ($error = field_error('numero_control')): ?>
                        <div class="invalid-feedback d-block"><?= e($error) ?></div>
                    <?php else: ?>
                        <div class="invalid-feedback">Ingresa tu número de control oficial (6 a 20 caracteres).</div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="email" class="form-label small fw-semibold text-slate-700 mb-0">Correo Electrónico Institucional *</label>
                        <button type="button" class="btn btn-link p-0 text-cyan text-decoration-none small fs-7" id="btnAutofillTecDomain">
                            + @tecvictoria.edu.mx
                        </button>
                    </div>
                    <input type="email" class="form-control<?= invalid_class('email') ?>" id="email" name="email"
                           value="<?= e(old('email')) ?>" required autocomplete="email"
                           placeholder="ej: 22380123@tecvictoria.edu.mx"
                           data-type="tecvictoria-email"
                           data-label="Correo Institucional">
                    <div class="form-text fs-7 text-muted">
                        Obligatorio: debe pertenecer al dominio institucional <strong>@tecvictoria.edu.mx</strong>.
                    </div>
                    <?php if ($error = field_error('email')): ?>
                        <div class="invalid-feedback d-block"><?= e($error) ?></div>
                    <?php else: ?>
                        <div class="invalid-feedback">El correo debe ser institucional y terminar en @tecvictoria.edu.mx.</div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label small fw-semibold text-slate-700">Contraseña *</label>
                    <div class="input-group">
                        <input type="password" class="form-control<?= invalid_class('password') ?>" id="password" name="password"
                               required autocomplete="new-password" placeholder="Mínimo 8 caracteres (letras y números)">
                        <button class="btn btn-outline-secondary" type="button" data-toggle-password="password" aria-label="Ver u ocultar contraseña">
                            <?= icon('eye', 'icon-sm') ?>
                        </button>
                    </div>
                    <?php if ($error = field_error('password')): ?>
                        <div class="invalid-feedback d-block"><?= e($error) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label small fw-semibold text-slate-700">Confirmar Contraseña *</label>
                    <div class="input-group">
                        <input type="password" class="form-control<?= invalid_class('password_confirmation') ?>" id="password_confirmation"
                               name="password_confirmation" required autocomplete="new-password" placeholder="Repite tu contraseña">
                        <button class="btn btn-outline-secondary" type="button" data-toggle-password="password_confirmation" aria-label="Ver u ocultar confirmación">
                            <?= icon('eye', 'icon-sm') ?>
                        </button>
                    </div>
                    <?php if ($error = field_error('password_confirmation')): ?>
                        <div class="invalid-feedback d-block"><?= e($error) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input<?= invalid_class('terminos') ?>" type="checkbox" id="terminos" name="terminos" value="1" required>
                        <label class="form-check-label fs-7 text-slate-600" for="terminos">
                            Acepto las <a href="<?= url('/ayuda#terminos') ?>" target="_blank" class="text-cyan">condiciones generales</a> y el <a href="<?= url('/ayuda#privacidad') ?>" target="_blank" class="text-cyan">aviso de privacidad</a>.
                        </label>
                    </div>
                    <?php if ($error = field_error('terminos')): ?>
                        <div class="invalid-feedback d-block"><?= e($error) ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-cyan w-100 fw-bold py-2 shadow-cyan">
                    Registrarme y comenzar
                </button>
            </form>

            <div class="mt-4 pt-3 border-top text-center small text-slate-500">
                ¿Ya tienes una cuenta? <a href="<?= url('/login') ?>" class="text-cyan fw-bold text-decoration-none">Inicia sesión</a>
            </div>
        </div>
    </div>
</section>

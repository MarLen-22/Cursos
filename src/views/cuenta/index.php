<?php
use App\Models\Catalogo;
use App\Models\Genero;
use App\Models\Modalidad;
use App\Models\NivelEstudios;

// Separar nombre y apellidos para la presentación idéntica a la imagen
$partesNombre = preg_split('/\s+/u', trim($usuario->nombre)) ?: [];
$primerNombre = $partesNombre[0] ?? $usuario->nombre;
if (count($partesNombre) >= 3) {
    // Si tiene 2 nombres (ej: Johan Gael)
    $primerNombre = $partesNombre[0] . ' ' . $partesNombre[1];
    $apellidos = implode(' ', array_slice($partesNombre, 2));
} else {
    $apellidos = implode(' ', array_slice($partesNombre, 1));
}
if ($apellidos === '') {
    $apellidos = '—';
}

$fechaNac = $perfil->get('fecha_nacimiento');
$fechaNacFormateada = '—';
if ($fechaNac) {
    $dt = DateTimeImmutable::createFromFormat('Y-m-d', $fechaNac);
    if ($dt) {
        $fechaNacFormateada = $dt->format('d/m/Y');
    }
}
?>

<section class="py-5 bg-slate-50 min-vh-75">
    <div class="container-fluid max-w-7xl mx-auto px-3 px-md-4">
        <div class="row g-4">
            <!-- Columna Izquierda: Tarjeta de perfil y navegación lateral (Réplica Imagen 4) -->
            <div class="col-12 col-lg-4 col-xl-3">
                <div class="account-sidebar-card shadow-xs">
                    <!-- Avatar Circular Rojo "JC" -->
                    <div class="avatar-profile-red">
                        <?= e($usuario->iniciales()) ?>
                    </div>

                    <h1 class="account-user-name text-truncate" title="<?= e($usuario->nombre) ?>">
                        <?= e($usuario->nombre) ?>
                    </h1>

                    <div class="account-meta-item">
                        <span class="text-secondary"><?= icon('pin', 'icon-sm') ?></span>
                        <span><?= e($perfil->get('pais_residencia') ?: 'México') ?></span>
                    </div>

                    <div class="account-meta-item">
                        <span class="text-secondary"><?= icon('phone', 'icon-sm') ?></span>
                        <span><?= e($perfil->get('telefono') ?: '+52 834') ?></span>
                    </div>

                    <nav class="account-nav-list" aria-label="Menú de cuenta">
                        <button type="button" class="account-nav-btn is-active" data-account-tab="perfil">
                            <span class="text-danger"><?= icon('user-circle', 'icon-sm') ?></span>
                            <span>Datos del perfil</span>
                        </button>
                        <button type="button" class="account-nav-btn" data-account-tab="preferencias">
                            <span class="text-secondary"><?= icon('settings', 'icon-sm') ?></span>
                            <span>Preferencias</span>
                        </button>
                        <button type="button" class="account-nav-btn" data-account-tab="configuracion">
                            <span class="text-secondary"><?= icon('shield', 'icon-sm') ?></span>
                            <span>Configuración de cuenta</span>
                        </button>

                        <form action="<?= url('/logout') ?>" method="POST" class="m-0">
                            <?= csrf_field() ?>
                            <button type="submit" class="account-nav-btn account-nav-btn-logout">
                                <span><?= icon('logout', 'icon-sm') ?></span>
                                <span>Cerrar sesión</span>
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Columna Derecha: Tarjeta Principal con los Datos del Alumno -->
            <div class="col-12 col-lg-8 col-xl-9">
                <!-- Panel 1: Datos del perfil (Activo por defecto) -->
                <div class="account-main-card shadow-xs" id="pane-perfil" data-account-pane>
                    <div class="account-section-header">
                        <h2 class="account-section-title">Datos personales</h2>
                        <button type="button" class="btn-edit-pencil" data-bs-toggle="modal" data-bs-target="#modalEditarPerfil" aria-label="Editar datos del perfil">
                            <?= icon('pencil') ?>
                        </button>
                    </div>

                    <!-- 1. Datos Personales -->
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-6">
                            <div class="data-item">
                                <div class="data-label">Nombre</div>
                                <div class="data-value"><?= e($primerNombre) ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="data-item">
                                <div class="data-label">Apellidos</div>
                                <div class="data-value"><?= e($apellidos) ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="data-item">
                                <div class="data-label">Fecha de nacimiento</div>
                                <div class="data-value"><?= e($fechaNacFormateada) ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="data-item">
                                <div class="data-label">Género</div>
                                <div class="data-value"><?= e($perfil->genero()?->etiqueta() ?: 'Hombre') ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="data-item">
                                <div class="data-label">Nacionalidad</div>
                                <div class="data-value"><?= e($perfil->get('nacionalidad') ?: 'México') ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="data-item">
                                <div class="data-label">País de residencia</div>
                                <div class="data-value"><?= e($perfil->get('pais_residencia') ?: 'México') ?></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="data-item">
                                <div class="data-label">No. De Control / Documento</div>
                                <div class="data-value font-monospace"><?= e($perfil->get('numero_control') ?: '22380123') ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Información de Contacto -->
                    <h3 class="account-subheading">Información de contacto</h3>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-6">
                            <div class="data-item">
                                <div class="data-label">Correo institucional</div>
                                <div class="data-value"><?= e($usuario->email) ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="data-item">
                                <div class="data-label">Correo electrónico alterno</div>
                                <div class="data-value"><?= e($perfil->get('email_alterno') ?: '22380123@itcv.edu.mx') ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="data-item">
                                <div class="data-label">Teléfono de contacto</div>
                                <div class="data-value"><?= e($perfil->get('telefono') ?: '+52 834 156 1234') ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="data-item">
                                <div class="data-label">Ciudad / Estado</div>
                                <div class="data-value"><?= e(($perfil->get('ciudad') ?: 'Ciudad Victoria') . ', ' . ($perfil->get('estado_residencia') ?: 'Tamaulipas')) ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="data-item">
                                <div class="data-label">Código Postal</div>
                                <div class="data-value"><?= e($perfil->get('codigo_postal') ?: '87000') ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="data-item">
                                <div class="data-label">Contacto de emergencia</div>
                                <div class="data-value">
                                    <?= e($perfil->get('contacto_emergencia_nombre') ?: 'Ejemplo: María González') ?>
                                    <span class="text-muted small">(<?= e($perfil->get('contacto_emergencia_telefono') ?: '+52 834 111 2233') ?>)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Información Académica -->
                    <h3 class="account-subheading">Información académica</h3>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-6">
                            <div class="data-item">
                                <div class="data-label">Institución educativa</div>
                                <div class="data-value"><?= e($perfil->get('institucion') ?: 'Instituto Tecnológico de Ciudad Victoria') ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="data-item">
                                <div class="data-label">Carrera o programa</div>
                                <div class="data-value"><?= e($perfil->get('carrera') ?: 'Ingeniería en Sistemas Computacionales') ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="data-item">
                                <div class="data-label">Semestre actual</div>
                                <div class="data-value"><?= e($perfil->get('semestre') ?: '6') ?>.° Semestre</div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="data-item">
                                <div class="data-label">Modalidad</div>
                                <div class="data-value"><?= e($perfil->modalidad()?->etiqueta() ?: 'Escolarizada') ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Información Adicional como Estudiante -->
                    <h3 class="account-subheading">Información adicional como estudiante</h3>
                    <div class="row g-3">
                        <div class="col-12 col-sm-4">
                            <div class="data-item">
                                <div class="data-label">Nivel de estudios</div>
                                <div class="data-value"><?= e($perfil->nivelEstudios()?->etiqueta() ?: 'Ingeniería') ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-5">
                            <div class="data-item">
                                <div class="data-label">Área de estudios o especialidad</div>
                                <div class="data-value"><?= e($perfil->get('area_estudios') ?: 'Tecnologías de la Información y Software') ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-3">
                            <div class="data-item">
                                <div class="data-label">Nota media (Promedio)</div>
                                <div class="data-value text-success font-monospace fs-5">
                                    <?= e($perfil->get('nota_media') ?: '94.50') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel 2: Preferencias -->
                <div class="account-main-card shadow-xs d-none" id="pane-preferencias" data-account-pane>
                    <div class="account-section-header">
                        <h2 class="account-section-title">Preferencias de la plataforma</h2>
                    </div>
                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <div class="p-3 border rounded-3">
                                <h3 class="h6 fw-bold mb-2">Tema de la interfaz</h3>
                                <p class="text-muted small mb-3">Elige entre modo claro o modo oscuro para mayor confort visual al estudiar.</p>
                                <button type="button" class="btn btn-outline-cyan btn-sm fw-bold" data-theme-toggle>
                                    Cambiar tema actual 🌓
                                </button>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="p-3 border rounded-3">
                                <h3 class="h6 fw-bold mb-2">Notificaciones por correo</h3>
                                <p class="text-muted small mb-3">Avisos de avance en módulos y constancias completadas.</p>
                                <div class="form-check form-switch">
                                    <input class="form-check-switch form-check-input" type="checkbox" id="checkNotif" checked>
                                    <label class="form-check-label small" for="checkNotif">Recibir alertas de constancias</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel 3: Configuración de cuenta -->
                <div class="account-main-card shadow-xs d-none" id="pane-configuracion" data-account-pane>
                    <div class="account-section-header">
                        <h2 class="account-section-title">Configuración de cuenta y seguridad</h2>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <p class="text-muted small">Tu cuenta está protegida con cifrado bcrypt y expiración segura de sesiones.</p>
                            <div class="p-3 bg-light rounded-3 mb-3">
                                <div class="small fw-bold">Correo registrado</div>
                                <div class="text-muted"><?= e($usuario->email) ?></div>
                            </div>
                            <div class="p-3 bg-light rounded-3 mb-3">
                                <div class="small fw-bold">Fecha de creación de la cuenta</div>
                                <div class="text-muted"><?= fecha($usuario->creadoEn, 'd/m/Y H:i') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal de Edición de Perfil Completo -->
<!-- Modal de Edición de Perfil Completo con Barra de Desplazamiento y Validaciones -->
<div class="modal fade" id="modalEditarPerfil" tabindex="-1" aria-labelledby="modalEditarPerfilLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            <div class="modal-header bg-navy text-white px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="fs-4">📝</span>
                    <div>
                        <h3 class="modal-title fs-5 fw-bold text-white mb-0" id="modalEditarPerfilLabel">Actualizar datos del alumno</h3>
                        <p class="text-cyan small mb-0">Instituto Tecnológico de Ciudad Victoria</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="<?= url('/mi-cuenta') ?>" method="POST" id="formEditarPerfil" novalidate>
                <?= csrf_field() ?>
                
                <!-- Cuerpo del Modal con Barra de Desplazamiento Vertical Dedicada -->
                <div class="modal-body p-4" id="modalEditarPerfilBody">
                    <!-- Contenedor dinámico de alertas y notificaciones del formulario -->
                    <div id="perfilNotificationContainer" class="mb-3"></div>

                    <!-- Indicador visual de la barra de desplazamiento -->
                    <div class="p-3 bg-cyan-subtle text-navy rounded-3 border border-cyan-subtle mb-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fs-5">📜</span>
                            <span class="small">
                                <strong>Barra de navegación activa:</strong> Desplázate verticalmente por esta ventana para revisar y completar todos los datos sin necesidad de ajustar el zoom de tu navegador.
                            </span>
                        </div>
                        <span class="badge bg-navy text-white rounded-pill px-3 py-1 text-nowrap d-none d-sm-inline">
                            Scroll disponible ↕
                        </span>
                    </div>

                    <!-- 1. Datos Personales -->
                    <h4 class="h6 fw-bold text-cyan text-uppercase tracking-wider mb-3 d-flex align-items-center gap-2">
                        <span>👤</span>
                        <span>Datos personales</span>
                    </h4>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label for="nombre" class="form-label small fw-semibold">Nombre completo *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                   value="<?= e($usuario->nombre) ?>" required
                                   autocomplete="name"
                                   placeholder="Ej: Johan Gael Castillo Gonzalez"
                                   data-type="only-string"
                                   data-label="Nombre completo">
                            <div class="form-text fs-7 text-muted">Solo caracteres de tipo String (letras y espacios). No se permiten números ni símbolos.</div>
                            <div class="invalid-feedback">El nombre es obligatorio y solo puede contener letras y espacios (sin números ni símbolos).</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="numero_control" class="form-label small fw-semibold">No. De Control / Matrícula *</label>
                            <input type="text" class="form-control text-uppercase font-monospace" id="numero_control" name="numero_control"
                                   value="<?= e($perfil->get('numero_control') ?: '22380123') ?>" required
                                   maxlength="20"
                                   placeholder="22380123"
                                   data-type="control-number"
                                   data-label="Número de control">
                            <div class="form-text fs-7 text-muted">Identificador de estudiante (6 a 20 caracteres alfanuméricos).</div>
                            <div class="invalid-feedback">El número de control debe contener entre 6 y 20 caracteres alfanuméricos.</div>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="fecha_nacimiento" class="form-label small fw-semibold">Fecha de nacimiento</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                                   value="<?= e($perfil->get('fecha_nacimiento') ?: '2004-01-12') ?>"
                                   data-type="birthdate"
                                   data-label="Fecha de nacimiento">
                            <div class="invalid-feedback">Ingresa una fecha de nacimiento válida (edad entre 14 y 90 años).</div>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="genero" class="form-label small fw-semibold">Género</label>
                            <select class="form-select" id="genero" name="genero">
                                <?php foreach (Genero::cases() as $g): ?>
                                    <option value="<?= e($g->value) ?>" <?= ($perfil->get('genero') ?: 'hombre') === $g->value ? 'selected' : '' ?>>
                                        <?= e($g->etiqueta()) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="nacionalidad" class="form-label small fw-semibold">Nacionalidad</label>
                            <input type="text" class="form-control" id="nacionalidad" name="nacionalidad"
                                   value="<?= e($perfil->get('nacionalidad') ?: 'México') ?>"
                                   data-type="only-string"
                                   data-label="Nacionalidad"
                                   placeholder="México">
                            <div class="invalid-feedback">Solo se permiten caracteres de texto (letras).</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="pais_residencia" class="form-label small fw-semibold">País de residencia</label>
                            <select class="form-select" id="pais_residencia" name="pais_residencia">
                                <?php foreach (Catalogo::PAISES as $p): ?>
                                    <option value="<?= e($p) ?>" <?= ($perfil->get('pais_residencia') ?: 'México') === $p ? 'selected' : '' ?>>
                                        <?= e($p) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="telefono" class="form-label small fw-semibold">Teléfono de contacto</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono"
                                   value="<?= e($perfil->get('telefono') ?: '+52 834 156 1234') ?>"
                                   data-type="phone"
                                   data-label="Teléfono"
                                   placeholder="+52 834 156 1234">
                            <div class="form-text fs-7 text-muted">Formato estándar de 10 dígitos o con lada (+52).</div>
                            <div class="invalid-feedback">Formato de teléfono no válido (solo números, +, espacios o guiones).</div>
                        </div>
                    </div>

                    <!-- 2. Información de Contacto -->
                    <h4 class="h6 fw-bold text-cyan text-uppercase tracking-wider mb-3 d-flex align-items-center gap-2">
                        <span>📍</span>
                        <span>Información de contacto</span>
                    </h4>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label for="email_alterno" class="form-label small fw-semibold">Correo electrónico alterno</label>
                            <input type="email" class="form-control" id="email_alterno" name="email_alterno"
                                   value="<?= e($perfil->get('email_alterno') ?: 'johan.castillo@gmail.com') ?>"
                                   data-type="email"
                                   data-label="Correo alterno"
                                   placeholder="ejemplo@correo.com">
                            <div class="invalid-feedback">Por favor ingresa un correo alterno válido.</div>
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="ciudad" class="form-label small fw-semibold">Ciudad</label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad"
                                   value="<?= e($perfil->get('ciudad') ?: 'Ciudad Victoria') ?>"
                                   data-type="only-string"
                                   data-label="Ciudad"
                                   placeholder="Ciudad Victoria">
                            <div class="invalid-feedback">Solo se permiten letras y espacios.</div>
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="estado_residencia" class="form-label small fw-semibold">Estado</label>
                            <input type="text" class="form-control" id="estado_residencia" name="estado_residencia"
                                   value="<?= e($perfil->get('estado_residencia') ?: 'Tamaulipas') ?>"
                                   data-type="only-string"
                                   data-label="Estado"
                                   placeholder="Tamaulipas">
                            <div class="invalid-feedback">Solo se permiten letras y espacios.</div>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="codigo_postal" class="form-label small fw-semibold">Código Postal</label>
                            <input type="text" class="form-control font-monospace" id="codigo_postal" name="codigo_postal"
                                   value="<?= e($perfil->get('codigo_postal') ?: '87000') ?>"
                                   maxlength="5"
                                   data-type="postal-code"
                                   data-label="Código Postal"
                                   placeholder="87000">
                            <div class="form-text fs-7 text-muted">5 dígitos numéricos.</div>
                            <div class="invalid-feedback">El código postal debe tener exactamente 5 números.</div>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="contacto_emergencia_nombre" class="form-label small fw-semibold">Contacto de emergencia</label>
                            <input type="text" class="form-control" id="contacto_emergencia_nombre" name="contacto_emergencia_nombre"
                                   value="<?= e($perfil->get('contacto_emergencia_nombre') ?: 'María González') ?>"
                                   data-type="only-string"
                                   data-label="Nombre de contacto de emergencia"
                                   placeholder="Nombre completo">
                            <div class="invalid-feedback">Solo se permiten letras y espacios (sin números).</div>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="contacto_emergencia_telefono" class="form-label small fw-semibold">Teléfono de emergencia</label>
                            <input type="tel" class="form-control" id="contacto_emergencia_telefono" name="contacto_emergencia_telefono"
                                   value="<?= e($perfil->get('contacto_emergencia_telefono') ?: '+52 834 111 2233') ?>"
                                   data-type="phone"
                                   data-label="Teléfono de emergencia"
                                   placeholder="+52 834 111 2233">
                            <div class="invalid-feedback">Ingresa un teléfono válido de emergencia.</div>
                        </div>
                    </div>

                    <!-- 3. Información Académica -->
                    <h4 class="h6 fw-bold text-cyan text-uppercase tracking-wider mb-3 d-flex align-items-center gap-2">
                        <span>🎓</span>
                        <span>Información académica</span>
                    </h4>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label for="institucion" class="form-label small fw-semibold">Institución educativa</label>
                            <input type="text" class="form-control" id="institucion" name="institucion"
                                   value="<?= e($perfil->get('institucion') ?: 'Instituto Tecnológico de Ciudad Victoria') ?>"
                                   placeholder="Instituto Tecnológico de Ciudad Victoria">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="carrera" class="form-label small fw-semibold">Carrera o programa</label>
                            <input type="text" class="form-control" id="carrera" name="carrera"
                                   value="<?= e($perfil->get('carrera') ?: 'Ingeniería en Sistemas Computacionales') ?>"
                                   placeholder="Ingeniería en Sistemas Computacionales">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="semestre" class="form-label small fw-semibold">Semestre actual</label>
                            <input type="number" min="1" max="14" class="form-control" id="semestre" name="semestre"
                                   value="<?= e($perfil->get('semestre') ?: '6') ?>"
                                   data-type="semester"
                                   data-label="Semestre">
                            <div class="form-text fs-7 text-muted">Valor entre 1 y 14.</div>
                            <div class="invalid-feedback">El semestre debe ser un número entero entre 1 y 14.</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="modalidad" class="form-label small fw-semibold">Modalidad</label>
                            <select class="form-select" id="modalidad" name="modalidad">
                                <?php foreach (Modalidad::cases() as $m): ?>
                                    <option value="<?= e($m->value) ?>" <?= ($perfil->get('modalidad') ?: 'escolarizada') === $m->value ? 'selected' : '' ?>>
                                        <?= e($m->etiqueta()) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- 4. Información Adicional como Estudiante -->
                    <h4 class="h6 fw-bold text-cyan text-uppercase tracking-wider mb-3 d-flex align-items-center gap-2">
                        <span>📊</span>
                        <span>Información adicional como estudiante</span>
                    </h4>
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <label for="nivel_estudios" class="form-label small fw-semibold">Nivel de estudios</label>
                            <select class="form-select" id="nivel_estudios" name="nivel_estudios">
                                <?php foreach (NivelEstudios::cases() as $ne): ?>
                                    <option value="<?= e($ne->value) ?>" <?= ($perfil->get('nivel_estudios') ?: 'ingenieria') === $ne->value ? 'selected' : '' ?>>
                                        <?= e($ne->etiqueta()) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 col-md-5">
                            <label for="area_estudios" class="form-label small fw-semibold">Área de estudios o especialidad</label>
                            <input type="text" class="form-control" id="area_estudios" name="area_estudios"
                                   value="<?= e($perfil->get('area_estudios') ?: 'Tecnologías de la Información y Software') ?>">
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="nota_media" class="form-label small fw-semibold">Nota media (Promedio)</label>
                            <input type="number" step="0.01" min="0" max="100" class="form-control font-monospace" id="nota_media" name="nota_media"
                                   value="<?= e($perfil->get('nota_media') ?: '94.50') ?>"
                                   data-type="score"
                                   data-label="Nota media">
                            <div class="form-text fs-7 text-muted">Escala de 0.00 a 100.00.</div>
                            <div class="invalid-feedback">El promedio debe estar entre 0.00 y 100.00.</div>
                        </div>
                    </div>
                </div>

                <!-- Footer del Modal: Fijado en la parte inferior para visibilidad permanente -->
                <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-between align-items-center">
                    <span class="small text-muted" id="perfilFormValidationStatus">
                        Verifica que los datos sean correctos antes de guardar.
                    </span>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-cyan fw-bold rounded-pill px-4 shadow-sm" id="btnGuardarPerfil">
                            Guardar cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

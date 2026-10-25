<footer class="app-footer mt-auto pt-5 pb-4 px-3 px-md-4" aria-labelledby="footerTitle">
    <div class="container-fluid max-w-7xl mx-auto">
        <h2 id="footerTitle" class="visually-hidden">Pie de página</h2>

        <div class="footer-card">
            <div class="footer-cols">
                <div class="footer-col">
                    <h3 class="footer-heading">Lógica Visual ABP</h3>
                    <a href="<?= url('/quienes-somos') ?>" class="footer-link">Sobre nosotros <?= icon('chevron-right', 'icon-xs') ?></a>
                    <a href="<?= url('/blog') ?>" class="footer-link">Blog <?= icon('chevron-right', 'icon-xs') ?></a>
                </div>
                <div class="footer-col">
                    <h3 class="footer-heading">Catálogo</h3>
                    <a href="<?= url('/modulos') ?>" class="footer-link">Módulos <?= icon('chevron-right', 'icon-xs') ?></a>
                    <a href="<?= url('/videopodcast') ?>" class="footer-link">Videopodcast <?= icon('chevron-right', 'icon-xs') ?></a>
                    <a href="<?= url('/metodologia') ?>" class="footer-link">Metodología ABP <?= icon('chevron-right', 'icon-xs') ?></a>
                    <a href="<?= url('/evaluacion') ?>" class="footer-link">Evaluación <?= icon('chevron-right', 'icon-xs') ?></a>
                </div>
                <div class="footer-col">
                    <h3 class="footer-heading">Sitios institucionales</h3>
                    <a href="https://www.tecnm.mx" class="footer-link" target="_blank" rel="noopener noreferrer">TecNM <?= icon('chevron-right', 'icon-xs') ?></a>
                    <a href="https://cdvictoria.tecnm.mx" class="footer-link" target="_blank" rel="noopener noreferrer">Tec de Ciudad Victoria <?= icon('chevron-right', 'icon-xs') ?></a>
                    <a href="<?= url('/ayuda#contacto') ?>" class="footer-link">Contacto institucional <?= icon('chevron-right', 'icon-xs') ?></a>
                </div>
            </div>
            <div class="footer-help">
                <h3 class="footer-heading footer-heading-plain">¿Necesitas ayuda?</h3>
                <p class="small mb-3">Revisa nuestras preguntas frecuentes y, si tienes dudas, contacta con nosotros.</p>
                <a href="<?= url('/ayuda') ?>" class="btn btn-light btn-sm rounded-pill fw-bold px-4 text-navy">Ir a soporte y ayuda</a>
            </div>
        </div>

        <div class="footer-bottom">
            <div>
                <a class="d-inline-flex align-items-center gap-2 fw-bold text-decoration-none mb-3 footer-brand" href="<?= url('/') ?>">
                    <span class="brand-badge">{ }</span> Lógica Visual ABP
                </a>
                <nav aria-label="Enlaces legales" class="footer-legal">
                    <a href="<?= url('/ayuda#terminos') ?>">Condiciones generales</a>
                    <a href="<?= url('/ayuda#aviso-legal') ?>">Aviso legal</a>
                    <a href="<?= url('/ayuda#privacidad') ?>">Privacidad</a>
                    <a href="<?= url('/ayuda#cookies') ?>">Cookies</a>
                    <a href="<?= url('/ayuda#accesibilidad') ?>">Accesibilidad</a>
                    <a href="<?= url('/ayuda') ?>">Soporte y ayuda</a>
                </nav>
                <p class="small mt-2 mb-0 footer-copy">© <?= date('Y') ?> Lógica Visual ABP · Instituto Tecnológico de Ciudad Victoria. Todos los derechos reservados.</p>
            </div>
            <div class="footer-social" aria-label="Redes sociales">
                <a href="https://www.instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><?= icon('instagram') ?></a>
                <a href="https://www.linkedin.com" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><?= icon('linkedin') ?></a>
                <a href="https://x.com" target="_blank" rel="noopener noreferrer" aria-label="X"><?= icon('x-social') ?></a>
                <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><?= icon('facebook') ?></a>
                <a href="https://www.youtube.com" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><?= icon('youtube') ?></a>
            </div>
        </div>
    </div>
</footer>

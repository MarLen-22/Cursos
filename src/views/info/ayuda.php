<section class="py-5 bg-navy text-white position-relative">
    <div class="bg-grid position-absolute top-0 start-0 w-100 h-100 pointer-events-none"></div>
    <div class="container-fluid max-w-7xl mx-auto px-4 position-relative z-1">
        <div class="d-flex align-items-center gap-3 mb-3">
            <span class="fs-2">🛟</span>
            <div>
                <h1 class="h2 fw-bold text-white mb-0">Centro de Soporte y Ayuda</h1>
                <p class="text-cyan small mb-0">Preguntas frecuentes, contacto y avisos legales</p>
            </div>
        </div>
        <p class="lead text-slate-300 max-w-2xl mb-0">
            Encuentra respuestas a dudas frecuentes sobre el funcionamiento de los simuladores,
            tu cuenta de alumno, constancias oficiales y la metodología ABP.
        </p>
    </div>
</section>

<section class="py-5 bg-slate-50 min-vh-75">
    <div class="container-fluid max-w-7xl mx-auto px-4">
        <div class="row g-4 mb-5">
            <div class="col-12 col-lg-8">
                <h2 class="h4 fw-bold mb-4 text-navy">Preguntas Frecuentes (FAQ)</h2>

                <div class="accordion shadow-xs rounded-4 overflow-hidden border-0 mb-5" id="accordionFaq">
                    <div class="accordion-item border-0 border-bottom">
                        <h3 class="accordion-header" id="headingOne">
                            <button class="accordion-button fw-bold text-navy" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                ¿Cómo me inscribo a un módulo interactivo?
                            </button>
                        </h3>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionFaq">
                            <div class="accordion-body text-muted leading-relaxed small">
                                Una vez que crees tu cuenta o inicies sesión, ve al apartado <strong>Módulos Interactivos</strong> en el menú superior o en <strong>Explora</strong>. Al entrar a cualquier módulo, encontrarás el botón <em>Inscribirme en este módulo</em>. Automáticamente se registrará en tu apartado <strong>Mi aprendizaje</strong>.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 border-bottom">
                        <h3 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed fw-bold text-navy" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                ¿Cómo obtengo mi constancia o certificado oficial?
                            </button>
                        </h3>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFaq">
                            <div class="accordion-body text-muted leading-relaxed small">
                                Al completar el 100% de los retos del simulador del módulo, su estado cambiará a <strong>FINALIZADO</strong>. En tu tabla de <strong>Mi aprendizaje</strong> se habilitará el enlace <em>Ver certificado</em>, donde podrás visualizar, verificar e imprimir tu constancia con folio criptográfico único.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 border-bottom">
                        <h3 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed fw-bold text-navy" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                ¿Cómo activo el Modo Oscuro?
                            </button>
                        </h3>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFaq">
                            <div class="accordion-body text-muted leading-relaxed small">
                                Puedes hacer clic en el botón con icono de Luna / Sol ubicado en el encabezado superior a un lado de tu foto de perfil, o desde <strong>Mi cuenta &gt; Preferencias</strong>. El sistema recordará tu preferencia en todas tus sesiones.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0">
                        <h3 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed fw-bold text-navy" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                ¿Qué datos se requieren para registrar un alumno?
                            </button>
                        </h3>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionFaq">
                            <div class="accordion-body text-muted leading-relaxed small">
                                Para crear la cuenta solicitamos tu nombre completo, tu <strong>No. De Control</strong> (matrícula oficial), correo institucional y contraseña segura. Luego en <strong>Mi cuenta</strong> puedes completar tu información académica y de contacto para personalizar tus constancias.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Avisos Legales -->
                <div class="pt-4 border-top">
                    <h2 class="h5 fw-bold mb-3 text-navy" id="privacidad">Aviso de Privacidad y Términos</h2>
                    <div class="p-4 bg-white rounded-4 border small text-muted leading-relaxed mb-4" id="terminos">
                        <p class="mb-2"><strong>Protección de Datos:</strong> Los datos personales, número de control y registros de avance académico recopilados en esta plataforma son utilizados exclusivamente con fines didácticos, de evaluación formativa y emisión de constancias del Instituto Tecnológico de Ciudad Victoria.</p>
                        <p class="mb-0"><strong>Seguridad:</strong> Todas las contraseñas son almacenadas mediante hashes irreversibles (bcrypt) y las solicitudes web están protegidas contra ataques CSRF, Clickjacking e inyección de código.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4" id="contacto">
                <div class="card border-0 rounded-4 shadow-xs p-4 bg-white mb-4">
                    <h3 class="h6 fw-bold text-navy mb-3">Contacto Directo</h3>
                    <p class="small text-muted mb-3">¿Tienes alguna duda técnica o académica? Estamos a tu disposición:</p>
                    <ul class="list-unstyled small text-slate-700 space-y-2 mb-4">
                        <li class="mb-2">📧 <strong>Correo:</strong> soporte@logicavisual.edu.mx</li>
                        <li class="mb-2">📞 <strong>Teléfono:</strong> (834) 153 2000</li>
                        <li class="mb-2">📍 <strong>Campus:</strong> Blvd. Emilio Portes Gil No. 1301, Ciudad Victoria, Tamaulipas.</li>
                    </ul>
                    <a href="mailto:soporte@logicavisual.edu.mx" class="btn btn-cyan btn-sm fw-bold w-100">Enviar correo de soporte</a>
                </div>

                <div class="card border-0 rounded-4 shadow-xs p-4 bg-navy text-white">
                    <h3 class="h6 fw-bold text-cyan mb-2">Atajos de navegación</h3>
                    <p class="small text-slate-300 mb-2">Presiona la tecla <kbd class="bg-navy-dark text-cyan px-2 py-1">/</kbd> en cualquier página para buscar rápidamente módulos y cursos.</p>
                </div>
            </div>
        </div>
    </div>
</section>

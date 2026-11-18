# Lógica Visual ABP · Plataforma Educativa y Sistema de Gestión Académica

Plataforma web interactiva desarrollada para el **Instituto Tecnológico de Ciudad Victoria (ITCV · TecNM)** orientada al fortalecimiento del razonamiento lógico y la resolución de problemas en ingeniería mediante la metodología de **Aprendizaje Basado en Problemas (ABP)**, simuladores dinámicos, seguimiento académico y gestión administrativa integral.

---

## 📑 Índice de Documentación

1. [Stack Tecnológico y Entorno](#-1-stack-tecnológico-y-entorno)
2. [Arquitectura y Estructura del Proyecto](#-2-arquitectura-y-estructura-del-proyecto)
3. [Esquema de Base de Datos (`cursos`)](#-3-esquema-de-base-de-datos-cursos)
4. [Control de Acceso Basado en Roles (RBAC) y Credenciales](#-4-control-de-acceso-basado-en-roles-rbac-y-credenciales)
5. [Mapa de Rutas y Módulos del Sistema](#-5-mapa-de-rutas-y-módulos-del-sistema)
6. [Bitácora Estructurada del Proyecto (Changelog)](#-6-bitácora-estructurada-del-proyecto-changelog)
7. [Guía de Instalación Rápida en XAMPP](#-7-guía-de-instalación-rápida-en-xampp)
8. [Seguridad y Despliegue en Windows Azure](#-8-seguridad-y-despliegue-en-windows-azure)

---

## 🛠️ 1. Stack Tecnológico y Entorno

| Capa | Tecnología / Estándar | Detalles de Implementación |
| :--- | :--- | :--- |
| **Backend** | PHP 8.1+ (`declare(strict_types=1)`) | Arquitectura MVC con Front Controller, Autoloader PSR-4 propio, PDO y Enums nativos. |
| **Base de Datos** | MySQL 8.0 / MariaDB 10.4+ | Puerto `3306`, codificación `utf8mb4_unicode_ci`, migración y aseguramiento automático (`SchemaEnsurer`). |
| **Frontend** | HTML5 Semántico + CSS3 + JS ES6+ | Paleta institucional (`#0a1128` Navy, `#22d3ee` Cyan, `#4f46e5` Indigo), soporte de tema Claro/Oscuro. |
| **Framework UI** | Bootstrap 5.3.3 | Alojado localmente en `BootStrap/` y servido mediante `AssetController` con caché optimizada. |
| **Servidor Local** | Apache (XAMPP) | Puerto `8080` con reescritura de URLs vía `.htaccess` hacia `public/index.php`. |
| **Nube Destino** | Microsoft Azure | Preparado para *Azure App Service* + *Azure Database for MySQL (Flexible Server)* con soporte SSL/TLS. |

---

## 📂 2. Arquitectura y Estructura del Proyecto

El proyecto separa estrictamente la carpeta pública expuesta al navegador (`public/`) del código fuente de negocio (`src/`):

```text
Cursos/
│
├── public/                                  # Punto de entrada web (DocumentRoot / Front Controller)
│   ├── css/                                 # Hojas de estilo globales y por módulo
│   │   ├── styles.css                       # Paleta, botones, tarjetas, utilidades y componentes base
│   │   ├── alumno.css                       # Estilos de Mi Aprendizaje, Mi Cuenta, Header y Modo Oscuro
│   │   ├── fundamentos_programacion.css     # Estilos del simulador de Fundamentos de Programación
│   │   ├── calculo-diferencial.css          # Estilos del simulador de Cálculo Diferencial
│   │   ├── matematicas_discretas.css        # Estilos del simulador de Matemáticas Discretas
│   │   └── fisica.css                       # Estilos del simulador de Física
│   ├── js/                                  # Scripts interactivos del cliente
│   │   ├── app.js                           # Simulador base, alertas y autogeneración de slugs en admin
│   │   ├── alumno.js                        # Filtros de Mi Aprendizaje, validaciones en tiempo real y temas
│   │   ├── blog.js                          # Reproductor modal de videos de YouTube y visor de PDFs
│   │   ├── videopodcast.js                  # Interactividad de episodios de videopodcast
│   │   ├── fundamentos_programacion.js      # Lógica interactiva del curso de Programación
│   │   ├── calculo-diferencial.js           # Graficador y simulador de límites/derivadas
│   │   ├── matematicas_discretas.js         # Tablas de verdad, conjuntos y grafos interactivos
│   │   └── fisica.js                        # Laboratorio virtual de cinemática, fuerzas y energía
│   ├── pdf/                                 # Guías didácticas y artículos científicos en PDF
│   ├── .htaccess                            # Redirección de tráfico al Front Controller
│   └── index.php                            # Inicialización del núcleo MVC
│
├── src/                                     # Lógica del servidor (protegida fuera del alcance directo web)
│   ├── cli/
│   │   └── crear_superadmin.php             # Script CLI para aprovisionar o actualizar Superadministradores
│   ├── config/
│   │   ├── app.php                          # Configuración general de la aplicación y zona horaria
│   │   ├── database.php                     # Parámetros PDO y certificados SSL para MySQL/Azure
│   │   └── routes.php                       # Definición centralizada de rutas públicas, alumno y admin
│   ├── controllers/
│   │   ├── HomeController.php               # Portada principal
│   │   ├── AuthController.php               # Inicio de sesión, registro institucional y cierre de sesión
│   │   ├── ModuloController.php             # Catálogo público y vista interactiva de cada módulo
│   │   ├── AprendizajeController.php        # "Mi aprendizaje", inscripciones, avance, becas y constancias
│   │   ├── CuentaController.php             # "Mi cuenta", perfil personal, de contacto y académico
│   │   ├── MetodologiaController.php        # Vista de las 4 etapas de la Metodología ABP
│   │   ├── EvaluacionController.php         # Tipos de evaluación y barras de progreso cognitivo
│   │   ├── InfoController.php               # Blog educativo, Videopodcast, Quiénes somos y Ayuda
│   │   ├── BuscarController.php             # Buscador global de módulos y recursos
│   │   ├── AdminController.php              # Dashboard Admin y gestión integral de Usuarios y Roles (RBAC)
│   │   ├── AdminModuloController.php        # CRUD completo de Módulos Interactivos (Crear, Ver, Editar, Ocultar, Eliminar)
│   │   └── AdminAprendizajeController.php   # Gestión de Becas (TecVictoria/TECNM), Inscripciones y Etapas ABP
│   ├── core/
│   │   ├── App.php / Router.php             # Despachador de peticiones HTTP y coincidencias por expresiones regulares
│   │   ├── Auth.php / Middleware.php        # Estado de sesión y guardias de ruta (guest, auth, admin, superadmin)
│   │   ├── Database.php / SchemaEnsurer.php # Conexión PDO singleton y migración/semilla automática en caliente
│   │   └── Validator.php / Csrf.php         # Validación declarativa de formularios y protección anti-CSRF
│   ├── models/                              # Entidades de dominio y Repositorios SQL parametrizados
│   └── views/                               # Plantillas PHP organizadas por contexto (layouts, admin, modulos, etc.)
│
├── docs/                                    # Documentación técnica y scripts SQL
│   ├── database/
│   │   ├── 01_schema.sql                    # Esquema principal (usuario, administrador, modulo, evaluacion, etapa_abp)
│   │   ├── 02_seed.sql                      # Semilla de módulos oficiales, evaluaciones, etapas ABP y becas
│   │   ├── 03_usuario_app.sql               # Creación de usuario MySQL de mínimo privilegio
│   │   ├── 04_alumno_aprendizaje.sql        # Tablas perfil_alumno e inscripcion
│   │   └── 05_seed_alumno.sql               # Datos de ejemplo del perfil estudiantil e inscripciones
│   └── AZURE_MIGRATION.md                   # Manual de despliegue en Microsoft Azure
│
├── BootStrap/                               # Archivos compilados locales de Bootstrap 5.3.3
├── .env / .env.example                      # Variables de entorno
└── README.md                                # Documentación general y bitácora del equipo
```

---

## 🗄️ 3. Esquema de Base de Datos (`cursos`)

La base de datos relacional está compuesta por **8 tablas principales**:

1. **`usuario`**: Credenciales de todas las personas registradas (`id_usuario`, `nombre`, `email`, `password_hash`, `activo`, `intentos_fallidos`, `bloqueado_hasta`, `ultimo_acceso`).
2. **`administrador`**: Tabla de control de acceso RBAC (`id_administrador`, `id_usuario`, `nivel` ENUM `'jefe_curso'`, `'admin'`, `'superadmin'`, `cargo`, `departamento`, `otorgado_por`).
3. **`perfil_alumno`**: Datos extendidos 1:1 con `usuario` (`numero_control`, `telefono`, `fecha_nacimiento`, `genero`, `pais_residencia`, `ciudad`, `estado_residencia`, `codigo_postal`, `contacto_emergencia_nombre`, `institucion`, `carrera`, `semestre`, `modalidad`, `nivel_estudios`, `area_estudios`, `nota_media`).
4. **`modulo`**: Catálogo de simuladores y cursos ABP (`id_modulo`, `slug`, `titulo`, `descripcion`, `contenido`, `icono`, `color`, `nivel`, `orden`, `activo`).
5. **`inscripcion`**: Relación N:M entre usuarios y módulos en *Mi aprendizaje* (`id_inscripcion`, `id_usuario`, `id_modulo`, `estado` ENUM `'inscrito'`, `'en_curso'`, `'finalizado'`, `avance` 0–100%, `finalizado_en`).
6. **`beca`**: Catálogo de portales y convocatorias de becas institucionales (`id_beca`, `clave`, `institucion`, `siglas`, `titulo`, `descripcion`, `url`, `estado`, `icono`, `orden`, `activo`).
7. **`evaluacion`**: Criterios de evaluación formativa (`diagnostica`, `formativa`, `desempeno`) con su etapa e ícono.
8. **`etapa_abp`** y **`progreso`**: Pasos de la metodología ABP (`Explora`, `Formula`, `Experimenta`, `Reflexiona`) e indicadores porcentuales por usuario (`comprension`, `argumentacion`, `aplicacion`).

---

## 🔐 4. Control de Acceso Basado en Roles (RBAC) y Credenciales

### Roles del Sistema
- **`👑 Superadministrador` (`superadmin`)**: Control total de la plataforma. Único nivel con facultad para crear, promover, degradar o eliminar a otros Superadministradores y Administradores, además de gestionar módulos, usuarios, becas y parámetros ABP.
- **`🛡️ Administrador` (`admin`)**: Acceso completo al panel administrativo (`/admin`), alta y edición de usuarios, CRUD de módulos interactivos, gestión de inscripciones y becas.
- **`👨‍🏫 Jefe de Curso` (`jefe_curso`)**: Perfil docente/coordinador con acceso al panel administrativo para supervisar módulos interactivos, gestionar avances de estudiantes y actualizar parámetros pedagógicos.
- **`🎓 Estudiante` (`estudiante`)**: Usuario estándar con acceso a simuladores interactivos, inscripción a cursos, descarga de constancias de finalización, consulta de becas y edición de su perfil en *Mi cuenta*.

### Cuentas de Superadministrador Preconfiguradas

| Nombre | Correo Electrónico | Contraseña | Rol / Privilegios |
| :--- | :--- | :--- | :--- |
| **Johan Gael Castillo Gonzalez** | `admin@logicavisual.edu.mx` | `Admin1234!` | `Superadministrador` (Control total) |
| **Marlene Jaquelin Escobedo Mejia** | `adminMarlen@tecvictoria.edu.mx` | `AdminEsc1#` | `Superadministrador` (Control total) |

> **Nota técnica**: La clase [`SchemaEnsurer.php`](file:///c:/xampp/htdocs/Cursos/src/core/SchemaEnsurer.php) verifica y sincroniza automáticamente ambas cuentas y el esquema de base de datos al iniciar cualquier conexión en la aplicación.

---

## 🧭 5. Mapa de Rutas y Módulos del Sistema

### A) Rutas Públicas y Académicas
- `GET /` — Portada principal de Lógica Visual ABP.
- `GET /modulos` y `GET /modulos/{slug}` — Catálogo y simuladores interactivos (*Fundamentos de Programación*, *Cálculo Diferencial*, *Matemáticas Discretas* y *Física*).
- `GET /metodologia` — Las 4 etapas del Aprendizaje Basado en Problemas.
- `GET /evaluacion` — Modelo de evaluación diagnóstica, formativa y de desempeño.
- `GET /blog` — Publicaciones académicas con enlaces externos, reproductor modal de videos de YouTube y visor integrado de documentos PDF.
- `GET /videopodcast`, `GET /quienes-somos`, `GET /ayuda`, `GET /buscar`.

### B) Área del Alumno y Aprendizaje (Requiere Autenticación)
- `GET /mi-aprendizaje` — Panel de cursos inscritos, filtro interactivo por tipo/estado, descarga de **Constancias con Folio** y **Apartado Oficial de Becas**:
  - **Becas TecVictoria**: https://cdvictoria.tecnm.mx/alumnos/becas.html
  - **Becas TECNM**: https://www.tecnm.mx/?vista=Becas
- `GET /mi-cuenta` y `POST /mi-cuenta` — Gestión de datos personales, de contacto y académicos con validaciones en tiempo real.

### C) Panel Administrativo (`/admin`) — 4 Pestañas Principales
1. **`📊 Resumen` (`/admin`)**: Tarjetas de métricas generales (Total Usuarios, Módulos Activos, Equipo Gestor RBAC, Inscripciones y Becas), accesos rápidos y últimos usuarios registrados.
2. **`👥 Usuarios y Permisos` (`/admin/usuarios` y `/admin/usuarios/nuevo`)**:
   - Listado con buscador en tiempo real y filtros por rol y estado.
   - **Página de Alta y Edición Integral (`/admin/usuarios/nuevo` y `/admin/usuarios/{id}/editar`)**: Permite registrar o editar un **Estudiante**, **Jefe de Curso**, **Administrador** o **Superadministrador**, definiendo correo, contraseña (con generador seguro), cargo, departamento, datos personales, historial académico y módulos inscritos.
3. **`🧩 Módulos Interactivos` (`/admin/modulos`)**:
   - **CRUD Completo**: Crear nuevo módulo (`/admin/modulos/nuevo`), Ver en el sitio (`👁️`), Editar (`/admin/modulos/{id}/editar`), Ocultar/Publicar (`POST /admin/modulos/{id}/estado`) y **Eliminar curso permanentemente** (`POST /admin/modulos/{id}/eliminar`).
4. **`🎓 Aprendizaje, Becas y ABP` (`/admin/aprendizaje`)**:
   - **Gestión de Becas (TecVictoria y TECNM)**: Alta, activación/ocultamiento y eliminación de convocatorias de becas mostradas en *Mi aprendizaje*.
   - **Control de Inscripciones y Constancias**: Inscripción manual de usuarios a módulos, ajuste de porcentaje de avance (`0% - 100%`) y emisión/consulta de certificados.
   - **Configuración Pedagógica en Vivo**: Edición directa de las etapas de la Metodología ABP y de los tipos de Evaluación.

---

## 📋 6. Bitácora Estructurada del Proyecto (Changelog)

### Fase 1 · Arquitectura Base, Seguridad y Autenticación
- Diseño e implementación del núcleo MVC en PHP 8.1+ (`App`, `Router`, `Controller`, `View`, `Request`, `Response`).
- Configuración de seguridad: consultas preparadas PDO, tokens CSRF por sesión, cabeceras HTTP seguras y bloqueo automático ante intentos fallidos de inicio de sesión.
- Implementación de registro institucional con validación de dominio `@tecvictoria.edu.mx`.

### Fase 2 · Módulos Interactivos y Simuladores ABP
- Desarrollo de los 4 módulos interactivos oficiales con sus hojas de estilo, guías en PDF y simuladores en JavaScript:
  1. **Fundamentos de Programación** (`fundamentos-de-programacion`)
  2. **Cálculo Diferencial** (`calculo-diferencial`)
  3. **Matemáticas Discretas** (`matematicas-discretas`)
  4. **Física** (`fisica`)

### Fase 3 · Portal del Estudiante: Mi Aprendizaje, Constancias y Mi Cuenta
- Creación de las tablas `perfil_alumno` e `inscripcion` (`docs/database/04_alumno_aprendizaje.sql`).
- Desarrollo del módulo **Mi aprendizaje** (`/mi-aprendizaje`) con filtros dinámicos, seguimiento de estado (`Inscrito`, `En curso`, `Finalizado`) y generador de **Constancias Oficiales** imprimibles.
- Implementación de **Mi cuenta** (`/mi-cuenta`) con modal desplazable y validación en tiempo real de campos de texto, matrícula, teléfonos y promedio.

### Fase 4 · Blog Educativo, Videopodcast y Mejoras Visuales UI
- Integración del **Blog de Lógica Visual ABP** (`/blog`) con artículos de investigación, reproductor emergente de videos de YouTube y visor integrado de documentos PDF (`abp.pdf`, `Cap-21-Formacion-Docente.pdf` y `Aprendizaje en Simulación.pdf`).
- Corrección de contraste visual en las insignias de etapa dentro de **Evaluación** (`/evaluacion`) y en los botones de visualización de PDFs en `/blog` (`styles.css`).

### Fase 5 · Panel Administrativo Avanzado, Roles RBAC, CRUD Completo y Becas
- Aprovisionamiento de la cuenta de Superadministradora **`adminMarlen@tecvictoria.edu.mx`** (`AdminEsc1#`) con los mismos privilegios que **`admin@logicavisual.edu.mx`** (`Admin1234!`).
- Ampliación del modelo RBAC para soportar **Estudiantes**, **Jefes de Curso**, **Administradores** y **Superadministradores**, junto con el formulario integral de creación y edición de usuarios (`/admin/usuarios/nuevo`).
- Implementación del **CRUD completo en Módulos Interactivos** (`/admin/modulos`), añadiendo la eliminación permanente de cursos junto a las funciones de crear, ver, editar y ocultar/publicar.
- Creación de la 4.ª pestaña administrativa **`🎓 Aprendizaje, Becas y ABP`** (`/admin/aprendizaje`) para administrar becas, inscripciones de alumnos, constancias y parámetros pedagógicos.
- Integración del apartado oficial de **Becas TecVictoria** (https://cdvictoria.tecnm.mx/alumnos/becas.html) y **Becas TECNM** (https://www.tecnm.mx/?vista=Becas) en **Mi aprendizaje** tanto para alumnos como para administradores.

---

## 🚀 7. Guía de Instalación Rápida en XAMPP

1. **Clonar o ubicar el proyecto** en `C:\xampp\htdocs\Cursos`.
2. **Iniciar servicios** en el panel de control de XAMPP:
   - **Apache** (puerto `8080` o `80`)
   - **MySQL** (puerto `3306`)
3. **Importar la base de datos** ejecutando en terminal desde la raíz del proyecto:
   ```powershell
   cmd.exe /c "C:\xampp\mysql\bin\mysql.exe -u root -h 127.0.0.1 -P 3306 < docs\database\01_schema.sql"
   cmd.exe /c "C:\xampp\mysql\bin\mysql.exe -u root -h 127.0.0.1 -P 3306 < docs\database\02_seed.sql"
   cmd.exe /c "C:\xampp\mysql\bin\mysql.exe -u root -h 127.0.0.1 -P 3306 < docs\database\04_alumno_aprendizaje.sql"
   cmd.exe /c "C:\xampp\mysql\bin\mysql.exe -u root -h 127.0.0.1 -P 3306 < docs\database\05_seed_alumno.sql"
   ```
   *(Nota: Al abrir cualquier página de la aplicación, `SchemaEnsurer` aplicará automáticamente las actualizaciones de tablas, becas y cuentas de Superadministrador).*
4. **Acceder desde el navegador**:
   - **Sitio Principal**: `http://localhost:8080/Cursos/public/`
   - **Panel Administrativo**: `http://localhost:8080/Cursos/public/admin`

---

## ☁️ 8. Seguridad y Despliegue en Windows Azure

- **Inyección SQL**: 100% de consultas preparadas con PDO (`ATTR_EMULATE_PREPARES => false`).
- **XSS y CSRF**: Escapado automático de salida con `e()` y validación de token `_csrf` en todas las peticiones `POST`.
- **Guía de Migración a la Nube**: Consulta el procedimiento completo en [`docs/AZURE_MIGRATION.md`](file:///c:/xampp/htdocs/Cursos/docs/AZURE_MIGRATION.md).

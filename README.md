# Lógica Visual ABP · Software de Implementación de los Fundamentos de Programación

Plataforma educativa para el desarrollo del razonamiento lógico y la resolución de problemas en ingeniería mediante la metodología de **Aprendizaje Basado en Problemas (ABP)** y simuladores interactivos.

---

## 🛠️ Contexto Técnico y Stack Tecnológico

- **Lenguaje Backend**: PHP 8.1+ (tipado estricto, arquitectura MVC/Front-Controller, PDO, PSR-4).
- **Frontend**: HTML5 Semántico, CSS3 Vanilla (paleta de alta fidelidad: `#0a1128`, cian `#22d3ee`, índigo, púrpura), JavaScript moderno nativo.
- **Framework UI**: **Bootstrap 5.3.3** (local en directorio `BootStrap/` y CDN fallback).
- **Base de Datos**: **MySQL 8.0 / MariaDB 10.4+** (puerto 3306).
- **Servidor Web Local**: **Apache en XAMPP** (puerto 8080).
- **Destino Nube**: Preparado para **Windows Azure (Azure App Service + Azure Database for MySQL - Flexible Server)**.

---

## 📂 Convenciones de Estructura del Proyecto

```
Cursos/
│
├── public/                 # Directorio raíz expuesto al servidor web
│   ├── css/                # Hojas de estilo y diseño personalizado
│   │   └── img/            # Recursos gráficos e imágenes
│   ├── js/                 # Lógica interactiva del simulador y UI
│   ├── uploads/            # Archivos multimedia subidos por usuarios
│   ├── .htaccess           # Reescritura Front Controller
│   └── index.php           # Punto de entrada principal (Front Controller)
│
├── src/                    # Lógica de la aplicación (No expuesta directamente)
│   ├── cli/                # Scripts de terminal (ej. crear_superadmin.php)
│   ├── config/             # Archivos de configuración (app.php, database.php, routes.php)
│   ├── controllers/        # Controladores (Auth, Admin, Modulos, ABP, Evaluacion)
│   ├── core/               # Núcleo MVC (Router, Auth, Database PDO, Validator, CSRF)
│   ├── models/             # Entidades y repositorios de base de datos
│   └── views/              # Plantillas y vistas (layouts, modulos, admin, etc.)
│
├── docs/                   # Documentación técnica, scripts SQL y guías
│   ├── database/           # 01_schema.sql, 02_seed.sql, 03_usuario_app.sql
│   └── AZURE_MIGRATION.md  # Guía completa de migración a Windows Azure
│
├── BootStrap/              # Framework Bootstrap 5.3.3 local del proyecto
│   ├── css/
│   └── js/
├── .env                    # Configuración de entorno activo
├── .env.example            # Plantilla de variables de entorno
└── README.md               # Documentación general
```

---

## 🗄️ Esquema y Tablas de la Base de Datos (`cursos`)

El sistema utiliza las siguientes tablas con llaves foráneas y tipos restringidos:

1. **`usuario`**: Almacena las cuentas de estudiantes y docentes con hash bcrypt seguro, bloqueo por fuerza bruta y registro de auditoría.
2. **`administrador`**: Control de acceso basado en roles (RBAC). **Solo los usuarios con registro activo en esta tabla tienen acceso al panel de administración**. Incluye niveles `superadmin` y `admin`.
3. **`modulo`**: Catálogo de módulos interactivos (Fundamentos de Programación, Cálculo, Matemáticas Discretas, Física) con metadatos, icono, nivel y estado de publicación.
4. **`evaluacion`**: Tipos de evaluación educativa (Diagnóstica, Formativa y de Desempeño).
5. **`etapa_abp`**: Fases del proceso metodológico (Explora, Formula, Experimenta, Reflexiona).
6. **`progreso`**: Indicadores de rendimiento por estudiante (Comprensión, Argumentación, Aplicación).

---

## 🚀 Instalación y Puesta en Marcha en XAMPP

### 1. Iniciar Servicios en XAMPP
Asegúrate de que en el **XAMPP Control Panel** estén activos:
- **Apache** (escuchando en el puerto `8080`)
- **MySQL** (escuchando en el puerto `3306`)

### 2. Configurar la Base de Datos
Abre tu terminal en la raíz del proyecto (`c:\xampp\htdocs\Cursos`) y ejecuta:

```powershell
# Ejecutar esquema de base de datos
cmd.exe /c "C:\xampp\mysql\bin\mysql.exe -u root -h 127.0.0.1 -P 3306 cursos < docs\database\01_schema.sql"

# Cargar catálogo inicial de módulos y etapas
cmd.exe /c "C:\xampp\mysql\bin\mysql.exe -u root -h 127.0.0.1 -P 3306 cursos < docs\database\02_seed.sql"
```

### 3. Crear el Primer Superadministrador
Ejecuta el script CLI para aprovisionar tu cuenta con privilegios de administración:

```powershell
C:\xampp\php\php.exe src\cli\crear_superadmin.php "Johan Gael Castillo" "admin@logicavisual.edu.mx" "Admin1234*"
```

### 4. Abrir en el Navegador
Accede a cualquiera de las dos rutas (ambas funcionan gracias al Front Controller y la reescritura de Apache):
- **Directo**: `http://localhost:8080/Cursos/public/`
- **Raíz**: `http://localhost:8080/Cursos/`

---

## 🛡️ Seguridad Implementada

- **Protección contra Inyección SQL**: 100% de consultas parametrizadas con PDO y `ATTR_EMULATE_PREPARES => false`.
- **Protección CSRF**: Tokens criptográficos `hash_equals` por sesión verificados automáticamente en todas las peticiones POST.
- **Protección XSS**: Escapado contextual con función auxiliar `e()` usando UTF-8 y `ENT_QUOTES`.
- **Cabeceras HTTP de Seguridad**: CSP estricto, `X-Content-Type-Options: nosniff`, `X-Frame-Options: DENY`, `Referrer-Policy`.
- **Protección contra Fuerza Bruta**: Bloqueo automático temporal tras 5 intentos fallidos con tiempo constante de respuesta mediante hash señuelo.
- **Principio de Mínimo Privilegio**: La carpeta `src/` no es accesible desde el navegador web; solo `public/` expone recursos.

---

## ☁️ Migración a Windows Azure

Para migrar la base de datos a **Azure Database for MySQL (Flexible Server)** y la aplicación a **Azure App Service**, consulta la guía paso a paso en:
👉 [`docs/AZURE_MIGRATION.md`](file:///c:/xampp/htdocs/Cursos/docs/AZURE_MIGRATION.md).


- Cambiar contraseña en base de datos
# Guía de Migración a Windows Azure · Lógica Visual ABP

Esta guía detalla el procedimiento de producción para migrar el proyecto **Lógica Visual ABP** desde el entorno local de desarrollo con **XAMPP** hacia la infraestructura de nube de **Microsoft Azure** utilizando **Azure App Service (Linux o Windows con PHP 8.1+)** y **Azure Database for MySQL – Flexible Server**.

---

## 1. Arquitectura en Azure

```
   [ Clientes Web / Estudiantes / Administradores ]
                         │
                         ▼ (HTTPS / TLS 1.3)
      ┌──────────────────────────────────────┐
      │   Azure App Service (PHP 8.1+)       │
      │   Raíz de Documentos: /public        │
      │   - Enrutamiento Front Controller    │
      │   - ModSecurity / WAF                │
      │   - Certificado SSL Administrado     │
      └──────────────────┬───────────────────┘
                         │
                         ▼ (TLS con DigiCertGlobalRootCA)
      ┌──────────────────────────────────────┐
      │ Azure Database for MySQL (Flexible)  │
      │   - Red Privada / Reglas de Firewall │
      │   - Base de datos: cursos            │
      │   - Tablas: usuario, administrador,  │
      │     modulo, evaluacion, etapa_abp    │
      └──────────────────────────────────────┘
```

---

## 2. Paso a Paso para la Base de Datos (Azure Database for MySQL)

### Paso 2.1: Aprovisionar el Servidor en Azure
1. Ingresa al [Portal de Azure](https://portal.azure.com).
2. Crea un nuevo recurso: **Azure Database for MySQL (Servidor flexible)**.
3. Especifica:
   - **Nombre de servidor**: `cursos-mysql-srv` (ejemplo)
   - **Región**: `East US 2` o la más cercana a tu institución.
   - **Versión de MySQL**: `8.0`.
   - **Nivel de proceso**: `Burstable` (B1ms para bajo costo en pruebas) o `General Purpose` para producción.
   - **Autenticación**: Solo autenticación de MySQL.
   - **Nombre de usuario administrador**: `azureadmin`
   - **Contraseña segura**: Crea una contraseña robusta.

### Paso 2.2: Configurar Redes y Firewall
1. En la pestaña **Redes**, marca la opción:
   - *"Permitir el acceso público desde cualquier servicio de Azure dentro de Azure a este servidor"* (esto autoriza a App Service a comunicarse con la base de datos).
2. Agrega tu dirección IP pública local si deseas cargar el esquema inicial mediante MySQL Workbench o consola local.

### Paso 2.3: Importar el Esquema y Datos Semilla
Desde tu consola local o Cloud Shell de Azure, ejecuta:

```bash
# 1. Crear base de datos y tablas
mysql -h cursos-mysql-srv.mysql.database.azure.com -u azureadmin -p --ssl-ca=docs/database/DigiCertGlobalRootCA.crt.pem < docs/database/01_schema.sql

# 2. Cargar datos iniciales
mysql -h cursos-mysql-srv.mysql.database.azure.com -u azureadmin -p --ssl-ca=docs/database/DigiCertGlobalRootCA.crt.pem cursos < docs/database/02_seed.sql
```

---

## 3. Configuración de Azure App Service

### Paso 3.1: Crear el App Service
1. Crea un recurso **App Service (Web App)**:
   - **Pila en tiempo de ejecución**: `PHP 8.1` o `PHP 8.2`.
   - **Plan de App Service**: B1 (Basic) o superior.

### Paso 3.2: Configurar la Raíz del Sitio Web (Document Root)
El código sigue las mejores prácticas de seguridad, manteniendo la lógica en `src/` fuera del alcance de internet. El servidor web debe exponer únicamente la carpeta `public/`:

- **En Linux App Service (Nginx / PHP-FPM)**:
  En **Configuración > Configuración general > Comando de inicio**, coloca:
  ```bash
  cp /home/site/wwwroot/docs/nginx.conf /etc/nginx/sites-available/default && service nginx reload
  ```
  O configura el Document Root hacia `/home/site/wwwroot/public`.

- **En Windows App Service (IIS)**:
  En **Configuración > Asignaciones de ruta**:
  - Ruta física de `/`: `site\wwwroot\public`.

### Paso 3.3: Variables de Entorno en Azure (Application Settings)
En Azure App Service, ve a **Configuración > Variables de entorno** (o Application Settings). La clase `App\Core\Env` lee automáticamente las variables del sistema de Azure con prioridad sobre cualquier archivo `.env`:

| Nombre de la Variable | Valor en Azure |
| :--- | :--- |
| `APP_NAME` | `Lógica Visual ABP` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_TIMEZONE` | `America/Monterrey` |
| `DB_HOST` | `cursos-mysql-srv.mysql.database.azure.com` |
| `DB_PORT` | `3306` |
| `DB_DATABASE` | `cursos` |
| `DB_USERNAME` | `azureadmin` |
| `DB_PASSWORD` | `<Contraseña_De_Azure>` |
| `DB_SSL_CA` | `/home/site/wwwroot/docs/database/DigiCertGlobalRootCA.crt.pem` |
| `DB_SSL_VERIFY` | `true` |
| `SESSION_IDLE_LIFETIME` | `7200` |

---

## 4. Creación del Superadministrador en Producción

Una vez desplegada la aplicación en Azure, conéctate vía **SSH** (en el menú del App Service) y ejecuta:

```bash
php src/cli/crear_superadmin.php "Director Administrador" "admin@logicavisual.edu.mx" "ContrasenaSegura2026!"
```

El script registrará al usuario y le otorgará el nivel `superadmin` en la tabla `administrador`.
Cualquier otro usuario registrado desde el formulario web regular será creado como **Estudiante** y no tendrá acceso al panel `/admin` hasta que el Superadministrador se lo otorgue explícitamente desde la interfaz.

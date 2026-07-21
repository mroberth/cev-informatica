# 🎓 CEV Informática — Sistema de Gestión Académica

CEV Informática es un sistema integral de gestión académica diseñado para el **PNF en Informática**. Este sistema facilita el ciclo académico completo, permitiendo la gestión administrativa de estudiantes, docentes, materias, evaluaciones, calificaciones y seguridad del sistema.

[![PHP](https://img.shields.io/badge/PHP-8.x-blue?logo=php)](https://www.php.net/)
[![MariaDB](https://img.shields.io/badge/MariaDB-v10.x-orange?logo=mariadb)](https://mariadb.org/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-v5-purple?logo=bootstrap)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 🚀 Características principales

El sistema está dividido en tres roles principales, cada uno con funcionalidades específicas:

| Rol | Funcionalidades |
| :--- | :--- |
| **Administrador** | Gestión de Usuarios, Períodos, Trayectos, Estudiantes, Docentes, Asignaciones, Reportes Estadísticos. |
| **Profesor** | Gestión de Materias asignadas, Recursos, Evaluaciones, Calificación de estudiantes, Ver entregas. |
| **Estudiante** | Visualización de Materias, Calendario de evaluaciones, Entrega de actividades, Consulta de notas. |

*   **Notificaciones en tiempo real:** Sistema basado en EventSource (SSE).
*   **Reportes Estadísticos:** Paneles de control con gráficos interactivos (Chart.js).
*   **Seguridad avanzada:** JWT (JSON Web Tokens), Rate Limiting (Token Bucket), protección CSRF y CORS.

---

## 🛠️ Requisitos técnicos

*   PHP 8.0 o superior.
*   MariaDB o MySQL.
*   Composer (para gestión de dependencias).
*   Servidor Web (Apache recomendado con `mod_rewrite` habilitado).

---

## 📋 Instalación

Sigue estos pasos para configurar el entorno de desarrollo:

### 1. Clonar el repositorio
```bash
git clone https://github.com/tu-usuario/cev-informatica.git
cd cev-informatica
```

### 2. Instalar dependencias
```bash
composer install
```

### 3. Configuración del entorno (`.env`)
Crea un archivo `.env` en la raíz basado en la configuración necesaria:

```env
APP_NAME="Centro de Estudiantes Virtual"
APP_ENV=desarrollo
APP_URL=http://localhost
BASE_URL=/

DB_HOST=127.0.0.1
DB_PORT=3306
DB_SECURITY=cev_security
DB_BUSINESS=cev_business
DB_USER=root
DB_PASS=tu_contraseña

# Generar con: openssl rand -hex 64
JWT_SECRET=tu_secreto_generado_seguro
JWT_ISSUER=cev_informatica
JWT_AUDIENCE=cev_frontend
```

### 4. Configuración de Base de Datos
Importa los archivos SQL ubicados en el directorio `/bd/` en tu servidor MariaDB:
1. `cev_security.sql` (Tablas de autenticación, usuarios y rate_limits)
2. `cev_business.sql` (Tablas académicas)

### 5. Permisos de archivos
El servidor debe tener permisos de escritura en la carpeta de uploads:
```bash
sudo chown -R tu_usuario:www-data public/uploads/
sudo chmod -R 775 public/uploads/
```

### 6. Configuración del servidor (Apache)
Asegúrate de que tu `DocumentRoot` apunte a la carpeta `/public` y que el archivo `.htaccess` esté procesando las redirecciones correctamente.

---

## 🔒 Consideraciones de Seguridad implementadas

*   **Autenticación JWT:** Uso de tokens de acceso (15 min) y refresh tokens (7 días).
*   **Rate Limiting:** Middleware Token Bucket para proteger endpoints críticos (ej: `/iniciar_sesion` limitado a 5 req/min).
*   **Protección CSRF:** Implementación de patrón "Double-Submit Cookie" para todas las peticiones POST/PUT/DELETE.
*   **CORS:** Whitelist estricta de dominios permitidos gestionada mediante `APP_URL`.

---

## 📚 Estructura del proyecto
```text
/src
  /Core       # Núcleo: Router, Middleware, Database, Helpers
  /Modules    # Lógica de negocio (Controladores, Repositorios, Rutas)
/public       # Acceso público (js, css, plugins, .htaccess)
/bd           # Esquema de base de datos y migraciones
```

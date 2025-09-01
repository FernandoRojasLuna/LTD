# Laboratorio de Transformación Digital

## Descripción del Proyecto
Sistema web para gestionar el contenido y servicios del Laboratorio de Transformación Digital, desarrollado con Laravel, Vue.js, MySQL y Docker.

## Características Principales
- **Autenticación**: Sistema completo con Laravel Jetstream e Inertia.js
- **Gestión de Banners**: Carrusel de banners administrable
- **Gestión de Contenido**: Administración de servicios (Desarrollo, Realidad Virtual, etc.)
- **Gestión de Proyectos**: Proyectos en desarrollo y desplegados
- **Gestión de Staff**: Administración del equipo del laboratorio
- **Gestión de Clientes**: Administración de clientes del laboratorio
- **Sección de Contacto**: Información de contacto

## Tecnologías Utilizadas
- **Backend**: Laravel 12 con PHP 8.2
- **Frontend**: Vue.js 3 con Inertia.js
- **Base de Datos**: MySQL 8.0
- **Cache**: Redis
- **Contenedores**: Docker & Docker Compose
- **Servidor Web**: Nginx

## Estructura del Proyecto

### Modelos Principales
- **User**: Gestión de usuarios administradores
- **Banner**: Gestión de banners del carrusel
- **Content**: Gestión de contenido de servicios
- **Project**: Gestión de proyectos del laboratorio
- **Staff**: Gestión del equipo
- **Client**: Gestión de clientes
- **Contact**: Información de contacto

### Controladores
- **AdminController**: Panel de administración principal
- **BannerController**: Gestión de banners
- **ContentController**: Gestión de contenido
- **ProjectController**: Gestión de proyectos
- **StaffController**: Gestión de equipo
- **ClientController**: Gestión de clientes

## Configuración del Entorno

### Prerrequisitos
- Docker
- Docker Compose
- Git

### Instalación

1. **Clonar el repositorio**
```bash
git clone <url-del-repositorio>
cd LTD
```

2. **Configurar variables de entorno**
```bash
cp .env.example .env
```

3. **Construir y ejecutar contenedores**
```bash
docker-compose up -d --build
```

4. **Instalar dependencias**
```bash
# Instalar dependencias de PHP
docker-compose exec app composer install

# Instalar dependencias de Node.js
docker-compose exec app npm install --legacy-peer-deps
```

5. **Ejecutar migraciones**
```bash
docker-compose exec app php artisan migrate
```

6. **Compilar assets**
```bash
docker-compose exec app npm run build
```

## Servicios Docker

### Puertos de Acceso
- **Aplicación Web**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
- **MySQL**: localhost:3306
- **Redis**: localhost:6379

### Servicios Disponibles
- **app**: Contenedor principal con PHP-FPM y Laravel
- **nginx**: Servidor web Nginx
- **mysql**: Base de datos MySQL 8.0
- **redis**: Cache Redis
- **phpmyadmin**: Interface web para MySQL

## Desarrollo

### Comandos Útiles

**Iniciar servicios**
```bash
docker-compose up -d
```

**Ver logs**
```bash
docker-compose logs -f
```

**Acceder al contenedor de la aplicación**
```bash
docker-compose exec app bash
```

**Ejecutar migraciones**
```bash
docker-compose exec app php artisan migrate
```

**Crear migración**
```bash
docker-compose exec app php artisan make:migration create_banners_table
```

**Crear modelo**
```bash
docker-compose exec app php artisan make:model Banner -mcr
```

**Compilar assets en desarrollo**
```bash
docker-compose exec app npm run dev
```

**Compilar assets para producción**
```bash
docker-compose exec app npm run build
```

## Estructura de la Base de Datos

### Tabla: banners
- id (bigint, PK)
- title (varchar)
- description (text)
- image_path (varchar)
- link_url (varchar, nullable)
- is_active (boolean)
- order (integer)
- created_at, updated_at

### Tabla: contents
- id (bigint, PK)
- title (varchar)
- description (text)
- content_type (enum: desarrollo, realidad_virtual, etc.)
- image_path (varchar, nullable)
- is_active (boolean)
- created_at, updated_at

### Tabla: projects
- id (bigint, PK)
- name (varchar)
- description (text)
- status (enum: en_desarrollo, desplegado)
- image_path (varchar, nullable)
- demo_url (varchar, nullable)
- github_url (varchar, nullable)
- technologies (json)
- created_at, updated_at

### Tabla: staff
- id (bigint, PK)
- name (varchar)
- position (varchar)
- bio (text)
- image_path (varchar, nullable)
- email (varchar, nullable)
- linkedin_url (varchar, nullable)
- github_url (varchar, nullable)
- is_active (boolean)
- created_at, updated_at

### Tabla: clients
- id (bigint, PK)
- name (varchar)
- company (varchar, nullable)
- email (varchar)
- phone (varchar, nullable)
- project_description (text)
- status (enum: prospecto, activo, completado)
- created_at, updated_at

## Próximos Pasos

1. **Crear las migraciones de la base de datos**
2. **Desarrollar los modelos con sus relaciones**
3. **Implementar los controladores**
4. **Crear las vistas de Vue.js**
5. **Implementar el panel de administración**
6. **Desarrollar la API REST**
7. **Implementar el frontend público**
8. **Configurar el sistema de archivos para imágenes**
9. **Implementar validaciones y seguridad**
10. **Crear seeders para datos de prueba**

## Contribución
Para contribuir al proyecto, por favor sigue las siguientes pautas:
1. Fork del repositorio
2. Crear una rama para la feature
3. Commit de los cambios
4. Push a la rama
5. Crear un Pull Request

## Licencia
Este proyecto es privado y pertenece al Laboratorio de Transformación Digital.

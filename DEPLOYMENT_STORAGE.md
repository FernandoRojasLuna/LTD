# Guía de Deployment - Corrección de Storage

## Problema Identificado
Las imágenes no se mostraban porque los archivos se guardaban directamente en `public/storage/` en lugar de usar el sistema de almacenamiento correcto de Laravel (`storage/app/public/`).

## Cambios Realizados

### Controladores Corregidos
- `ProjectController`: Ahora usa `Storage::disk('public')` para projects
- `ClientController`: Ahora usa `Storage::disk('public')` para clients  
- `TechnologyController`: Ahora usa `Storage::disk('public')` para technologies
- `StaffController`: Ahora usa `Storage::disk('public')` para staff
- `BannerController`: Ahora usa `Storage::disk('public')` para banners

## Comandos de Deployment en Producción

### Proceso Completo (Recomendado)

```bash
# 1. Hacer pull de los cambios
git pull origin main

# 2. Ejecutar migración (migra archivos y crea estructura)
./migrate-storage.sh

# 3. Probar que todo funciona
# - Subir una imagen desde el admin
# - Verificar que se muestre en el frontend
# - Actualizar una imagen existente

# 4. Ejecutar limpieza (elimina backups y carpetas antiguas)
./cleanup-storage.sh
```

### 1. Crear el enlace simbólico (si no existe)
```bash
cd /ruta/a/tu/proyecto
php artisan storage:link
```

Este comando crea un enlace simbólico desde `public/storage` → `storage/app/public`

### 2. Verificar que el enlace existe
```bash
ls -la public/storage
```

Debería mostrar algo como: `public/storage -> ../storage/app/public`

### 3. Crear los directorios necesarios en storage/app/public
```bash
mkdir -p storage/app/public/projects
mkdir -p storage/app/public/clients
mkdir -p storage/app/public/staff
mkdir -p storage/app/public/technologies
mkdir -p storage/app/public/banners
mkdir -p storage/app/public/contents
```

### 4. Establecer permisos correctos
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

O si usas un usuario diferente (por ejemplo tu usuario):
```bash
chown -R tu-usuario:www-data storage
chown -R tu-usuario:www-data bootstrap/cache
```

### 5. Migrar archivos existentes (SOLO SI TIENES ARCHIVOS EN public/storage)

**⚠️ IMPORTANTE: Hacer backup antes de ejecutar esto**

```bash
# Backup de public/storage (por si acaso)
cp -r public/storage public/storage.backup

# Migrar archivos de projects
if [ -d "public/storage/projects" ]; then
    cp -r public/storage/projects/* storage/app/public/projects/ 2>/dev/null || true
fi

# Migrar archivos de clients
if [ -d "public/storage/clients" ]; then
    cp -r public/storage/clients/* storage/app/public/clients/ 2>/dev/null || true
fi

# Migrar archivos de staff
if [ -d "public/storage/staff" ]; then
    cp -r public/storage/staff/* storage/app/public/staff/ 2>/dev/null || true
fi

# Migrar archivos de technologies
if [ -d "public/storage/technologies" ]; then
    cp -r public/storage/technologies/* storage/app/public/technologies/ 2>/dev/null || true
fi

# Migrar archivos de banners
if [ -d "public/storage/banners" ]; then
    cp -r public/storage/banners/* storage/app/public/banners/ 2>/dev/null || true
fi

# Migrar archivos de contents
if [ -d "public/storage/contents" ]; then
    cp -r public/storage/contents/* storage/app/public/contents/ 2>/dev/null || true
fi
```

### 6. Limpiar directorios duplicados (DESPUÉS DE VERIFICAR QUE TODO FUNCIONA)

**Opción Automática:**
```bash
./cleanup-storage.sh
```

**Opción Manual:**
```bash
# Eliminar backups
rm -rf public/storage.backup*

# Eliminar carpetas duplicadas en storage/public/
rm -rf storage/public/projects
rm -rf storage/public/clients
rm -rf storage/public/staff
rm -rf storage/public/technologies
rm -rf storage/public/banners
rm -rf storage/public/contents

# Eliminar storage/public si está vacío
rmdir storage/public 2>/dev/null || true

# Limpiar caché
php artisan config:clear
php artisan cache:clear
```

### 7. Verificar estructura final
```bash
# public/storage debe ser un symlink
ls -la public/storage

# storage/app/public debe contener los directorios con archivos
ls -la storage/app/public/

# Verificar permisos
ls -la storage/app/public/projects/
ls -la storage/app/public/clients/
```

## Verificación en Producción

### 1. Probar subida de archivo
- Ir al admin y subir una nueva imagen de proyecto/cliente
- Verificar que se guarde en `storage/app/public/[carpeta]/`
- Verificar que se muestre correctamente en el frontend

### 2. Verificar rutas en base de datos
```bash
php artisan tinker
```

```php
// Verificar que las rutas en BD sean relativas (sin /storage/)
\App\Models\Project::first()->image;
// Debería mostrar: "projects/xxxxx.png" o "/storage/projects/xxxxx.png"

\App\Models\Client::first()->logo;
// Debería mostrar: "clients/xxxxx.png"
```

### 3. Limpiar caché
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## Si algo sale mal

### Restaurar backup
```bash
# Si hiciste backup y necesitas volver atrás
rm -rf storage/app/public
mv public/storage.backup public/storage
```

### Verificar logs
```bash
tail -f storage/logs/laravel.log
```

## Notas Importantes

1. **Nunca guardar archivos directamente en `public/`**
   - Siempre usar `Storage::disk('public')->put()` o `storeAs()`

2. **El symlink es crítico**
   - Sin `php artisan storage:link` los archivos no serán accesibles

3. **Permisos**
   - El usuario del servidor web (www-data, nginx, apache) debe tener permisos de escritura en `storage/`

4. **En Docker**
   - Si usas Docker, el symlink debe recrearse cada vez que el contenedor se reinicia
   - Agregar `php artisan storage:link` al script de inicio del contenedor

## Comandos útiles para debugging

```bash
# Ver el contenido real del directorio (siguiendo symlinks)
ls -laR public/storage/

# Ver donde apunta el symlink
readlink public/storage

# Verificar si un archivo existe
ls -la storage/app/public/projects/archivo.png

# Verificar permisos de un archivo
stat storage/app/public/projects/archivo.png
```

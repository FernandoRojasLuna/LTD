# Guía de Despliegue - LTD

## Problema Común: Imágenes No Se Muestran en Producción

### Causa
Laravel almacena archivos subidos en `storage/app/public/` pero el servidor web sirve archivos desde `public/`. Para que las imágenes sean accesibles públicamente, Laravel necesita un **symlink** (enlace simbólico) de `public/storage` apuntando a `storage/app/public`.

### Síntomas
- ✅ Las imágenes funcionan en desarrollo local
- ❌ En producción aparecen errores 404 como:
  ```
  GET https://ltd.sistemasudh.com/storage/projects/xxx.png 404 (Not Found)
  GET https://ltd.sistemasudh.com/storage/clients/xxx.jpg 404 (Not Found)
  ```
- ❌ Al subir nuevas imágenes aparece error 500 en la API

### Solución

#### 1. Crear el Symlink (Después de Cada Deploy)

**Opción A: Desde el servidor (SSH)**
```bash
cd /ruta/a/tu/proyecto
php artisan storage:link
```

**Opción B: Desde tu script de deploy**
Añade este comando a tu `.gitlab-ci.yml`, `deploy.sh` o herramienta de deploy:
```bash
php artisan storage:link
```

#### 2. Verificar el Symlink

Comprueba que el symlink existe:
```bash
ls -la public/storage
```

Debe mostrar algo como:
```
lrwxrwxrwx 1 user user 30 Oct 24 10:00 public/storage -> ../storage/app/public
```

#### 3. Verificar Permisos

Asegúrate de que el servidor web pueda escribir en `storage/app/public`:
```bash
chmod -R 775 storage/app/public
chown -R www-data:www-data storage/app/public  # En Ubuntu/Debian
# O en CentOS/RHEL:
chown -R apache:apache storage/app/public
```

#### 4. Verificar Configuración de Nginx/Apache

**Nginx:**
```nginx
location /storage {
    alias /ruta/absoluta/a/tu/proyecto/storage/app/public;
}
```

**Apache:**
El symlink debe funcionar automáticamente si `FollowSymLinks` está habilitado en tu VirtualHost:
```apache
<Directory /ruta/a/tu/proyecto/public>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

---

## Checklist de Deploy

### Antes del Deploy
- [ ] Commit y push de todos los cambios
- [ ] Actualizar `.env` en producción con valores correctos
- [ ] Verificar que `APP_URL` en `.env` apunta a tu dominio

### Durante el Deploy
```bash
# 1. Actualizar código
git pull origin main

# 2. Instalar dependencias
composer install --no-dev --optimize-autoloader
npm install && npm run build

# 3. Limpiar cachés
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 4. Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Ejecutar migraciones
php artisan migrate --force

# 6. ⚠️ IMPORTANTE: Crear symlink de storage
php artisan storage:link

# 7. Verificar permisos
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Después del Deploy
- [ ] Verificar que el symlink existe: `ls -la public/storage`
- [ ] Probar subir una nueva imagen desde el admin
- [ ] Verificar que las imágenes existentes se muestran correctamente
- [ ] Revisar logs: `tail -f storage/logs/laravel.log`

---

## Troubleshooting

### Las imágenes aún no se muestran después de crear el symlink

1. **Verificar que las imágenes existen físicamente:**
   ```bash
   ls -la storage/app/public/projects
   ls -la storage/app/public/clients
   ls -la storage/app/public/staff
   ```

2. **Verificar que el symlink apunta al lugar correcto:**
   ```bash
   readlink -f public/storage
   # Debe mostrar: /ruta/absoluta/storage/app/public
   ```

3. **Si el symlink ya existe pero no funciona, recrearlo:**
   ```bash
   rm public/storage
   php artisan storage:link
   ```

### Error 500 al subir imágenes

1. **Verificar permisos de escritura:**
   ```bash
   namei -l storage/app/public
   ```

2. **Verificar espacio en disco:**
   ```bash
   df -h
   ```

3. **Revisar logs de Laravel:**
   ```bash
   tail -50 storage/logs/laravel.log
   ```

### Imágenes antiguas (antes de la corrección)

Las imágenes que se subieron con el código antiguo pueden estar en `public/storage/` en lugar de `storage/app/public/`. Tienes dos opciones:

**Opción A: Migrar imágenes antiguas**
```bash
# Mover imágenes antiguas a la ubicación correcta
mv public/storage/projects/* storage/app/public/projects/
mv public/storage/clients/* storage/app/public/clients/
mv public/storage/staff/* storage/app/public/staff/
# etc...
```

**Opción B: Actualizar rutas en la base de datos**
```sql
-- Si las rutas incluyen /storage/ al inicio
UPDATE projects SET image = REPLACE(image, '/storage/', '') WHERE image LIKE '/storage/%';
UPDATE clients SET logo = REPLACE(logo, '/storage/', '') WHERE logo LIKE '/storage/%';
UPDATE staff SET image = REPLACE(image, '/storage/', '') WHERE image LIKE '/storage/%';
-- etc...
```

---

## Estructura de Archivos Correcta

```
tu-proyecto/
├── public/
│   ├── index.php
│   ├── storage -> ../storage/app/public  ← SYMLINK (creado con artisan storage:link)
│   └── ...
├── storage/
│   ├── app/
│   │   ├── private/           ← Archivos NO públicos
│   │   └── public/            ← Archivos públicos (imágenes, documentos)
│   │       ├── banners/
│   │       ├── clients/
│   │       ├── contents/
│   │       ├── projects/
│   │       ├── staff/
│   │       └── technologies/
│   ├── framework/
│   └── logs/
└── ...
```

## URLs de Acceso

- Frontend sirve desde: `https://ltd.sistemasudh.com/storage/projects/archivo.png`
- Físicamente el archivo está en: `storage/app/public/projects/archivo.png`
- Laravel lo sirve vía symlink: `public/storage -> ../storage/app/public`

---

## Scripts Útiles

### Script de Deploy Completo

Guarda esto como `deploy.sh`:

```bash
#!/bin/bash
set -e

echo "🚀 Iniciando deploy..."

# Actualizar código
git pull origin main

# Instalar dependencias
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Limpiar y optimizar
php artisan down
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Migraciones
php artisan migrate --force

# Crear symlink (idempotente)
php artisan storage:link

# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permisos
chmod -R 775 storage bootstrap/cache

php artisan up

echo "✅ Deploy completado!"
echo "👉 Verificar: https://ltd.sistemasudh.com"
```

Hazlo ejecutable:
```bash
chmod +x deploy.sh
```

Ejecútalo:
```bash
./deploy.sh
```

---

## Contacto y Soporte

Si después de seguir estos pasos las imágenes aún no se muestran, revisa:

1. Los logs de Laravel: `storage/logs/laravel.log`
2. Los logs del servidor web (Nginx/Apache)
3. La consola del navegador (F12) para ver errores JavaScript
4. El output de `php artisan storage:link`

**Comando de verificación rápida:**
```bash
# Ver estado completo del storage
php artisan tinker
>>> Storage::disk('public')->exists('projects');
>>> Storage::disk('public')->files('projects');
```

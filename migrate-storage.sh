#!/bin/bash

# Script de deployment para corregir storage de imágenes
# Ejecutar en producción después de hacer pull de los cambios

echo "======================================"
echo "Storage Migration Script"
echo "======================================"
echo ""

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Función para imprimir mensajes
print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}!${NC} $1"
}

# 1. Crear enlace simbólico
echo "1. Creando enlace simbólico..."
if [ -L "public/storage" ]; then
    print_warning "El symlink ya existe"
    ls -la public/storage
else
    php artisan storage:link
    if [ $? -eq 0 ]; then
        print_success "Symlink creado correctamente"
    else
        print_error "Error al crear symlink"
        exit 1
    fi
fi
echo ""

# 2. Crear directorios en storage/app/public
echo "2. Creando directorios necesarios..."
directories=("projects" "clients" "staff" "technologies" "banners" "contents")

for dir in "${directories[@]}"; do
    mkdir -p "storage/app/public/$dir"
    if [ $? -eq 0 ]; then
        print_success "Directorio storage/app/public/$dir creado"
    else
        print_warning "El directorio storage/app/public/$dir ya existe o hubo un error"
    fi
done
echo ""

# 3. Backup de archivos existentes
echo "3. Creando backup de public/storage..."
if [ -d "public/storage.backup" ]; then
    print_warning "Ya existe un backup anterior"
    echo "¿Desea sobrescribirlo? (s/n)"
    read -r response
    if [ "$response" != "s" ]; then
        print_warning "Saltando backup"
    else
        rm -rf public/storage.backup
        # Backup solo de directorios reales (no del symlink)
        if [ -d "public/storage" ] && [ ! -L "public/storage" ]; then
            cp -r public/storage public/storage.backup
            print_success "Backup creado en public/storage.backup"
        else
            print_warning "public/storage es un symlink, no se necesita backup"
        fi
    fi
else
    if [ -d "public/storage" ] && [ ! -L "public/storage" ]; then
        cp -r public/storage public/storage.backup
        print_success "Backup creado en public/storage.backup"
    else
        print_warning "public/storage es un symlink, no se necesita backup"
    fi
fi
echo ""

# 4. Migrar archivos existentes
echo "4. Migrando archivos existentes..."
migrated=0

for dir in "${directories[@]}"; do
    if [ -d "public/storage/$dir" ] && [ ! -L "public/storage/$dir" ]; then
        # Contar archivos
        file_count=$(find "public/storage/$dir" -type f 2>/dev/null | wc -l)
        
        if [ "$file_count" -gt 0 ]; then
            echo "  Migrando $file_count archivo(s) de $dir..."
            cp -r "public/storage/$dir"/* "storage/app/public/$dir/" 2>/dev/null
            
            if [ $? -eq 0 ]; then
                print_success "$dir: $file_count archivos migrados"
                migrated=$((migrated + file_count))
            else
                print_warning "$dir: Error al migrar algunos archivos"
            fi
        else
            print_warning "$dir: No hay archivos para migrar"
        fi
    else
        # Si es un symlink, los archivos ya deberían estar en el lugar correcto
        if [ -L "public/storage/$dir" ]; then
            print_success "$dir: Ya usa symlink, no requiere migración"
        else
            print_warning "$dir: Directorio no existe en public/storage"
        fi
    fi
done

echo ""
print_success "Total de archivos migrados: $migrated"
echo ""

# 5. Establecer permisos
echo "5. Estableciendo permisos..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Detectar usuario del servidor web
if groups | grep -q 'www-data'; then
    WEB_USER='www-data'
elif groups | grep -q 'nginx'; then
    WEB_USER='nginx'
elif groups | grep -q 'apache'; then
    WEB_USER='apache'
else
    WEB_USER=$(whoami)
    print_warning "No se detectó usuario del servidor web, usando: $WEB_USER"
fi

echo "Usando usuario: $WEB_USER"
chown -R "$USER:$WEB_USER" storage 2>/dev/null
chown -R "$USER:$WEB_USER" bootstrap/cache 2>/dev/null

if [ $? -eq 0 ]; then
    print_success "Permisos establecidos correctamente"
else
    print_warning "No se pudieron establecer permisos (puede requerir sudo)"
fi
echo ""

# 6. Limpiar caché
echo "6. Limpiando caché de Laravel..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
print_success "Caché limpiada"
echo ""

# 7. Verificación
echo "7. Verificando estructura..."
echo ""
echo "Estructura de public/storage:"
ls -la public/storage
echo ""
echo "Contenido de storage/app/public:"
ls -la storage/app/public/
echo ""

# Contar archivos en cada directorio
echo "Archivos por directorio:"
for dir in "${directories[@]}"; do
    if [ -d "storage/app/public/$dir" ]; then
        count=$(find "storage/app/public/$dir" -type f 2>/dev/null | wc -l)
        echo "  $dir: $count archivos"
    fi
done
echo ""

# 8. Limpiar carpetas antiguas (si existen y no son symlinks)
echo "8. Limpiando estructura antigua..."

# Verificar si public/storage es un directorio real (no symlink)
if [ -d "public/storage" ] && [ ! -L "public/storage" ]; then
    print_warning "public/storage es un directorio real, necesita ser eliminado"
    echo "¿Desea eliminar public/storage y recrear el symlink? (s/n)"
    read -r response
    if [ "$response" = "s" ]; then
        # Backup adicional por seguridad
        if [ ! -d "public/storage.backup" ]; then
            cp -r public/storage public/storage.backup.pre-clean
            print_success "Backup de seguridad creado: public/storage.backup.pre-clean"
        fi
        
        # Eliminar el directorio real
        rm -rf public/storage
        print_success "Directorio public/storage eliminado"
        
        # Recrear el symlink
        php artisan storage:link
        if [ $? -eq 0 ]; then
            print_success "Symlink recreado correctamente"
        else
            print_error "Error al recrear symlink"
        fi
    else
        print_warning "Limpieza cancelada. El directorio public/storage permanece"
    fi
else
    print_success "public/storage ya es un symlink, estructura correcta"
fi

# Limpiar carpetas dentro de storage/public que no deberían estar ahí
echo ""
echo "Limpiando carpetas duplicadas en storage/public/..."
for dir in "${directories[@]}"; do
    if [ -d "storage/public/$dir" ]; then
        print_warning "Encontrada carpeta duplicada: storage/public/$dir"
        rm -rf "storage/public/$dir"
        print_success "Eliminada: storage/public/$dir"
    fi
done

# Eliminar storage/public si existe y está vacío
if [ -d "storage/public" ]; then
    if [ -z "$(ls -A storage/public)" ]; then
        rmdir storage/public
        print_success "Directorio vacío storage/public eliminado"
    else
        print_warning "storage/public no está vacío, revisar manualmente"
    fi
fi

echo ""

# 9. Resumen final
echo "======================================"
echo "Resumen"
echo "======================================"
print_success "Migración y limpieza completada"
echo ""
echo "Estructura final:"
ls -la public/storage
echo ""
echo "Pasos siguientes:"
echo "1. Probar subir una imagen desde el admin"
echo "2. Verificar que se muestre correctamente"
echo "3. Si todo funciona, eliminar backups con:"
echo "   rm -rf public/storage.backup*"
echo ""
echo "Si algo sale mal, restaurar con:"
echo "   rm -rf storage/app/public"
echo "   mv public/storage.backup public/storage"
echo ""
print_warning "Revisar DEPLOYMENT_STORAGE.md para más detalles"
echo ""

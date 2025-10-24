#!/bin/bash

# Script de limpieza post-migración
# Ejecutar SOLO después de verificar que todo funciona correctamente

echo "======================================"
echo "Storage Cleanup Script"
echo "======================================"
echo ""

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}!${NC} $1"
}

# Verificar que public/storage sea un symlink
if [ ! -L "public/storage" ]; then
    print_error "public/storage no es un symlink. Ejecuta primero migrate-storage.sh"
    exit 1
fi

print_success "Verificación: public/storage es un symlink correcto"
echo ""

# Advertencia
print_warning "Este script eliminará:"
echo "  - Todos los backups (public/storage.backup*)"
echo "  - Carpetas duplicadas en storage/public/"
echo "  - Archivos temporales de migración"
echo ""
echo "¿Estás seguro de continuar? (escribe 'SI' para confirmar)"
read -r confirmation

if [ "$confirmation" != "SI" ]; then
    print_warning "Limpieza cancelada"
    exit 0
fi

echo ""
echo "Iniciando limpieza..."
echo ""

# 1. Eliminar backups
echo "1. Eliminando backups..."
if [ -d "public/storage.backup" ] || ls public/storage.backup* 1> /dev/null 2>&1; then
    rm -rf public/storage.backup*
    print_success "Backups eliminados"
else
    print_warning "No se encontraron backups"
fi
echo ""

# 2. Limpiar storage/public/ (carpetas duplicadas)
echo "2. Limpiando carpetas duplicadas en storage/public/..."
directories=("projects" "clients" "staff" "technologies" "banners" "contents")
cleaned=0

for dir in "${directories[@]}"; do
    if [ -d "storage/public/$dir" ]; then
        rm -rf "storage/public/$dir"
        print_success "Eliminado: storage/public/$dir"
        cleaned=$((cleaned + 1))
    fi
done

if [ $cleaned -eq 0 ]; then
    print_warning "No se encontraron carpetas duplicadas"
fi
echo ""

# 3. Eliminar storage/public si existe y está vacío
echo "3. Verificando storage/public/..."
if [ -d "storage/public" ]; then
    if [ -z "$(ls -A storage/public)" ]; then
        rmdir storage/public
        print_success "Directorio vacío storage/public eliminado"
    else
        echo "Contenido de storage/public:"
        ls -la storage/public/
        print_warning "storage/public no está vacío. Revisa el contenido arriba."
        echo "¿Desea eliminarlo de todas formas? (s/n)"
        read -r response
        if [ "$response" = "s" ]; then
            rm -rf storage/public
            print_success "storage/public eliminado"
        else
            print_warning "storage/public no eliminado"
        fi
    fi
else
    print_success "storage/public no existe (correcto)"
fi
echo ""

# 4. Buscar y reportar archivos en ubicaciones incorrectas
echo "4. Buscando archivos en ubicaciones incorrectas..."

# Buscar imágenes directamente en public/ (no en public/storage)
orphaned=0
if find public/ -maxdepth 1 -type f \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" -o -name "*.gif" -o -name "*.webp" \) | grep -q .; then
    print_warning "Se encontraron imágenes sueltas en public/:"
    find public/ -maxdepth 1 -type f \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" -o -name "*.gif" -o -name "*.webp" \)
    orphaned=1
fi

# Buscar imágenes en storage/ (nivel raíz)
if find storage/ -maxdepth 1 -type f \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" -o -name "*.gif" -o -name "*.webp" \) | grep -q .; then
    print_warning "Se encontraron imágenes sueltas en storage/:"
    find storage/ -maxdepth 1 -type f \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" -o -name "*.gif" -o -name "*.webp" \)
    orphaned=1
fi

if [ $orphaned -eq 0 ]; then
    print_success "No se encontraron archivos huérfanos"
fi
echo ""

# 5. Verificar estructura final
echo "5. Verificando estructura final..."
echo ""
echo "Symlink de public/storage:"
ls -la public/storage
echo ""
echo "Contenido de storage/app/public/:"
ls -la storage/app/public/
echo ""

# Contar archivos
echo "Archivos por carpeta:"
for dir in "${directories[@]}"; do
    if [ -d "storage/app/public/$dir" ]; then
        count=$(find "storage/app/public/$dir" -type f 2>/dev/null | wc -l)
        echo "  storage/app/public/$dir: $count archivos"
    fi
done
echo ""

# 6. Limpiar caché de Laravel
echo "6. Limpiando caché..."
php artisan config:clear > /dev/null 2>&1
php artisan cache:clear > /dev/null 2>&1
php artisan view:clear > /dev/null 2>&1
print_success "Caché limpiada"
echo ""

# Resumen
echo "======================================"
echo "Limpieza Completada"
echo "======================================"
print_success "La estructura de storage está limpia y optimizada"
echo ""
echo "Estructura final correcta:"
echo "  public/storage -> ../storage/app/public (symlink)"
echo "  storage/app/public/[projects|clients|staff|etc]/ (archivos)"
echo ""
print_warning "Recomendaciones finales:"
echo "  1. Probar subir/actualizar imágenes en el admin"
echo "  2. Verificar que se muestren correctamente en el frontend"
echo "  3. Monitorear los logs: tail -f storage/logs/laravel.log"
echo ""

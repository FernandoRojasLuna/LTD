#!/bin/bash

# Script de limpieza inmediata para desarrollo
# Elimina archivos sueltos y duplicados

echo "======================================"
echo "Limpieza de Archivos Sueltos"
echo "======================================"
echo ""

# Colores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}!${NC} $1"
}

# 1. Eliminar archivos sueltos en storage/ (raíz)
echo "1. Limpiando archivos sueltos en storage/..."
removed=0
for file in storage/*.jpg storage/*.png storage/*.gif storage/*.webp; do
    if [ -f "$file" ]; then
        echo "  Eliminando: $file"
        rm "$file"
        removed=$((removed + 1))
    fi
done

if [ $removed -gt 0 ]; then
    print_success "Eliminados $removed archivos de storage/"
else
    print_warning "No hay archivos sueltos en storage/"
fi
echo ""

# 2. Eliminar archivos sueltos en storage/app/public/ (excepto .gitignore)
echo "2. Limpiando archivos sueltos en storage/app/public/..."
removed=0
for file in storage/app/public/*.jpg storage/app/public/*.png storage/app/public/*.gif storage/app/public/*.webp; do
    if [ -f "$file" ]; then
        echo "  Eliminando: $file"
        rm "$file"
        removed=$((removed + 1))
    fi
done

if [ $removed -gt 0 ]; then
    print_success "Eliminados $removed archivos de storage/app/public/"
else
    print_warning "No hay archivos sueltos en storage/app/public/"
fi
echo ""

# 3. Eliminar test_banners.json si existe
echo "3. Limpiando archivos de test..."
if [ -f "storage/app/test_banners.json" ]; then
    rm storage/app/test_banners.json
    print_success "Eliminado: storage/app/test_banners.json"
else
    print_warning "No existe storage/app/test_banners.json"
fi
echo ""

# 4. Mostrar estructura limpia
echo "4. Estructura actual de storage/app/public/:"
ls -lh storage/app/public/
echo ""

# 5. Contar archivos en cada carpeta
echo "5. Archivos por carpeta:"
for dir in storage/app/public/*/; do
    if [ -d "$dir" ]; then
        count=$(find "$dir" -type f | wc -l)
        dirname=$(basename "$dir")
        echo "  $dirname: $count archivos"
    fi
done
echo ""

print_success "Limpieza completada"
echo ""
echo "Estructura correcta ahora:"
echo "  storage/app/public/projects/   <- Imágenes de proyectos"
echo "  storage/app/public/clients/    <- Logos de clientes"
echo "  storage/app/public/staff/      <- Fotos del equipo"
echo "  storage/app/public/technologies/ <- Iconos de tecnologías"
echo "  storage/app/public/banners/    <- Imágenes de banners"
echo "  storage/app/public/contents/   <- Imágenes de contenidos"
echo ""

#!/bin/bash

# Script de diagnóstico para verificar storage en producción
# Ejecutar en el servidor de producción

echo "======================================"
echo "Diagnóstico de Storage - Producción"
echo "======================================"
echo ""

# Colores
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

print_ok() {
    echo -e "${GREEN}✓${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}!${NC} $1"
}

# 1. Verificar symlink
echo "1. Verificando symlink public/storage..."
if [ -L "public/storage" ]; then
    target=$(readlink -f public/storage)
    print_ok "public/storage es un symlink"
    echo "   Apunta a: $target"
    
    # Verificar que el target existe
    if [ -d "$target" ]; then
        print_ok "El directorio destino existe"
    else
        print_error "El directorio destino NO existe: $target"
        echo "   Ejecuta: mkdir -p storage/app/public"
    fi
elif [ -d "public/storage" ]; then
    print_error "public/storage es un DIRECTORIO (debería ser symlink)"
    echo "   Solución:"
    echo "     1. Respaldar: mv public/storage public/storage.backup"
    echo "     2. Crear symlink: php artisan storage:link"
elif [ ! -e "public/storage" ]; then
    print_error "public/storage NO EXISTE"
    echo "   Ejecuta: php artisan storage:link"
else
    print_warning "public/storage existe pero no se puede identificar tipo"
fi
echo ""

# 2. Verificar directorios en storage/app/public
echo "2. Verificando directorios en storage/app/public/..."
directories=("projects" "clients" "staff" "technologies" "banners" "contents")
missing=0

for dir in "${directories[@]}"; do
    if [ -d "storage/app/public/$dir" ]; then
        count=$(find "storage/app/public/$dir" -type f 2>/dev/null | wc -l)
        print_ok "storage/app/public/$dir ($count archivos)"
    else
        print_error "storage/app/public/$dir NO EXISTE"
        missing=$((missing + 1))
    fi
done

if [ $missing -gt 0 ]; then
    echo ""
    echo "   Crear directorios faltantes:"
    for dir in "${directories[@]}"; do
        echo "     mkdir -p storage/app/public/$dir"
    done
fi
echo ""

# 3. Verificar permisos
echo "3. Verificando permisos..."
if [ -d "storage/app/public" ]; then
    perms=$(stat -c %a storage/app/public 2>/dev/null || stat -f %A storage/app/public 2>/dev/null)
    owner=$(stat -c %U:%G storage/app/public 2>/dev/null || stat -f %Su:%Sg storage/app/public 2>/dev/null)
    
    echo "   Permisos: $perms"
    echo "   Propietario: $owner"
    
    if [ -w "storage/app/public" ]; then
        print_ok "El directorio es escribible"
    else
        print_error "El directorio NO es escribible"
        echo "   Ejecuta: chmod -R 775 storage"
        echo "   Y: chown -R www-data:www-data storage (o tu usuario web)"
    fi
else
    print_error "storage/app/public no existe"
fi
echo ""

# 4. Verificar archivos accesibles via web
echo "4. Verificando accesibilidad web..."
if [ -L "public/storage" ] && [ -d "storage/app/public" ]; then
    # Buscar un archivo de prueba
    test_file=$(find storage/app/public -type f -name "*.png" -o -name "*.jpg" | head -1)
    
    if [ -n "$test_file" ]; then
        # Extraer ruta relativa
        rel_path=${test_file#storage/app/public/}
        echo "   Archivo de prueba encontrado: $rel_path"
        echo "   Debería ser accesible en: /storage/$rel_path"
        
        # Verificar que el archivo es legible
        if [ -r "$test_file" ]; then
            print_ok "El archivo es legible"
        else
            print_error "El archivo NO es legible"
        fi
    else
        print_warning "No se encontraron archivos de prueba"
    fi
else
    print_error "No se puede verificar accesibilidad (faltan symlink o directorios)"
fi
echo ""

# 5. Verificar configuración de Laravel
echo "5. Verificando configuración Laravel..."
if [ -f ".env" ]; then
    app_url=$(grep "^APP_URL=" .env | cut -d= -f2)
    echo "   APP_URL: $app_url"
    
    filesystem_disk=$(grep "^FILESYSTEM_DISK=" .env | cut -d= -f2)
    if [ -n "$filesystem_disk" ]; then
        echo "   FILESYSTEM_DISK: $filesystem_disk"
    else
        print_warning "FILESYSTEM_DISK no está definido (usa 'local' por defecto)"
    fi
else
    print_error ".env no encontrado"
fi
echo ""

# 6. Verificar logs recientes
echo "6. Revisando logs recientes (últimas 10 líneas)..."
if [ -f "storage/logs/laravel.log" ]; then
    echo "   Últimos errores relacionados con storage:"
    tail -20 storage/logs/laravel.log | grep -i "storage\|file\|permission" | tail -5 || echo "   (ninguno encontrado)"
else
    print_warning "No hay archivo de logs"
fi
echo ""

# Resumen
echo "======================================"
echo "Resumen"
echo "======================================"
echo ""

# Crear lista de acciones necesarias
actions_needed=()

if [ ! -L "public/storage" ]; then
    actions_needed+=("Crear symlink: php artisan storage:link")
fi

if [ $missing -gt 0 ]; then
    actions_needed+=("Crear directorios faltantes en storage/app/public/")
fi

if [ ! -w "storage/app/public" ] 2>/dev/null; then
    actions_needed+=("Ajustar permisos: chmod -R 775 storage && chown -R www-data:www-data storage")
fi

if [ ${#actions_needed[@]} -eq 0 ]; then
    print_ok "Todo parece estar configurado correctamente"
    echo ""
    echo "Si aún hay problemas:"
    echo "  1. Revisar logs: tail -f storage/logs/laravel.log"
    echo "  2. Probar subir una imagen desde el admin"
    echo "  3. Verificar permisos del servidor web"
else
    print_warning "Se encontraron ${#actions_needed[@]} problema(s):"
    echo ""
    for action in "${actions_needed[@]}"; do
        echo "  - $action"
    done
fi
echo ""

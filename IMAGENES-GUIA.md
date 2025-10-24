# 📁 Guía: ¿Dónde Poner las Imágenes Estáticas?

## 🎯 TU PROBLEMA

Tienes imágenes en **dos ubicaciones diferentes** y no sabes cuál usar:

1. **Raíz del proyecto:** `test.jpg` y `tmp-logo.png`
2. **¿Dónde deberían estar realmente?**

## ✅ RESPUESTA SIMPLE

En tu proyecto Laravel + Vue hay **DOS tipos de imágenes** y cada una va en un lugar diferente:

---

## 📦 TIPO 1: Imágenes Estáticas (Logos, Iconos, Assets del Diseño)

### ¿Qué son?
- Imágenes que **NO se suben desde el admin**
- Son parte del código/diseño (logos, iconos, fondos fijos)
- Las subes **manualmente al servidor** vía FTP/Git

### ✅ Ubicación Correcta: `public/images/`

```
tu-proyecto/
├── public/
│   ├── images/              👈 AQUÍ van las imágenes estáticas
│   │   ├── ltd.svg          ✅ Logo del sitio
│   │   ├── ltdclaro.svg     ✅ Logo modo claro
│   │   ├── ltdoscuro.svg    ✅ Logo modo oscuro
│   │   ├── placeholder.jpg  ✅ Imagen por defecto
│   │   └── mi-fondo.jpg     ✅ Fondo decorativo
│   ├── index.php
│   └── storage -> ../storage/app/public
```

### Cómo se usan en Vue:
```vue
<!-- Referencia absoluta desde public/ -->
<img src="/images/ltd.svg" alt="Logo" />
<img src="/images/mi-fondo.jpg" alt="Fondo" />
```

### URL en producción:
```
https://ltd.sistemasudh.com/images/ltd.svg
https://ltd.sistemasudh.com/images/mi-fondo.jpg
```

### Ejemplos en tu código:
```vue
<!-- PublicLayout.vue - Logo del header -->
<img :src="isDark ? '/images/ltdoscuro.svg' : '/images/ltdclaro.svg'" />

<!-- AuthenticationCardLogo.vue -->
<img src="/images/ltd.svg" alt="LTD Logo" />
```

---

## 📤 TIPO 2: Imágenes Dinámicas (Subidas por Usuarios/Admin)

### ¿Qué son?
- Imágenes que **SE SUBEN desde el panel de administración**
- Proyectos, clientes, staff, banners, contenidos
- Se guardan automáticamente vía controladores Laravel

### ✅ Ubicación Correcta: `storage/app/public/`

```
tu-proyecto/
├── storage/
│   ├── app/
│   │   ├── public/              👈 AQUÍ se guardan las imágenes subidas
│   │   │   ├── projects/        ✅ Imágenes de proyectos
│   │   │   ├── clients/         ✅ Logos de clientes
│   │   │   ├── staff/           ✅ Fotos del equipo
│   │   │   ├── banners/         ✅ Banners del sitio
│   │   │   ├── technologies/    ✅ Iconos de tecnologías
│   │   │   ├── contents/        ✅ Imágenes de contenidos
│   │   │   ├── fondo3.jpg       ✅ Fondos decorativos (manuales)
│   │   │   ├── logoGore.jpg     ✅ Logos institucionales
│   │   │   └── muni.jpg
```

### Cómo se usan en Vue:
```vue
<!-- Referencia a través del symlink /storage/ -->
<img :src="`/storage/projects/${project.image}`" />
<img src="/storage/fondo3.jpg" />
```

### URL en producción:
```
https://ltd.sistemasudh.com/storage/projects/xxx.png
https://ltd.sistemasudh.com/storage/fondo3.jpg
```

### ⚠️ IMPORTANTE: Necesitas el symlink
El navegador accede vía `/storage/` pero los archivos están en `storage/app/public/`.
Laravel conecta ambos con un symlink:

```bash
# En tu servidor, ejecuta una sola vez:
php artisan storage:link

# Verifica que existe:
ls -la public/storage
# Debe mostrar: public/storage -> ../storage/app/public
```

### Ejemplos en tu código:
```vue
<!-- ContactForm.vue - Fondo decorativo -->
<div :style="{ backgroundImage: 'url(/storage/fondo3.jpg)' }"></div>

<!-- AboutLab.vue - Fondo del CTA -->
<img :src="getStorageUrl('fondo3.jpg')" />
```

---

## 🎬 TU CASO: test.jpg y tmp-logo.png

### Estado Actual:
```
❌ /home/fernando/LTD/test.jpg          (Raíz del proyecto)
❌ /home/fernando/LTD/tmp-logo.png      (Raíz del proyecto)
```

**Problema:** Estas imágenes NO están en ninguna ubicación correcta. La raíz del proyecto NO es accesible públicamente.

### ¿Son imágenes estáticas o dinámicas?

**Si son logos/fondos que forman parte del diseño:**
```bash
# Muévelas a public/images/
mv test.jpg public/images/
mv tmp-logo.png public/images/

# Úsalas así en Vue:
<img src="/images/test.jpg" />
<img src="/images/tmp-logo.png" />
```

**Si son fondos decorativos como fondo3.jpg:**
```bash
# Muévelas a storage/app/public/
mv test.jpg storage/app/public/
mv tmp-logo.png storage/app/public/

# Úsalas así en Vue:
<img src="/storage/test.jpg" />
<img src="/storage/tmp-logo.png" />

# ⚠️ Asegúrate de que el symlink existe:
php artisan storage:link
```

---

## 📊 TABLA RESUMEN

| Tipo de Imagen | Ubicación | URL en Producción | Ejemplo |
|----------------|-----------|-------------------|---------|
| **Estáticas** (logos, iconos) | `public/images/` | `/images/archivo.jpg` | Logo del sitio |
| **Dinámicas** (admin) | `storage/app/public/` | `/storage/archivo.jpg` | Foto de proyecto |
| **Fondos manuales** | `storage/app/public/` | `/storage/archivo.jpg` | fondo3.jpg |

---

## 🚀 CHECKLIST PARA PRODUCCIÓN

### Para Imágenes Estáticas:
- [ ] Copiar imágenes a `public/images/` en producción
- [ ] Verificar que son accesibles: `https://tudominio.com/images/archivo.jpg`

### Para Imágenes Dinámicas/Fondos:
- [ ] Copiar imágenes a `storage/app/public/` en producción
- [ ] Ejecutar `php artisan storage:link`
- [ ] Verificar symlink: `ls -la public/storage`
- [ ] Verificar permisos: `chmod -R 775 storage/app/public`
- [ ] Probar acceso: `https://tudominio.com/storage/archivo.jpg`

---

## 🔍 CÓMO IDENTIFICAR QUÉ TIPO DE IMAGEN ES

**Es Estática si:**
- ✅ Es un logo, icono, o recurso del diseño
- ✅ La subes manualmente al código (Git, FTP)
- ✅ No cambia según el contenido del admin
- ✅ Es parte de la interfaz (UI)

**Va a `public/images/`**

**Es Dinámica si:**
- ✅ Se sube desde el panel de administración
- ✅ Los usuarios pueden cambiarla
- ✅ Está en la base de datos (projects, clients, staff, etc.)
- ✅ Es contenido, no diseño

**Va a `storage/app/public/`**

---

## 💡 EJEMPLOS DE TU PROYECTO

### Estáticas (en `public/images/`):
```
✅ /images/ltd.svg           → Logo principal
✅ /images/ltdclaro.svg      → Logo modo claro
✅ /images/ltdoscuro.svg     → Logo modo oscuro
```

### Dinámicas (en `storage/app/public/`):
```
✅ /storage/fondo3.jpg           → Fondo decorativo
✅ /storage/logoGore.jpg         → Logo institucional
✅ /storage/muni.jpg             → Logo institucional
✅ /storage/projects/xxx.png     → Imagen de proyecto
✅ /storage/clients/xxx.jpg      → Logo de cliente
✅ /storage/staff/xxx.jpg        → Foto de staff
```

---

## ❓ FAQ

### P: ¿Por qué fondo3.jpg está en storage/ y no en public/images/?
**R:** Porque aunque es un fondo decorativo que no se sube desde el admin, se decidió manejarlo como contenido dinámico para poder reemplazarlo fácilmente sin redeployar el código.

### P: ¿Puedo tener todo en public/images/?
**R:** No es recomendable. Las imágenes en `public/` se versionan con Git. Las de `storage/` no, porque son generadas por usuarios.

### P: ¿Qué pasa si pongo una imagen en la raíz del proyecto?
**R:** No será accesible desde el navegador. Solo `public/` es servido por el servidor web.

### P: En desarrollo local funciona, ¿por qué no en producción?
**R:** Probablemente falta el symlink. Ejecuta `php artisan storage:link` en producción.

---

## 🎯 SOLUCIÓN PARA TUS IMÁGENES

### Opción 1: Si son logos/assets del diseño
```bash
# En tu computadora (desarrollo):
mv test.jpg public/images/
mv tmp-logo.png public/images/
git add public/images/
git commit -m "Mover imágenes estáticas a public/images"
git push

# En producción (servidor):
git pull
# Las imágenes ya estarán disponibles en /images/
```

### Opción 2: Si son fondos como fondo3.jpg
```bash
# En tu computadora (NO uses Git para esto):
# Copia manualmente vía FTP/SCP a:
# /home/tu-usuario/proyecto/storage/app/public/

# O en el servidor directamente:
mv /tmp/test.jpg /home/fernando/LTD/storage/app/public/
mv /tmp/tmp-logo.png /home/fernando/LTD/storage/app/public/

# Verifica el symlink:
php artisan storage:link
```

---

## 📝 RESUMEN ULTRA-RÁPIDO

```
┌─────────────────────────────────────────────┐
│ ¿La imagen es parte del DISEÑO?            │
│ (logo, icono, placeholder)                  │
│                                             │
│ SÍ  → public/images/                       │
│      URL: /images/archivo.jpg              │
│                                             │
│ NO  → Es contenido que cambia?             │
│       (proyectos, clientes, staff)          │
│                                             │
│       SÍ  → storage/app/public/            │
│            URL: /storage/archivo.jpg       │
│            ⚠️  Necesitas: php artisan      │
│               storage:link                  │
└─────────────────────────────────────────────┘
```

---

**¿Necesitas ayuda para mover tus imágenes?** Dime qué son `test.jpg` y `tmp-logo.png` y te ayudo a ponerlas en el lugar correcto. 🚀

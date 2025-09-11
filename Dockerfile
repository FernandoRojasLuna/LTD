FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libmagic-dev \
    zip \
    unzip \
    nodejs \
    npm

# Limpiar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP
RUN apt-get update && apt-get install -y libmagic-dev file \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# enable fileinfo (should be available by default, but ensure it)
RUN docker-php-ext-install fileinfo || true

# Obtener Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear usuario del sistema para ejecutar comandos de Composer y Artisan
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copiar archivos existentes de la aplicación al directorio de trabajo
COPY . /var/www

# Copiar permisos existentes de la aplicación al directorio de trabajo
COPY --chown=www:www . /var/www

# Cambiar al usuario www
USER www

# Exponer puerto 9000 y ejecutar php-fpm server
EXPOSE 9000
CMD ["php-fpm"]

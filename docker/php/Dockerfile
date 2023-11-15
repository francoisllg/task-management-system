
FROM php:8.2-fpm

# Instalación de dependencias y extensiones PHP...

# Copiar el contenido de tu proyecto Laravel al contenedor
COPY . /usr/src/app

# Cambiar al directorio del proyecto Laravel
WORKDIR /usr/src/app

# Instalación de Composer y extensiones PHP...

# Copiar el ejecutable de instalación de extensiones PHP
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Instalar las extensiones PHP que necesites (ejemplo: pdo_mysql)
RUN chmod +x /usr/local/bin/install-php-extensions && sync \
    && install-php-extensions pdo_mysql

# Copiar el archivo de configuración .env
COPY .env.example .env

# Generar la clave de aplicación de Laravel
RUN php artisan key:generate

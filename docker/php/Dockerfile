
FROM php:8.2-fpm

COPY . /usr/src/app

WORKDIR /usr/src/app

RUN PATH=$PATH:/usr/src/app/vendor/bin:bin

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && mv composer /usr/local/bin/composer

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/


# Instalar las extensiones PHP que necesites (ejemplo: pdo_mysql)
RUN chmod +x /usr/local/bin/install-php-extensions && sync \
    && install-php-extensions pdo_mysql

# Copiar el archivo de configuración .env
COPY .env.example .env

# Generar la clave de aplicación de Laravel
RUN php artisan key:generate

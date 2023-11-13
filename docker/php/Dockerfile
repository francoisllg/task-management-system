FROM php:8.2-fpm

RUN apt-get update --allow-releaseinfo-change \
    && apt-get install -y \
    libzip-dev \
    curl \
    unzip \
    zip \
    git \
    zlib1g-dev \
    locales \
    libssl-dev \
    libsasl2-dev \
    libzstd-dev \
    libpng-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    httrack \
    libonig-dev \
    libmcrypt-dev \
    libxml++2.6-dev \
    vim \
    cron \
    curl \
    wget

# Install PHP Extensions
RUN docker-php-ext-install zip \
    && docker-php-ext-install gettext \
    &&  docker-php-ext-install gd \
    &&  docker-php-ext-install bcmath \
    &&  docker-php-ext-install soap \
    &&  docker-php-ext-install exif \
    &&  docker-php-ext-install mbstring \
    &&  docker-php-ext-install intl \
    &&  docker-php-ext-install pdo_mysql \
    &&  docker-php-ext-install mysqli \
    &&  docker-php-ext-enable mysqli

# Install locales ??
RUN sed --in-place '/en_US/s/^#//' /etc/locale.gen \
    && sed --in-place '/en_US.UTF-8/s/^#//' /etc/locale.gen \
    && sed --in-place '/ca_ES/s/^#//' /etc/locale.gen \
    && sed --in-place '/ca_ES.UTF-8/s/^#//' /etc/locale.gen \
    && sed --in-place '/de_DE/s/^#//' /etc/locale.gen \
    && sed --in-place '/de_DE.UTF-8/s/^#//' /etc/locale.gen \
    && sed --in-place '/dk_DK/s/^#//' /etc/locale.gen \
    && sed --in-place '/dk_DK.UTF-8/s/^#//' /etc/locale.gen \
    && sed --in-place '/es_ES/s/^#//' /etc/locale.gen \
    && sed --in-place '/es_ES.UTF-8/s/^#//' /etc/locale.gen \
    && sed --in-place '/fr_FR/s/^#//' /etc/locale.gen \
    && sed --in-place '/fr_FR.UTF-8/s/^#//' /etc/locale.gen \
    && sed --in-place '/gl_ES/s/^#//' /etc/locale.gen \
    && sed --in-place '/gl_ES.UTF-8/s/^#//' /etc/locale.gen \
    && sed --in-place '/it_IT/s/^#//' /etc/locale.gen \
    && sed --in-place '/it_IT.UTF-8/s/^#//' /etc/locale.gen \
    && sed --in-place '/nl_NL/s/^#//' /etc/locale.gen \
    && sed --in-place '/nl_NL.UTF-8/s/^#//' /etc/locale.gen \
    && sed --in-place '/pl_PL/s/^#//' /etc/locale.gen \
    && sed --in-place '/pl_PL.UTF-8/s/^#//' /etc/locale.gen \
    && sed --in-place '/pt_PT/s/^#//' /etc/locale.gen \
    && sed --in-place '/pt_PT.UTF-8/s/^#//' /etc/locale.gen

RUN locale-gen \
    && update-locale \
    && echo "Europe/Madrid" > /etc/timezone \
    && dpkg-reconfigure -f noninteractive tzdata

WORKDIR /usr/src/app

RUN PATH=$PATH:/usr/src/app/vendor/bin:bin

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && mv composer /usr/local/bin/composer

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

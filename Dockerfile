FROM composer:2.4 AS composer
WORKDIR /var/www/html
COPY . .

# Install ext-exif and other PHP extensions
RUN docker-php-ext-install exif

# RUN composer --no-interaction --quiet install --ignore-platform-req=ext-exif
RUN composer --no-interaction --quiet install


FROM node:18 AS vite
WORKDIR /var/www/html
COPY --from=composer /var/www/html .
RUN npm ci
RUN npm run build


FROM php:8.2-apache
WORKDIR /var/www/html
COPY --from=vite /var/www/html .
RUN apt-get -qq update && apt-get -qq --no-install-recommends install -y \
    build-essential \
    libicu-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libmcrypt-dev \
    libzip-dev \
    libpng-dev \
    zlib1g-dev \
    libonig-dev \
    libxml2-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    wget \
    gnupg \
    ffmpeg \
    libreoffice

# Install additional PHP extensions and configure them
RUN apt-get update && apt-get -qq --no-install-recommends install -y \
    imagemagick libmagickwand-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-configure gd --with-freetype=/usr/include --with-jpeg=/usr/include \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl gd pdo_mysql mbstring zip exif pcntl gd mysqli bcmath opcache fileinfo \
    && docker-php-ext-enable opcache

# Enable Apache modules
RUN a2enmod rewrite headers

# Set ownership and permissions
RUN chown -R www-data:www-data bootstrap storage \
    && php artisan storage:link

# Copy configuration files
COPY ./.docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY ./.docker/php.ini /usr/local/etc/php/conf.d/zz_custom.ini

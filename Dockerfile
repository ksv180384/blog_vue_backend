FROM php:7.4-fpm

WORKDIR /var/www/blog

RUN apt-get update -y && apt-get install -y  \
    build-essential \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev \
    libfreetype6 \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

RUN docker-php-ext-install pdo_mysql zip exif pcntl

RUN curl -sS https://getcomposer.org/installer | php --  \
    --install-dir=/usr/local/bin --filename=composer

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY . /var/www/blog

RUN chown -R www-data:www-data /var/www/blog

EXPOSE 9000

ENTRYPOINT ["entrypoint"]

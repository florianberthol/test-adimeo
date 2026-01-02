FROM dunglas/frankenphp:php8.4-trixie

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get install -y cron vim curl locales coreutils apt-utils git libxrender1 libfontconfig1 libicu-dev libjpeg-dev libfreetype6-dev libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev g++ \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen \
    && locale-gen \
    && apt-get clean

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN docker-php-ext-install pdo pdo_mysql mysqli gd opcache intl zip calendar mbstring xsl xml \
    && docker-php-ext-enable pdo pdo_mysql

COPY php.ini /usr/local/etc/php/

WORKDIR /var/www

COPY . /var/www

RUN useradd -m -u 1000 symfony && chown -R symfony:symfony /var/www

USER symfony
FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y \
        git \
        unzip \
        libzip-dev \
        libicu-dev \
        zlib1g-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev && \
    docker-php-ext-configure intl && \
    docker-php-ext-install -j$(nproc) \
        intl \
        pdo_mysql \
        zip \
        gd && \
    a2enmod rewrite


COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

COPY .env .env.local .env.local.php .env.test composer.json composer.lock package.json package-lock.json symfony.lock webpack.config.js phpunit.xml.dist ./
COPY assets/ assets/
COPY bin/ bin/
COPY config/ config/
COPY migrations/ migrations/
COPY public/ public/
COPY src/ src/
COPY templates/ templates/
COPY tests/ tests/
COPY translations/ translations/
COPY var/ var/
COPY vendor/ vendor/

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-scripts --no-autoloader

RUN composer dump-autoload --optimize

EXPOSE 80

CMD ["apache2-foreground"]

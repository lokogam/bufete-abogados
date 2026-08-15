FROM php:8.4-fpm-alpine

# Librerías runtime necesarias para las extensiones compiladas
RUN apk add --no-cache \
        libpng \
        libjpeg-turbo \
        freetype \
        libzip \
        icu \
        oniguruma

# Paquetes de desarrollo (solo para compilar las extensiones)
RUN apk add --no-cache --virtual .build-deps \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libzip-dev \
        icu-dev \
        oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        gd \
        zip \
        intl \
        mbstring \
        opcache \
    && apk del .build-deps

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction --prefer-dist --no-scripts \
    && composer dump-autoload --optimize

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 9000

CMD ["php-fpm"]
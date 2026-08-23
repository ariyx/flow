FROM php:8.4.24-cli-bookworm

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:2.10.2 /usr/bin/composer /usr/local/bin/composer

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev unzip $PHPIZE_DEPS \
    && docker-php-ext-install pdo_pgsql \
    && pecl install redis-6.2.0 \
    && docker-php-ext-enable redis \
    && apt-get purge -y --auto-remove $PHPIZE_DEPS \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY . .
RUN composer install --no-interaction --prefer-dist

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

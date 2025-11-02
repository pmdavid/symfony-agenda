FROM php:8.2-fpm

# Tools needed
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev zip libicu-dev zlib1g-dev \
 && docker-php-ext-install pdo pdo_pgsql intl zip opcache

# Install Composer (copy from official image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install node/npm if you want inside php (optional)
# WORKDIR
WORKDIR /var/www/html

# Opcional: crea user para evitar permisos root (puedes ajustar)
RUN useradd -m symfony && chown -R symfony:symfony /var/www/html
USER symfony


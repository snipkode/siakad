FROM php:8.1-apache

# Aktifkan mod_rewrite + izinkan .htaccess agar clean URL CodeIgniter berfungsi
RUN a2enmod rewrite \
    && sed -ri -e 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Ekstensi PHP yang dibutuhkan CodeIgniter 3
RUN apt-get update \
    && apt-get install -y --no-install-recommends libonig-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql mbstring \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

EXPOSE 80
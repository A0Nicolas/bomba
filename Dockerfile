FROM php:8.2-apache

# Instalar solo pdo_mysql (lo mínimo)
RUN docker-php-ext-install pdo_mysql

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Limpiar caché de apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

EXPOSE 80
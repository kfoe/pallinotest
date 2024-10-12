FROM php:8.2-fpm

# Installa dipendenze e Xdebug
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    git \
    unzip \
    curl \
    && docker-php-ext-install pdo pdo_mysql \
    && pecl install xdebug \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copia lo script di inizializzazione
COPY ./docker/php/init.sh /usr/local/bin/init.sh

# Copia il file di configurazione xdebug.ini
COPY ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Assegna i permessi allo script
RUN chmod +x /usr/local/bin/init.sh

# Comando per avviare il container
CMD ["/usr/local/bin/init.sh"]

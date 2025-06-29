FROM php:8.3-fpm

# Dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    curl \
    git \
    libc-client-dev \
    libicu-dev \
    libkrb5-dev \
    libpq-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    zip \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl pdo pdo_pgsql pgsql pdo_mysql zip xml \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap

# Installer APCu (via PECL proprement)
RUN pecl install apcu && docker-php-ext-enable apcu

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Installer une extension utile via le script mlocati (ex: http)
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions http

# Config PHP (mémoire + timezone)
RUN echo 'memory_limit = 2048M' > /usr/local/etc/php/conf.d/memlimit.ini && \
    ln -sf /usr/share/zoneinfo/Europe/Paris /etc/localtime

WORKDIR /var/www/symfony

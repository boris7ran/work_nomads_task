FROM php:8.3-fpm
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    zlib1g-dev \
    libxml2-dev \
    libpng-dev \
    libzip-dev \
    libpq-dev \
    vim curl debconf subversion git apt-transport-https apt-utils \
    build-essential locales acl mailutils wget nodejs zip unzip \
    gnupg gnupg1 gnupg2 \
    sudo \
    ssh \
    && docker-php-ext-install \
    pgsql \
    pdo \
    pdo_pgsql \
    soap \
    zip \
    opcache \
    gd \
    intl
COPY opcache.ini /usr/local/etc/php/conf.d/
COPY custom.ini /usr/local/etc/php/conf.d/
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
RUN composer self-update 2.7.7
RUN wget --no-check-certificate https://phar.phpunit.de/phpunit-6.5.3.phar && \
    mv phpunit*.phar phpunit.phar && \
    chmod +x phpunit.phar && \
    mv phpunit.phar /usr/local/bin/phpunit
WORKDIR /var/www/
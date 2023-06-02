FROM php:7.4.6-fpm

COPY composer*.json /var/www/

WORKDIR /var/www

COPY ./php.ini /usr/local/etc/php/php.ini

RUN apt-get update && apt-get install -y \
    git \
    acl \
    openssl \
    openssh-client \
    wget \
    zip \
    vim \
    curl \
    librabbitmq-dev \
    libssh-dev \
    && apt-get install -y \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    libxml2-dev \
    libicu-dev

RUN docker-php-ext-install intl pdo pdo_mysql zip gd soap bcmath sockets \
    && pecl install xdebug amqp

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/

EXPOSE 9000

CMD ["php-fpm"]

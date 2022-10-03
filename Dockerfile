FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
curl \
wget \
zip \
git

RUN apt install gnupg -y
RUN apt-get install default-mysql-client -y
RUN docker-php-ext-install pdo pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get install -y libz-dev libmemcached-dev && \
apt-get install -y memcached libmemcached-tools && \
pecl install memcached && \
docker-php-ext-enable memcached

ADD ./app /usr/src/myapp

EXPOSE 9000

WORKDIR /usr/src/myapp
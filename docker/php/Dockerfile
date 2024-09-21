FROM php:8.3-cli

RUN apt-get update &&  \
    apt-get upgrade -y && \
    apt-get install -y libbz2-dev zlib1g-dev libpng-dev libpq-dev libonig-dev libzip-dev libmagickwand-dev libmagickcore-dev curl screen
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip bz2
RUN pecl install xdebug imagic
RUN docker-php-ext-enable xdebug

RUN mkdir /root/scripts
COPY ./scripts /root/scripts
RUN bash /root/scripts/install.sh -d "${APP_XDEBUG}"

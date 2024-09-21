FROM php:8.3-fpm

RUN apt-get update &&  \
    apt-get upgrade -y && \
    apt-get install -y libbz2-dev zlib1g-dev libpng-dev libpq-dev libonig-dev libzip-dev libmagickwand-dev libmagickcore-dev curl screen
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip bz2
RUN pecl install xdebug imagic
RUN docker-php-ext-enable xdebug

RUN touch /var/log/fpm-php.www.log && chmod 666 /var/log/fpm-php.www.log

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

CMD /bin/sh -c "(cd /var/www/html && touch database/database.sqlite && chown -R $USER:www-data ./ && find ./ -type d -exec chmod 755 {} \; && find ./ -type f -exec chmod 644 {} \; && chmod -R 775 ./storage && chmod -R 775 ./bootstrap/cache && chmod 664 ./database/*.sqlite && chmod +x -R ./node_modules/* && chmod +x ./*) && ulimit -Sn 10000 && ulimit -Hn 50000 && screen -dmS events bash -c 'php artisan queue:work' && screen -dmS jobs bash -c 'php artisan schedule:work' && php-fpm"

#!/bin/bash

cd /var/www/html/ && composer install && php artisan mimgrate

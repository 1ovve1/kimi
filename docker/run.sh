#!/bin/bash

source .env

DIRS=("${STORAGE_NGINX}" "${STORAGE_FPM}" "${STORAGE_MYSQL}")

for index in ${!DIRS[*]}
do
    mkdir -p "${DIRS[index]}"
done

# init logs files for mounting
LOG_FILES=("${STORAGE_FPM}/logs/fpm-php.www.log" "${STORAGE_NGINX}/logs/access.log" "${STORAGE_NGINX}/logs/error.log")

for index in ${!LOG_FILES[*]}
do
  subject="${LOG_FILES[$index]}"
  mkdir -p "$(dirname "${subject}")" && touch "$subject" && chmod 666 "$subject"
done

docker compose --env-file=.env --env-file="${STORAGE_APP}/.env" -f ./docker-compose.yml up --build -d

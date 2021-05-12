#!/bin/bash

cd docker || exit

docker-compose up -d --build --force-recreate

docker exec app composer install -n
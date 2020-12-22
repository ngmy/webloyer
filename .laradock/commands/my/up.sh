#!/bin/bash
set -Ceuo pipefail

local NAME='my:up'
local DESCRIPTION='Launch my development environment'

handle() {
  cp -f ../.laradock/env-development .env
  docker-compose up -d nginx mysql mailhog workspace
  cp ../.env.development ../.env
  docker-compose exec workspace composer install
  docker-compose exec workspace php artisan key:generate
  docker-compose exec workspace php artisan migrate
  docker-compose exec workspace npm install
  docker-compose exec workspace npm run dev
}

#!/bin/bash
set -Ceuo pipefail

NAME='my:up'
DESCRIPTION='Launch my development environment'

handle() {
  cp -f ../.laradock/env-development .env
  docker-compose up -d nginx mysql mailhog workspace
  cp ../.env.development ../.env
  laradockctl workspace:composer install
  laradockctl laravel:artisan key:generate
  laradockctl laravel:artisan migrate
  laradockctl workspace:npm install
  laradockctl workspace:npm install
  laradockctl workspace:npm run dev
}

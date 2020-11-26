#!/bin/bash
set -Ceuo pipefail

NAME='my:up'
DESCRIPTION='Create and start containers'

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

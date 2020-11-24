#!/bin/bash
set -Ceuo pipefail

NAME='my:up'
DESCRIPTION='Create and start containers'

handle() {
  docker-compose up -d nginx mysql mailhog workspace
}

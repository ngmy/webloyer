#!/bin/bash
set -Ceuo pipefail

local NAME='my:down'
local DESCRIPTION='Shutdown my development environment'

handle() {
  docker-compose down
}

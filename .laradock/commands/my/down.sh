#!/bin/bash
set -Ceuo pipefail

local NAME='my:down'
local DESCRIPTION='Shut down my development environment'

handle() {
  docker-compose down
}

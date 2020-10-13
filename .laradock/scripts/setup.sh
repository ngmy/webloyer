#!/bin/bash

SCRIPT_DIR=$(cd $(dirname $0); pwd)

cd $SCRIPT_DIR

cp -f ./config/laradock/env ../laradock/.env
cp -f ./config/laradock/mysql/alter-user-auth.sql ../laradock/mysql/docker-entrypoint-initdb.d/alter-user-auth.sql

# update /etc/hosts
APP_DOMAINS=(\
'localstack' \
)
APP_IP=127.0.0.1
HOSTS_UPDATE_STATUS=""
for DOMAIN_HOST in ${APP_DOMAINS[@]}; do
    if [[ -z $( cat /etc/hosts | grep "${DOMAIN_HOST}" ) ]]; then
        echo Add "'${APP_IP} ${DOMAIN_HOST}'" entry to /etc/hosts
        sudo sh -c "echo ${APP_IP} ${DOMAIN_HOST} >> /etc/hosts"
        HOSTS_UPDATE_STATUS="updated"
    elif [[ -z $( cat /etc/hosts | grep "${APP_IP}.*${DOMAIN_HOST}" ) ]]; then
        echo Mod "'${APP_IP} ${DOMAIN_HOST}'" entry to /etc/hosts
        sudo sed -i -e "s/.*\(${DOMAIN_HOST}\)/${APP_IP} \1/g" /etc/hosts
        HOSTS_UPDATE_STATUS="updated"
    fi
done
[[ -z ${HOSTS_UPDATE_STATUS} ]] \
&& echo /etc/hosts is up-to-date

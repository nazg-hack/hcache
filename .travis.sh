#!/bin/bash
set -ex
apt update -y
DEBIAN_FRONTEND=noninteractive apt install -y memcached redis-server php-cli zip unzip

systemctl status memcached
systemctl start memcached
systemctl status redis-server
systemctl start redis-server

hhvm --version
php --version

(
  cd $(mktemp -d)
  curl https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
)
composer install
hh_client

hhvm ./vendor/bin/hacktest.hack tests/

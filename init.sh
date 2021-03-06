#!/usr/bin/env bash

composer install -o

php bin/console doctrine:database:create --if-not-exists --no-interaction
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console messenger:consume app.to.tip --no-interaction --time-limit=0.1 > /dev/null 2>&1

if [ "$1" == 'test' ]; then
  php bin/console doctrine:database:create --if-not-exists --no-interaction --env=test
  php bin/console doctrine:migrations:migrate --no-interaction --env=test
fi


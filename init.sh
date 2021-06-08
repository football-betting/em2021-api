#!/usr/bin/env bash

composer install

php bin/console doctrine:database:create --no-interaction
php bin/console doctrine:migrations:migrate --no-interaction

#php bin/console doctrine:database:create --no-interaction --env=test
#php bin/console doctrine:migrations:migrate --no-interaction --env=test



#!/usr/bin/env bash
 
composer install
yarn install
yarn build

php bin/console doctrine:migration:migrate --no-interaction
php bin/console doctrine:fixture:load --no-interaction
 
exec "$@"
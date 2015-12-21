#!/bin/bash

# Test if PHP is installed
php -v > /dev/null 2>&1
PHP_IS_INSTALLED=$?

[[ $PHP_IS_INSTALLED -ne 0 ]] && { printf "!!! PHP is not installed.\n    Installing Composer aborted!\n"; exit 0; }

echo ">>> Copying build-config-example to build-config.json"
cp build-config-example.json build-config.json
echo ">>> Copying composer-example to composer.json"
cp composer-example.json composer.json

# Test if Composer is installed
composer -v > /dev/null 2>&1
COMPOSER_IS_INSTALLED=$?

if [[ $COMPOSER_IS_INSTALLED -ne 0 ]]; then
  echo ">>> Installing Composer"
  curl -sS https://getcomposer.org/installer | php
  echo ">>> Installing Wordpress and Plugins with Composer!"
  php composer.phar install
else 
  echo ">>> Installing Wordpress and Plugins with Composer!"
  composer install
fi

echo ">>>> Success!"

exit 0;

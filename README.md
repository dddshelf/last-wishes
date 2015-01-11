# Last Wishes - DDD Sample Application

[![Build Status](https://secure.travis-ci.org/dddinphp/last-wishes.svg?branch=master)](http://travis-ci.org/dddinphp/last-wishes)

## Install assets
    bower install

## Set up the project
    curl -sS https://getcomposer.org/installer | php
    php composer.phar install

## Create the database schema
    php bin/doctrine orm:schema-tool:create

## Run your Last Will bounded context
    php -S localhost:8080 -t src/Lw/Infrastructure/Ui/Web/Silex/Public

## Notify all domain events via messaging
    php bin/console domain:events:spread

## Notify all domain events via messaging to another new BC deployed
    php bin/console domain:events:spread <exchange-name>

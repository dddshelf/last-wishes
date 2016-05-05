# Last Wishes - DDD Sample Application

[![Build Status](https://secure.travis-ci.org/dddinphp/last-wishes.svg?branch=master)](http://travis-ci.org/dddinphp/last-wishes)

## Mandatory requirements

* PHP
* npm

## Optional requirements

* RabbitMQ for Messaging exercises
* Elastic for Elastic exercises
* Redis optional for Redis exercises

You can install manually or use `docker-compose up -d` to run all these services inside Docker. The PHP application can be run locally.

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

## Exercises

For the DDD learners, I propose you some exercises to practice your skills.

### UserRepository running with Redis

The default project uses Doctrine and SQLite to persist users, however, we would like to easily change the persistence storage to use Redis. The PRedis dependency is already specified in the composer.json file.

### UserRepository running with MongoDB

The default project uses Doctrine and SQLite to persist users, however, we would like to easily change the persistence storage to use MongoDB.

### Log Domain Events into File and Elastic

The default project, does not log anything. We're interested in logging all DomainEvents into a file, using ad-hoc solutions or Monolog. Create a new Domain Events fired when a user tries to log in. Log also to ElasticSearch.

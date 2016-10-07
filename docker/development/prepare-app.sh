#!/usr/bin/env bash
set -e

composer install --ignore-platform-reqs
php bin/doctrine orm:schema-tool:create

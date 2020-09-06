#!/bin/bash

# Migrate the DB. Need to force to migrate on production.
php artisan migrate --force

# Start webserver.
apache2-foreground

#!/usr/bin/env bash
start=$(date +%s)

export SENTRY_AUTH_TOKEN=<AUTH_TOKEN>
export SENTRY_ORG=<ORG>
VERSION=$(sentry-cli releases propose-version)

php artisan opcache:clear
php artisan down

composer install --no-dev -o
composer dump-autoload --optimize

php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan grab:info && php artisan grab:news && php artisan grab:map && php artisan grab:status
wait

php artisan up && php artisan grab:wote && php artisan opcache:optimize
wait

sentry-cli releases new -p tmp-helper --finalize $VERSION && sentry-cli releases set-commits --auto $VERSION
wait

now=$(date +%s)
sentry-cli releases deploys $VERSION new -e production -t $((now-start))

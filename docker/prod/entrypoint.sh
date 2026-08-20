#!/usr/bin/env sh
set -e

# Ensure a .env exists (can be mounted or copied from example)
if [ ! -f "/var/www/html/.env" ]; then
  if [ -f "/var/www/html/.env.production.example" ]; then
    cp /var/www/html/.env.production.example /var/www/html/.env
  fi
fi

# Publish the assets baked into this image to the volume served by Nginx.
# Mounting that volume directly over /var/www/html/public would hide new image
# contents after the volume's first creation and leave old Vite bundles active.
if [ -d "/var/www/shared-public" ]; then
  cp -a /var/www/html/public/. /var/www/shared-public/
fi

# Ensure required folders exist and are writable by the web user (needed before running artisan)
mkdir -p /var/www/html/bootstrap/cache
mkdir -p /var/www/html/storage/framework/cache/data /var/www/html/storage/framework/sessions /var/www/html/storage/framework/views /var/www/html/storage/logs

# Keep bind-mounted storage writable by Laravel without taking directory
# ownership away from the host deploy user. Git needs write permission on the
# parent directories to replace its tracked .gitignore sentinels.
case "${DEPLOY_UID:-}" in
  ''|*[!0-9]*)
    find /var/www/html/storage -type d -exec chown www-data:www-data {} \;
    find /var/www/html/storage -type d -exec chmod 775 {} \;
    ;;
  *)
    find /var/www/html/storage -type d -exec chown "${DEPLOY_UID}:www-data" {} \;
    find /var/www/html/storage -type d -exec chmod 2775 {} \;
    ;;
esac

find /var/www/html/storage -type f ! -name '.gitignore' -exec chown www-data:www-data {} \;
find /var/www/html/storage -type f ! -name '.gitignore' -exec chmod 664 {} \;

# bootstrap/cache lives inside the container and can remain fully owned by the web user.
find /var/www/html/bootstrap/cache -type d -exec chown www-data:www-data {} \;
find /var/www/html/bootstrap/cache -type d -exec chmod 775 {} \;
find /var/www/html/bootstrap/cache -type f ! -name '.gitignore' -exec chown www-data:www-data {} \;
find /var/www/html/bootstrap/cache -type f ! -name '.gitignore' -exec chmod 664 {} \;


# Clear all caches to ensure fresh config
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
rm -f /var/www/html/bootstrap/cache/packages.php
rm -f /var/www/html/bootstrap/cache/services.php

# Run migrations if needed for scheduler/queue
# Only run migrations automatically in production or when explicitly enabled.
# This prevents CI/test runs (APP_ENV=testing) from attempting to connect to a DB.
if [ "${RUN_MIGRATIONS}" = "true" ]; then
  php artisan migrate --force
fi

if [ "$RUN_SEEDERS" = "true" ]; then
    php artisan db:seed --force
fi

# Only cache if in production mode
if [ "${APP_ENV}" = "production" ]; then
  # Don't cache config - let auto-discovery respect dont-discover
  # php artisan config:cache
  php artisan route:cache
  php artisan view:cache
fi

exec "$@"

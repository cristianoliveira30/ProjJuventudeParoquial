#!/usr/bin/env sh
set -eu

: "${DB_DATABASE:?DB_DATABASE is required}"
: "${DB_USERNAME:?DB_USERNAME is required}"
: "${DB_PASSWORD:?DB_PASSWORD is required}"

export MYSQL_DATABASE="$DB_DATABASE"
export MYSQL_USER="$DB_USERNAME"
export MYSQL_PASSWORD="$DB_PASSWORD"
export MYSQL_ROOT_PASSWORD="$DB_PASSWORD"

exec /usr/local/bin/docker-entrypoint.sh "$@"

#!/bin/bash
# setup-db.sh
# Imports the travel.sql schema and seed data into the MySQL database.
# Reads connection credentials from environment variables set by Railway's
# MySQL service reference variables: DB_HOST, DB_USER, DB_PASS, DB_NAME.

set -e

# Resolve the directory this script lives in so it works regardless of the
# working directory Railway uses when running the pre-deploy command.
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SQL_FILE="$SCRIPT_DIR/travel.sql"

# Validate required environment variables.
: "${DB_HOST:?DB_HOST is not set. Ensure the MySQL service reference variable is configured.}"
: "${DB_USER:?DB_USER is not set. Ensure the MySQL service reference variable is configured.}"
: "${DB_NAME:?DB_NAME is not set. Ensure the MySQL service reference variable is configured.}"

echo "==> Setting up database '$DB_NAME' on host '$DB_HOST'..."

# Create the database if it does not already exist, then import the schema.
# Using CREATE DATABASE IF NOT EXISTS avoids errors on repeated deployments.
# The schema uses CREATE TABLE IF NOT EXISTS, so re-running is safe.
mysql \
  --host="$DB_HOST" \
  --user="$DB_USER" \
  --password="${DB_PASS:-}" \
  --connect-timeout=30 \
  -e "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET latin1 COLLATE latin1_swedish_ci;"

echo "==> Importing travel.sql into '$DB_NAME'..."

mysql \
  --host="$DB_HOST" \
  --user="$DB_USER" \
  --password="${DB_PASS:-}" \
  --connect-timeout=30 \
  "$DB_NAME" < "$SQL_FILE"

echo "==> Database setup complete."

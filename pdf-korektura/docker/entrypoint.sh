#!/bin/sh
set -e

echo "=== PDF Korektura - Starting ==="

# Wait for PostgreSQL to be ready
echo "Waiting for PostgreSQL..."
until pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" -q; do
    sleep 1
done
echo "PostgreSQL is ready."

# Create required storage directories early (before any artisan commands)
mkdir -p /var/www/html/storage/app
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/temp
mkdir -p /var/www/html/storage/logs

# Clear file-based caches (these don't need DB)
echo "Clearing file caches..."
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan event:clear 2>/dev/null || true

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Check if database tables exist by checking for the users table
echo "Checking database state..."
TABLES_EXIST=$(PGPASSWORD="$DB_PASSWORD" psql -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" -d "$DB_DATABASE" -tAc "SELECT EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = 'users')" 2>/dev/null || echo "f")

if [ "$TABLES_EXIST" != "t" ]; then
    echo "Database tables not found. Running fresh migration with seed..."
    php artisan migrate:fresh --force --seed
else
    echo "Running migrations..."
    php artisan migrate --force
fi

# Clear DB-based caches now that tables exist
echo "Clearing application caches..."
php artisan cache:clear 2>/dev/null || true

# Seed database (only if no admin user exists)
ADMIN_EXISTS=$(php artisan tinker --execute="echo App\Models\User::where('username','admin')->exists() ? 'yes' : 'no';" 2>/dev/null || echo "no")
if [ "$ADMIN_EXISTS" = "no" ]; then
    echo "Seeding database..."
    php artisan db:seed --force
fi

# Publish Livewire JS assets to public/ for static serving
echo "Publishing Livewire assets..."
php artisan livewire:publish 2>/dev/null || true

# Create storage link
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link 2>/dev/null || true
fi

# Fix permissions
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

echo "=== PDF Korektura - Ready ==="

# Execute the main command (supervisord)
exec "$@"

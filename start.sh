#!/bin/sh

echo "Waiting for database..."

while ! php -r "
try {
    new PDO(
        'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD')
    );
    echo 'Database connected!' . PHP_EOL;
    exit(0);
} catch (Exception $e) {
    exit(1);
}
"; do
    echo "Database not ready... retrying in 3 seconds"
    sleep 3
done

echo "Running migrations..."
php artisan migrate --force --seed

echo "Starting Laravel..."
php -S 0.0.0.0:$PORT -t public
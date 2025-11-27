web: php artisan config:cache && php artisan migrate --force && php artisan storage:link && php -S 0.0.0.0:${PORT:-8080} -t public
worker: php artisan queue:work database --queue=default,emails --tries=3 --timeout=90 --sleep=3 --max-jobs=1000 --max-time=3600
scheduler: while true; do php artisan schedule:run --verbose --no-interaction & sleep 60; done

docker exec e_ordering_cron /bin/sh -c "cd /var/www/html && nohup php artisan queue:listen --tries=3 --timeout=0 >/dev/null 2>&1 &" 2>&1

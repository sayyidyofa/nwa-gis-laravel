ssh -p 22 -i ~/.ssh/id_rsa ${username}@${server} 'cd ${deploy_path} && php composer.phar install --optimize-autoloader --no-dev && php artisan migrate:fresh --seed --force && php artisan config:cache && php artisan route:cache'

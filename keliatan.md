Laravel Breeze
composer require laravel/breeze --dev
php artisan breeze:install

Laravel Breeze
php artisan storage:link

php artisan make:mode User -mcr
php artisan make:mode Member -mcr
php artisan make:mode Product -mcr
php artisan make:mode Purchase -mcr
php artisan make:mode PurchaseItem -mcr
php artisan make:mode SystemSetting -mcr
php artisan make:controller Dashboard

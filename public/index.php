<?php

// Azure Nginx রুট ফোল্ডার স্বয়ংক্রিয়ভাবে public-এ পরিবর্তন করার স্ক্রিপ্ট
if (file_exists('/etc/nginx/sites-available/default') && ! str_contains(file_get_contents('/etc/nginx/sites-available/default'), 'wwwroot/public')) {
    shell_exec("sed -i 's|root /home/site/wwwroot;|root /home/site/wwwroot/public;|g' /etc/nginx/sites-available/default && service nginx reload");
}

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());

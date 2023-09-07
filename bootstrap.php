<?php
require 'vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * setup database dependencies
 * */

use Dotenv\Dotenv;

Dotenv::createImmutable(__DIR__)->safeLoad();
$capsule = new Capsule;

$capsule->addConnection([
    'driver' => $_ENV['DB_DRIVER'],
    'host' => $_ENV['DB_HOST'],
    'database' => $_ENV["DB_NAME"],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV["DB_PASS"],
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();


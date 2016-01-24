<?php
// All file paths relative to root
chdir(dirname(__DIR__));
require 'vendor/autoload.php';

session_start();

if (file_exists('app/settings.php')) {
    $settings = require 'app/settings.php';
} else {
    $settings = require 'app/settings.php.dist';
}

$app = new \Slim\App($settings);

// disable error handler
unset($app->getContainer()['errorHandler']);

// ___________________
//               _____
// Set up routes _____
// ___________________
 $app->get('/', function($request, $response, $args) {
     echo 'Hello, world!';
 });

// $app->get('/hello[/{name}]', function ($request, $response, $args) {
//     echo "Hello, " . $args['name'];
// })->setName('hello');

// Register the routes
require 'app/src/routes.php';
// ===================



$app->run();

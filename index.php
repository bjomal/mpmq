<?php
// All file paths relative to root
chdir (dirname(__FILE__));

define ('MPMQ_ROOT', dirname(__FILE__) . '/');

require 'vendor/autoload.php';
require 'app/src/autoload.php';

session_start();


if (file_exists('app/settings.php')) {
    $settings = require 'app/settings.php';
} else {
    $settings = require 'app/settings.php.dist';
}

// Create a log object
$log = \Malmanger\Mpmq\Util\Log::getInstance(\Malmanger\Mpmq\Util\Log::DEBUG);

$log->debug("Logging started");

// initialize database
$data = $settings["settings"]["db"];

// TODO: do test on settings to confirm correct database type
$database = new \Malmanger\Mpmq\Db\SQLite($data);

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

// Register the routes
require 'app/src/routes.php';
// ===================

$app->run();

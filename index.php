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
$misc = $settings["settings"]["misc"];

$app = new \Slim\App($settings);

// disable error handler
unset($app->getContainer()['errorHandler']);

// Create or connect to DB
try {
    // TODO: do test on settings to confirm correct database type
    $database = new \Malmanger\Mpmq\Db\SQLite($data);
} catch (\Exception $e) {
    $err = new \Malmanger\Mpmq\Util\ErrorHandler();
    $resp = new Slim\Http\Response();
    $log->debug("Error connecting to database:" . $e->getMessage());
    $log->error("Unable to create or connect to database!, exiting");
    $app->respond($err->addFatal($e->getMessage())->getErrorResponse($resp));
    exit();
}

// ___________________
//               _____
// Set up routes _____
// ___________________
// $app->get('/', function($request, $response, $args) {
//     echo 'Hello, world!';
// });

// Register the routes
require 'app/src/routes.php';
// ===================

$app->run();

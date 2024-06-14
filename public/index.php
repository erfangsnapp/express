<?php

require '../vendor/autoload.php';
use App\Controllers\HealthController;
use App\Controllers\BikerController;
use App\Controllers\TripController;
use App\Controllers\VendorController;
use App\Application;

ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', 'php://stderr');
error_reporting(E_ALL);

$config = [
    'db' => [
        'servername' => 'db',
        'username' => 'root', 
        'password' => 'adminadmin123',
        'dbname' => 'express'
    ]
];
$app = new Application($config);
$router = $app->router ;

/////////////////
$router->setRoute('/health',HealthController::class, 'index');
/////////////////

$router->setRoute('/api/v1/biker/{bikerId}', BikerController::class, 'index');
$router->setRoute('/api/v1/trip/{tripId}', TripController::class, 'get');
$router->setRoute('/api/v1/trip', TripController::class, 'create');
$router->setRoute('/api/v1/trip/{tripId}/assign', TripController::class, 'assign');
$router->setRoute('/api/v1/trip/{tripId}/status', TripController::class, 'status');
$router->setRoute('/api/v1/vendor', VendorController::class, 'create');
$router->setRoute('/api/v1/vendor/{vendorId}', VendorController::class, 'get');

$router->run();


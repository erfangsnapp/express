<?php

require '../vendor/autoload.php';
use App\Controllers\HealthController;
use App\Controllers\BikerController;
use App\Controllers\TripController;
use App\Controllers\VendorController;
use App\Application;

//Setting up error logging
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

//Routes : actions are a function in the controller
$router->setRoute('/health',HealthController::class, 'GET', 'index');

$router->setRoute('/api/v1/biker/{bikerId}', BikerController::class, 'GET', 'RetrieveBikerAction');
$router->setRoute('/api/v1/biker/{bikerId}', BikerController::class, 'PUT', 'UpdateBikerLocationAction');
$router->setRoute('/api/v1/biker', BikerController::class, 'POST', 'CreateBikerAction');

$router->setRoute('/api/v1/trip/{tripId}', TripController::class, 'GET', 'RetrieveTripAction');
$router->setRoute('/api/v1/trip', TripController::class, 'POST', 'CreateTripAction');
$router->setRoute('/api/v1/trip/{tripId}/assign', TripController::class, 'POST', 'AssignBikerAction');
$router->setRoute('/api/v1/trip/{tripId}/status', TripController::class, 'PUT', 'UpdateStatusAction');

$router->setRoute('/api/v1/vendor', VendorController::class, 'POST', 'CreateVendorAction');
$router->setRoute('/api/v1/vendor/{vendorId}', VendorController::class, 'GET', 'RetrieveVendorAction');

$router->run();


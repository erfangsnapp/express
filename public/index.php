<?php

require '../vendor/autoload.php';
use App\Controllers\HealthController;
use App\Controllers\BikerController;
use App\Application;
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


$router->run();


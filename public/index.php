<?php

require '../vendor/autoload.php';
//use Controllers\AuthController;
//use Controllers\HomeController;
use App\Controllers\HealthController;
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



$router->run();


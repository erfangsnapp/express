<?php

require '../vendor/autoload.php';

use Application; 

use Controllers\AuthController;
use Controllers\HomeController;

$config = [
    'db' => [
        'servername' => 'db', 
        'username' => 'root', 
        'password' => 'adminadmin123',
        'dbname' => 'app'
    ]
];

$app = new Application($config);  
$router = $app->router ; 
$router->run();


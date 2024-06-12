<?php

namespace App\Controllers;
use App\Response;
class HealthController{
    public function index():void{
            Response::JsonResponse([
                "status" => "OK",
                "description"=> "system is healty",
            ]);
        }
};

<?php
namespace App;

class Errors{
    public static function NotFound():void{
        header('Content-type: application/json');
        http_response_code(404);
        $response = [
            "status" => "ERROR",
            "description"=> "NotFound",
        ];
        echo json_encode($response);
        die();
    }
}
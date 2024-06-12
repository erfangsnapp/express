<?php
namespace App;

class Errors{

    public static function InvalidInput(string $message):void{
        header('Content-type: application/json');
        http_response_code(400);
        $response = [
            "message" => $message
        ];
        echo json_encode($response);
        die();
    }
    public static function Unauthorized(string $message):void{
        header('Content-type: application/json');
        http_response_code(401);
        $response = [
            "message" => $message
        ];
        echo json_encode($response);
        die();
    }
    public static function Forbidden(string $message):void{
        header('Content-type: application/json');
        http_response_code(403);
        $response = [
            "message" => $message
        ];
        echo json_encode($response);
        die();
    }
    public static function NotFound(string $message):void{
        header('Content-type: application/json');
        http_response_code(404);
        $response = [
            "message" => $message
        ];
        echo json_encode($response);
        die();
    }
    public static function Conflict(string $message):void{
        header('Content-type: application/json');
        http_response_code(409);
        $response = [
            "message" => $message
        ];
        echo json_encode($response);
        die();
    }
    public static function ServerError(string $message):void{
        header('Content-type: application/json');
        http_response_code(500);
        $response = [
            "message" => $message
        ];
        echo json_encode($response);
        die();
    }
    
}
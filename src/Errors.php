<?php
namespace App;

class Errors{
    public static function BadRequest(string $message="Bad Request"):void{
        $response = [
            "message" => $message
        ];
        Response::JsonResponse($response, 400);
    }
    public static function InvalidInput(string $message="Invalid Input"):void{
        $response = [
            "message" => $message
        ];
        Response::JsonResponse($response, 400);

    }
    public static function Unauthorized(string $message="Unauthorized"):void{
        $response = [
            "message" => $message
        ];
        Response::JsonResponse($response, 401);
    }
    public static function Forbidden(string $message="Forbidden"):void{
        $response = [
            "message" => $message
        ];
        Response::JsonResponse($response, 403);
    }
    public static function NotFound(string $message="Not Found"):void{
        $response = [
            "message" => $message
        ];
        Response::JsonResponse($response, 404);
    }
    public static function Conflict(string $message="Conflict"):void{
        $response = [
            "message" => $message
        ];
        Response::JsonResponse($response, 409);
    }
    public static function ServerError(string $message="Server Error"):void{
        $response = [
            "message" => $message
        ];
        Response::JsonResponse($response, 500);
    }
}
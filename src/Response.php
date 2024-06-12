<?php

namespace App;

class Response {
    public static function JsonResponse(array $data, int $status=200){
        header('Content-type: application/json');
        http_response_code($status);
        echo json_encode($data);
        die();
    }
}
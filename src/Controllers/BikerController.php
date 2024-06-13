<?php
namespace App\Controllers;
use App\Response;
use App\Models\Biker;
use App\Field;
use App\Errors;
use App\Services\BikerService;
class BikerController{
    public function index(array $params):void{
        if ($_SERVER['REQUEST_METHOD'] === 'GET'){
            $this->get($params);
        } else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $this->put($params);
        }
    }
    public function get(array $params){
        try {
            $biker_id = $params['bikerId'];
            $id_field = new Field(Biker::$fieldRules['id'], $biker_id, Biker::class, 'id');
            $id_field->validate();
        }
        catch (\Exception $e) {
            Errors::InvalidInput($e->getMessage());
        }

        try {
            $res = BikerService::getBiker($biker_id);
            Response::JsonResponse($res);
        }
        catch (\Exception $e) {
            if ($e->getCode() == 404) {
                Errors::NotFound($e->getMessage());
            }
            else
                Errors::ServerError($e->getMessage());
        }
    }
    public function put(array $params){
        try {
            $biker_id = $params['bikerId'];
            $id_field = new Field(Biker::$fieldRules['id'], $biker_id, Biker::class, 'id');
            $id_field->validate();
        }
        catch (\Exception $e) {
            Errors::InvalidInput($e->getMessage());
        }

        try{
            $data = json_decode(file_get_contents('php://input'), true);
            $latitude = $data['latitude'];
            $longitude = $data['longitude'];
            $latitude_field = new Field(Biker::$fieldRules['latitude'], $latitude, Biker::class, 'latitude');
            $longitude_field = new Field(Biker::$fieldRules['longitude'], $longitude, Biker::class, 'longitude');
            $latitude_field->validate();
            $longitude_field->validate();
        }
        catch (\Exception $e) {
            Errors::InvalidInput($e->getMessage());
        }
        try{
            $res = BikerService::UpdateBikerLocation($biker_id, $latitude, $longitude);
        }
        catch (\Exception $e) {
            if($e->getCode() == 404){
                Errors::NotFound($e->getMessage());
            }
            else if ($e->getCode() == 400)
                Errors::InvalidInput($e->getMessage());
            else if($e->getCode() == 500)
                Errors::ServerError($e->getMessage());
        }
        Response::JsonResponse($res);
    }
}
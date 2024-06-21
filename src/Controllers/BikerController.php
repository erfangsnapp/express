<?php
namespace App\Controllers;
use App\Response;
use App\Models\Biker;
use App\Field;
use App\Errors;
use App\Services\BikerService;
class BikerController{
    public function RetrieveBikerAction(array $params){
        $biker_id = $this->tryToGetBikerIdInput($params);
        $serialized_response = $this->tryToRetrieveSerializedBiker($biker_id);
        Response::JsonResponse($serialized_response);   
    }
    private function tryToGetBikerIdInput(array $params){
        $biker_id = $params['bikerId'];
        if(!isset($biker_id)){
            Errors::BadRequest();
        }
        return $biker_id;
    }
    private function tryToRetrieveSerializedBiker($biker_id):array{
        try{
            $serialized_response = BikerService::getBiker($biker_id);
        }
        catch (\Exception $e){
            if($e->getCode() == 404){
                Errors::NotFound($e->getMessage());
            }
            else if ($e->getCode() == 400)
                Errors::InvalidInput($e->getMessage());
            else
                Errors::ServerError($e->getMessage());
        }
        return $serialized_response;
    }


    public function UpdateBikerLocationAction(array $params){
        list($biker_id, $latitude, $longitude) = $this->tryToGetBikerLocationFromInput($params);
        $this->tryToUpdateBikerLocation($biker_id, $latitude, $longitude);
    }
    private function tryToGetBikerLocationFromInput($params){
        try{
            $biker_id = $params['bikerId'];
            $data = json_decode(file_get_contents('php://input'), true);
            $latitude = $data['latitude'];
            $longitude = $data['longitude'];
        }
        catch (\Throwable $e){
            Errors::BadRequest();
        }
        return [$biker_id, $latitude, $longitude];
    }
    private function tryToUpdateBikerLocation($biker_id, $latitude, $longitude):void{
        try {
            $updated_biker_serialized = BikerService::UpdateBikerLocation($biker_id, $latitude, $longitude);
            Response::JsonResponse($updated_biker_serialized);
        } catch (\Exception $e) {
            if ($e->getCode() == 404) {
                Errors::NotFound($e->getMessage());
            } else if ($e->getCode() == 400)
                Errors::InvalidInput($e->getMessage());
            else if ($e->getCode() == 500)
                Errors::ServerError($e->getMessage());
        }
    }


    public function CreateBikerAction(){
        list($name, $latitude, $longitude) = $this->tryToGetBikerFromInput($_POST);
        $this->tryToCreateBiker($name, $latitude, $longitude);
    }
    private function tryToGetBikerFromInput($params){
        try{
            $data = json_decode(file_get_contents('php://input'), true);
            $name = $data['name'];
            $latitude = $data['latitude'];
            $longitude = $data['longitude'];
        }
        catch (\Throwable $e){
            Errors::BadRequest();
        }
        return [$name, $latitude, $longitude];
    }
    private function tryToCreateBiker($name, $latitude, $longitude):void{
        try {
            $res = BikerService::createBiker($name, $latitude, $longitude);
            Response::JsonResponse($res, 201);
        } catch (\Exception $e) {
            if ($e->getCode() == 400) {
                Errors::InvalidInput($e->getMessage());
            } else if ($e->getCode() == 500)
                Errors::ServerError($e->getMessage());
        }
    }
}
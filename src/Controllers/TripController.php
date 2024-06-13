<?php
namespace App\Controllers;
use App\Services\TripService;
use App\Response;
use App\Errors;
use App\Models\Trip;
use App\Field;
class TripController{
    public function get($params):void{
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            try {
                $trip_id = $params['tripId'];
                $res = TripService::getTrip($trip_id);
                Response::JsonResponse($res);
            } catch (\Exception $e) {
                if ($e->getCode() == 404) {
                    Errors::NotFound($e->getMessage());
                } else {
                    Errors::ServerError($e->getMessage());
                }
            }
        }
    }
    public function create():void{
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
                try{
                    $data = json_decode(file_get_contents('php://input'), true);
                    $vendor_id = $data['vendor_id'];
                    $origin_latitude = $data['origin_latitude'];
                    $origin_longitude = $data['origin_longitude'];
                    $destination_latitude = $data['destination_latitude'];
                    $destination_longitude = $data['destination_longitude'];
                    $vendor_id_field = new Field(Trip::$fieldRules['vendor_id'], $vendor_id, Trip::class, 'vendor_id');
                    $origin_latitude_field = new Field(Trip::$fieldRules['origin_latitude'], $origin_latitude, Trip::class, 'origin_latitude');
                    $origin_longitude_field = new Field(Trip::$fieldRules['origin_longitude'], $origin_longitude, Trip::class, 'origin_longitude');
                    $destination_latitude_field = new Field(Trip::$fieldRules['destination_latitude'], $destination_latitude, Trip::class, 'destination_latitude');
                    $destination_longitude_field = new Field(Trip::$fieldRules['destination_longitude'], $destination_longitude, Trip::class, 'destination_longitude');
                    $vendor_id_field->validate();
                    $origin_latitude_field->validate();
                    $origin_longitude_field->validate();
                    $destination_latitude_field->validate();
                    $destination_longitude_field->validate();
                }
                catch(\Exception $e){
                    Errors::InvalidInput($e->getMessage());
                }
                try{
                    $res = TripService::createTrip($vendor_id, $origin_latitude, $origin_longitude, $destination_latitude, $destination_longitude);
                    Response::JsonResponse($res);
                }
                catch(\Exception $e){
                    if($e->getCode() == 400){
                        Errors::InvalidInput($e->getMessage());
                    }
                    else{
                        Errors::ServerError($e->getMessage());
                    }
                }
            }
    }
    public function assign():void{
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

        }
    }
    public function status():void{
        if($_SERVER['REQUEST_METHOD'] == 'PUT'){

        }
    }
};
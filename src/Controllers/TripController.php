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
            try{
                $trip_id = $params['tripId'];
                $trip_field = new Field(Trip::$fieldRules['id'], $trip_id, Trip::class, 'id');
                $trip_field->validate();
            }
            catch (\Throwable $e) {
                Errors::InvalidInput($e->getMessage());
            }
            try {
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
                catch(\Throwable $e){
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
    public function assign($params):void{
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            try{
                $data = json_decode(file_get_contents('php://input'), true);
                $trip_id = $params['tripId'];
                $biker_id = $data['biker_id'];
                $trip_id_field = new Field(Trip::$fieldRules['id'], $trip_id, Trip::class, 'id');
                $biker_id_field = new Field(Trip::$fieldRules['biker_id'], $biker_id, Trip::class, 'biker_id');
                $trip_id_field->validate();
                $biker_id_field->validate();
            }
            catch(\Throwable $e){
                Errors::InvalidInput($e->getMessage());
            }
            try{
                $res = TripService::assignTrip($trip_id, $biker_id);
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
    public function status($params):void{
        if($_SERVER['REQUEST_METHOD'] == 'PUT'){
            try{
                $data = json_decode(file_get_contents('php://input'), true);
                $trip_id = $params['tripId'];
                $status = $data['status'];
                $trip_id_field = new Field(Trip::$fieldRules['id'], $trip_id, Trip::class, 'id');
                $status_field = new Field(Trip::$fieldRules['status'], $status, Trip::class, 'status');
                $trip_id_field->validate();
                $status_field->validate();
            }
            catch(\Throwable $e){
                Errors::InvalidInput($e->getMessage());
            }
            try{
                $res = TripService::updateStatus($trip_id, $status);
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
};
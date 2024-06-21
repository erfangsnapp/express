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
            $trip_id = $this->tryToGetTripId($params);
            $serialized_trip = $this->tryToRetrieveTrip($trip_id);
            Response::JsonResponse($serialized_trip);
        }
    }
    private function tryToGetTripId($params) {
        try{
            $trip_id = $params['tripId'];
        }
        catch (\Throwable $e) {
            Errors::InvalidInput($e->getMessage());
        }
        return $trip_id;
    }
    private function tryToRetrieveTrip($trip_id) {
        try {
            return TripService::getTrip($trip_id);
        } catch (\Exception $e) {
            if ($e->getCode() == 404) {
                Errors::NotFound($e->getMessage());
            } else {
                Errors::ServerError($e->getMessage());
            }
        }
    }

    public function create():void{
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            list($vendor_id,
                $origin_latitude,
                $origin_longitude,
                $destination_latitude,
                $destination_longitude
                ) = $this->tryToGetTripDataFromInput();
            $serialized_created_trip = $this->tryToCreateTrip(
                $vendor_id,
                $origin_latitude,
                $origin_longitude,
                $destination_latitude,
                $destination_longitude
            );
            Response::JsonResponse($serialized_created_trip, 201);
        }
    }

    private function tryToGetTripDataFromInput() {
        try{
            $data = json_decode(file_get_contents('php://input'), true);
            $vendor_id = $data['vendor_id'];
            $origin_latitude = $data['origin_latitude'];
            $origin_longitude = $data['origin_longitude'];
            $destination_latitude = $data['destination_latitude'];
            $destination_longitude = $data['destination_longitude'];
            return [$vendor_id, $origin_latitude, $origin_longitude, $destination_latitude, $destination_longitude];
        }
        catch(\Throwable $e){
            Errors::BadRequest($e->getMessage());
        }
    }

    private function tryToCreateTrip($vendor_id, $origin_latitude, $origin_longitude, $destination_latitude, $destination_longitude) {
        try{
            return TripService::createTrip($vendor_id, $origin_latitude, $origin_longitude, $destination_latitude, $destination_longitude);
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

    public function assign($params):void{
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            list($trip_id, $biker_id) = $this->tryToGetAssignDataFromInput($params);
            $serialized_assigned_trip = $this->tryToAssignTrip($trip_id, $biker_id);
            Response::JsonResponse($serialized_assigned_trip);
        }
    }

    private function tryToGetAssignDataFromInput($params) {
        try{
            $data = json_decode(file_get_contents('php://input'), true);
            $trip_id = $params['tripId'];
            $biker_id = $data['biker_id'];
            $trip_id_field = new Field(Trip::$fieldRules['id'], $trip_id, Trip::class, 'id');
            $biker_id_field = new Field(Trip::$fieldRules['biker_id'], $biker_id, Trip::class, 'biker_id');
            $trip_id_field->validate();
            $biker_id_field->validate();
            return [$trip_id, $biker_id];
        }
        catch(\Throwable $e){
            Errors::InvalidInput($e->getMessage());
        }
    }

    private function tryToAssignTrip($trip_id, $biker_id) {
        try{
            return TripService::assignTrip($trip_id, $biker_id);
        }
        catch(\Exception $e){
            if($e->getCode() == 400){
                Errors::InvalidInput($e->getMessage());
            }
            else if($e->getCode() == 409){
                Errors::Conflict($e->getMessage());
            }
            else{
                Errors::ServerError($e->getMessage());
            }
        }
    }

    public function status($params):void{
        if($_SERVER['REQUEST_METHOD'] == 'PUT'){
            list($trip_id, $status) = $this->tryToGetStatusDataFromInput($params);
            $serialized_updated_trip = $this->tryToUpdateStatus($trip_id, $status);
            Response::JsonResponse($serialized_updated_trip);
        }
    }

    private function tryToGetStatusDataFromInput($params) {
        try{
            $data = json_decode(file_get_contents('php://input'), true);
            $trip_id = $params['tripId'];
            $status = $data['status'];
            return [$trip_id, $status];
        }
        catch(\Throwable $e){
            Errors::InvalidInput($e->getMessage());
        }
    }

    private function tryToUpdateStatus($trip_id, $status) {
        try{
            return TripService::updateStatus($trip_id, $status);
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
};
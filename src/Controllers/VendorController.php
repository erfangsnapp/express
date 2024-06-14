<?php

namespace App\Controllers;
use App\Response;
use App\Services\VendorService;
use App\Models\Vendor;
use App\Errors;
use App\Field;
class VendorController{
    public function get($params):void{
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            try {
                $vendor_id = $params['vendorId'];
                $vendor_field = new Field(Vendor::$fieldRules['id'], $vendor_id, Vendor::class, 'id');
                $vendor_field->validate();
            }
            catch (\Throwable $e) {
                Errors::InvalidInput($e->getMessage());
            }
            try {

                $res = VendorService::getVendor($vendor_id);
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
                $name = $data['name'];
                $latitude = $data['latitude'];
                $longitude = $data['longitude'];
                $vendor_name_field = new Field(Vendor::$fieldRules['name'], $name, Vendor::class, 'name');
                $vendor_longitude_field = new Field(Vendor::$fieldRules['longitude'], $longitude, Vendor::class, 'longitude');
                $vendor_latitude_field = new Field(Vendor::$fieldRules['latitude'], $latitude, Vendor::class, 'latitude');
                $vendor_name_field->validate();
                $vendor_latitude_field->validate();
                $vendor_longitude_field->validate();
            }
            catch(\Throwable $e){
                Errors::InvalidInput($e->getMessage());
            }
            try{
                $res = VendorService::createVendor($name, $latitude, $longitude);
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

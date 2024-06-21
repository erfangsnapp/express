<?php

namespace App\Controllers;
use App\Response;
use App\Services\VendorService;
use App\Models\Vendor;
use App\Errors;
use App\Field;

class VendorController{
    public function RetrieveVendorAction($params):void{
        $vendor_id = $this->tryToGetVendorId($params);
        $serialized_vendor = $this->tryToGetVendor($vendor_id);
        Response::JsonResponse($serialized_vendor);
    }

    private function tryToGetVendorId($params) {
        try{
            $vendor_id = $params['vendorId'];
            return $vendor_id;
        }
        catch (\Throwable $e) {
            Errors::InvalidInput($e->getMessage());
        }
    }

    private function tryToGetVendor($vendor_id) {
        try {
            return VendorService::getVendor($vendor_id);
        }
        catch (\Exception $e) {
            if ($e->getCode() == 404) {
                Errors::NotFound($e->getMessage());
            } else {
                Errors::ServerError($e->getMessage());
            }
        }
    }

    public function CreateVendorAction():void{
        list($name, $latitude, $longitude) = $this->tryToGetVendorDataFromInput();
        $serialized_created_vendor = $this->tryToCreateVendor($name, $latitude, $longitude);
        Response::JsonResponse($serialized_created_vendor, 201);
    }

    private function tryToGetVendorDataFromInput() {
        try{
            $data = json_decode(file_get_contents('php://input'), true);
            $name = $data['name'];
            $latitude = $data['latitude'];
            $longitude = $data['longitude'];
            return [$name, $latitude, $longitude];
        }
        catch(\Throwable $e){
            Errors::InvalidInput($e->getMessage());
        }
    }

    private function tryToCreateVendor($name, $latitude, $longitude) {
        try{
            return VendorService::createVendor($name, $latitude, $longitude);
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
<?php

namespace App\Services;

use App\Models\Vendor;

class VendorService{
    public static function getVendor($vendor_id):array{
        $vendor = Vendor::getById_Or404($vendor_id);
        return $vendor->exportData();
    }
    public static function createVendor(string $name, $latitude, $longitude):array{
        $vendor = new Vendor();
        $vendor->insertData([
            'name'=> $name,
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);
        $vendor->create();
        return $vendor->exportData();
    }
}
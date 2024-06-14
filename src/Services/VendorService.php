<?php

namespace App\Services;

use App\Models\Vendor;

class VendorService{
    public static function getVendor($vendor_id):array{
        $vendor = new Vendor();
        $vendor = $vendor->getById($vendor_id);
        if ($vendor == null) {
            throw new \Exception("Vendor not found", 404);
        }
        return $vendor->exportData();
    }
    public static function createVendor(string $name, $latitude, $longitude):array{
        $vendor = new Vendor();
        try{
            $vendor->insertData([
                'name'=> $name,
                'latitude' => $latitude,
                'longitude' => $longitude
            ], Vendor::$fieldRules);
        }
        catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 400);
        }

        try {
            $vendor->create();
        }
        catch (\Exception $e) {
            throw new \Exception("Failed to create vendor", 500);
        }
        return $vendor->exportData();
    }
}
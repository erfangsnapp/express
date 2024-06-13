<?php

namespace App\Services;
use App\Models\Trip;

class TripService{
    public static function getTrip($trip_id):array{
        $trip = new Trip();
        $trip = $trip->getById($trip_id);
        if ($trip == null) {
            throw new \Exception("Trip not found", 404);
        }
        return $trip->exportData();
    }
    public static function createTrip($vendor_id, $origin_latitude, $origin_longitude, $destination_latitude, $destination_longitude):array{
        $trip = new Trip();
        try{
            $trip->insertData([
                'vendor_id' => $vendor_id,
                'origin_latitude' => $origin_latitude,
                'origin_longitude' => $origin_longitude,
                'destination_latitude' => $destination_latitude,
                'destination_longitude' => $destination_longitude,
                'status' => 'requested'
            ], Trip::$fieldRules);
        }
        catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 400);
        }

        try {
            $trip->create();
        }
        catch (\Exception $e) {
            throw new \Exception("Failed to create trip", 500);
        }
        return $trip->exportData();
    }

};
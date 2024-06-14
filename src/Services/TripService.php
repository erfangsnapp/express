<?php

namespace App\Services;
use App\Models\Trip;
use App\Models\Biker;
use App\Location;
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

    public static function assignTrip($trip_id, $biker_id):array{
        $trip = new Trip();
        $trip = $trip->getById($trip_id);
        $biker = new Biker();
        $biker = $biker->getById($biker_id);
        if ($trip == null) {
            throw new \Exception("Trip not found", 404);
        }
        if ($biker == null) {
            throw new \Exception("Biker not found", 404);
        }
        if ($trip->get_status() != 'requested') {
            throw new \Exception("Trip is not in requested status", 409);
        }
        $fromLat = $biker->get_location()['latitude'];
        $fromLong = $biker->get_location()['longitude'];
        $toLat = $trip->get_origin()['latitude'];
        $toLong = $trip->get_origin()['longitude'];
        if (Location::Distance($fromLat, $fromLong, $toLat, $toLong) > 5000) {
            throw new \Exception("Biker is too far", 409);
        }
        $trip->insertData([
            'biker_id' => $biker_id,
            'status' => 'assigned'
        ], Trip::$fieldRules);
        try {
            $trip->save();
        }
        catch (\Exception $e) {
            throw new \Exception("Failed to assign trip", 500);
        }
        return $trip->exportData();
    }

    public static function updateStatus($trip_id, $status):array{
        $trip = new Trip();
        $trip = $trip->getById($trip_id);
        if ($trip == null) {
            throw new \Exception("Trip not found", 404);
        }
        $priority = Trip::$fieldRules['status']['values'];
        if (array_search($status, $priority) < array_search($trip->get_status(), $priority)) {
            throw new \Exception("Already passed", 409);
        }
        if ($trip->get_biker_id() == null) {
            throw new \Exception("Biker is not assigned", 409);
        }
        $biker = new Biker();
        $biker = $biker->getById($trip->get_biker_id());
        if ($status == 'picked' && Location::Distance($biker->get_location()['latitude'], $biker->get_location()['longitude'], $trip->get_origin()['latitude'], $trip->get_origin()['longitude']) > 30) {
            throw new \Exception("Biker is too far from origin", 409);
        }
        if ($status == 'delivered' && Location::Distance($biker->get_location()['latitude'], $biker->get_location()['longitude'], $trip->get_destination()['latitude'], $trip->get_destination()['longitude']) > 30) {
            throw new \Exception("Biker is too far from destination", 409);
        }

        $trip->insertData([
            'status' => $status
        ], Trip::$fieldRules);
        try {
            $trip->save();
        }
        catch (\Exception $e) {
            throw new \Exception("Failed to update status", 500);
        }
        return $trip->exportData();
    }

};
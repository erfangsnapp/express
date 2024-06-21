<?php

namespace App\Services;
use App\Models\Trip;
use App\Models\Biker;
use App\Location;
class TripService{
    public static function getTrip($trip_id):array{
        $trip = Trip::getById_Or404($trip_id);
        return $trip->exportData();
    }
    public static function createTrip($vendor_id, $origin_latitude, $origin_longitude, $destination_latitude, $destination_longitude):array{
        $trip = new Trip();
        $trip->insertData([
            'vendor_id' => $vendor_id,
            'origin_latitude' => $origin_latitude,
            'origin_longitude' => $origin_longitude,
            'destination_latitude' => $destination_latitude,
            'destination_longitude' => $destination_longitude,
            'status' => 'requested'
        ]);
        $trip->create();
        return $trip->exportData();
    }

    const MAX_DISTANCE_FROM_ORIGIN_TO_START_TRIP = 5000;
    public static function assignTrip($trip_id, $biker_id):array{
        $trip = Trip::getById_Or404($trip_id);
        $biker = Biker::getById_Or404($biker_id);

        if ($trip->get_status() != 'requested') {
            throw new \Exception("Trip is not in requested status", 409);
        }

        $bikerLatitude = $biker->get_location()['latitude'];
        $bikerLongitude = $biker->get_location()['longitude'];
        $originLatitude = $trip->get_origin()['latitude'];
        $originLongitude = $trip->get_origin()['longitude'];
        if (
            Location::Distance(
                $bikerLatitude,
                $bikerLongitude,
                $originLatitude,
                $originLongitude) > self::MAX_DISTANCE_FROM_ORIGIN_TO_START_TRIP) {
            throw new \Exception("Biker is too far", 409);
        }

        $trip->insertData([
            'biker_id' => $biker_id,
            'status' => 'assigned'
        ]);
            $trip->save();
        return $trip->exportData();
    }

    const MAX_DISTANCE_FROM_CLAIMED_ORIGIN = 30;
    const MAX_DISTANCE_FROM_CLAIMED_DESTINATION = 30;
    public static function updateStatus($trip_id, $status):array{
        $trip = Trip::getById_Or404($trip_id);

        $priority = Trip::$fieldRules['status']['values'];
        if (array_search($status, $priority) < array_search($trip->get_status(), $priority)) {
            throw new \Exception("Already passed", 409);
        }

        if ($trip->get_biker_id() == null) {
            throw new \Exception("Biker is not assigned", 409);
        }

        $biker = Biker::getById_Or404($trip->get_biker_id());
        $bikerLatitude = $biker->get_location()['latitude'];
        $bikerLongitude = $biker->get_location()['longitude'];
        $originLatitude = $trip->get_origin()['latitude'];
        $originLongitude = $trip->get_origin()['longitude'];
        $destinationLatitude = $trip->get_destination()['latitude'];
        $destinationLongitude = $trip->get_destination()['longitude'];
        if (
            $status == 'picked' &&
            Location::Distance(
                $bikerLatitude,
                $bikerLongitude,
                $originLatitude,
                $originLongitude) > self::MAX_DISTANCE_FROM_CLAIMED_ORIGIN) {
            throw new \Exception("Biker is too far from origin", 409);
        }
        if (
            $status == 'delivered' &&
            Location::Distance(
                $bikerLatitude,
                $bikerLongitude,
                $destinationLatitude,
                $destinationLongitude) > self::MAX_DISTANCE_FROM_CLAIMED_DESTINATION) {
            throw new \Exception("Biker is too far from destination", 409);
        }

        $trip->insertData([
            'status' => $status
        ]);
        $trip->save();
        return $trip->exportData();
    }

};
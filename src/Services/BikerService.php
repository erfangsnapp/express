<?php
namespace App\Services;
use App\Models\Biker;
use App\Errors;
class BikerService{
    public static function getBiker($biker_id):array{
        $biker = Biker::getById($biker_id);
        if ($biker == null) {
            throw new \Exception("Biker not found", 404);
        }
        return $biker->exportData();
    }
    public static function UpdateBikerLocation($biker_id, $latitude, $longitude):array{
        $biker = Biker::getById($biker_id);
        if ($biker == null) {
           throw new \Exception("Biker not found", 404);
        }
        $biker->insertData(['latitude' => $latitude, 'longitude' => $longitude]);
        $biker->save();
        return $biker->exportData();
    }

    public static function createBiker($name, $latitude, $longitude):array{
        $biker = new Biker();
        $biker->insertData(['name' => $name, 'latitude' => $latitude, 'longitude' => $longitude]);
        $biker->create();
        return $biker->exportData();
    }
}

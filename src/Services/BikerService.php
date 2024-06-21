<?php
namespace App\Services;
use App\Models\Biker;
use App\Errors;
class BikerService{
    public static function getBiker($biker_id):array{
        $biker = Biker::getById_Or404($biker_id);
        return $biker->exportData();
    }
    public static function UpdateBikerLocation($biker_id, $latitude, $longitude):array{
        $biker = Biker::getById_Or404($biker_id);
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

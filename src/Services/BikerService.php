<?php
namespace App\Services;
use App\Models\Biker;
use App\Errors;
class BikerService{
    public static function getBiker($biker_id):array{
        $biker = new Biker();
        $biker = $biker->getById($biker_id);
        if ($biker == null) {
            throw new \Exception("Biker not found", 404);
        }
        return $biker->exportData();
    }
    public static function UpdateBikerLocation($biker_id, $latitude, $longitude):array{
        $biker = new Biker();
        $biker = $biker->getById($biker_id);
        if ($biker == null) {
           throw new \Exception("Biker not found", 404);
        }
        try{
            $biker->insertData(['latitude' => $latitude, 'longitude' => $longitude], Biker::$fieldRules);
        }
        catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 400);
        }

        try {
            $biker->save();
        }
        catch (\Exception $e) {
            throw new \Exception("Failed to update biker location", 500);
        }
        return $biker->exportData();
    }
}

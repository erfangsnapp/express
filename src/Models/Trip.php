<?php

namespace App\Models;
use App\Model;

class Trip extends Model{
    protected $id;
    protected int $vendor_id;
    protected ?int $biker_id;
    protected float $origin_latitude;
    protected float $origin_longitude;
    protected float $destination_latitude;
    protected float $destination_longitude;
    protected string $status;
    protected string $created_at;
    public static array $fieldRules = [
        'id' => ['type' => 'integer', 'min' => 0],
        'vendor_id' => ['type' => 'integer', 'min' => 0],
        'biker_id' => ['type' => 'integer', 'min' => 0, ],
        'origin_latitude' => ['type' => 'double', 'min' => -90, 'max' => 90, 'required' => true],
        'origin_longitude' => ['type' => 'double', 'min' => -180, 'max' => 180, 'required' => true],
        'destination_latitude' => ['type' => 'double', 'min' => -90, 'max' => 90, 'required' => true],
        'destination_longitude' => ['type' => 'double', 'min' => -180, 'max' => 180, 'required' => true],
        'status' => ['type' => 'enum', 'values'=>['requested','assigned','acked','picked','delivered','canceled'], 'required' => false],
        'created_at' => ['type' => 'string', 'min_length' => 0, 'max_length' => 255, 'required' => false]
    ];
    public function get_status():string{
        return $this->status;
    }
    public function get_origin():array{
        return ['latitude' => $this->origin_latitude, 'longitude' => $this->origin_longitude];
    }
    public function get_destination():array{
        return ['latitude' => $this->destination_latitude, 'longitude' => $this->destination_longitude];
    }
    public function get_vendor_id():int{
        return $this->vendor_id;
    }
    public function get_biker_id():?int{
        return $this->biker_id;
    }
};
<?php

namespace App\Models;
use App\Model;

class Biker extends Model{
    protected $id;
    protected string $name;
    protected float $latitude;
    protected float $longitude;
    protected string $updated_at;

    public static array $fieldRules = [
        'id' => ['type' => 'integer', 'min' => 0],
        'name' => ['type' => 'string', 'min_length' => 1, 'max_length' => 255, 'required' => true],
        'latitude' => ['type' => 'double', 'min' => -90, 'max' => 90, 'required' => true],
        'longitude' => ['type' => 'double', 'min' => -180, 'max' => 180, 'required' => true],
        'updated_at' => ['type' => 'string', 'min_length' => 1, 'max_length' => 255, 'required' => true]
    ];
}
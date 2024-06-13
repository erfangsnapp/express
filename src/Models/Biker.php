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
        'id' => ['type' => 'int', 'min' => 0],
        'name' => ['type' => 'string', 'min_length' => 1, 'max_length' => 255, 'required' => true],
        'latitude' => ['type' => 'float', 'min' => -90, 'max' => 90, 'required' => true],
        'longitude' => ['type' => 'float', 'min' => -180, 'max' => 180, 'required' => true],
        'updated_at' => ['type' => 'string', 'min_length' => 1, 'max_length' => 255, 'required' => true]
    ];
}
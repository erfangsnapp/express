<?php

namespace App\Models;

use App\Model;

class Vendor extends Model
{
    public static array $fieldRules = [
        'id' => ['type' => 'integer', 'min' => 0],
        'name' => ['type' => 'string', 'required' => true, 'max_length' => 50],
        'latitude' => ['type' => 'double', 'min' => -90, 'max' => 90, 'required' => true],
        'longitude' => ['type' => 'double', 'min' => -180, 'max' => 180, 'required' => true],
    ];
    protected $id;
    protected string $name;
    protected float $latitude;
    protected float $longitude;
}
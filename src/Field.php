<?php
namespace App;

class Field{
    private string $name;
    private $data;

    private bool $nullable;
    private $model; 
    private array $config;
    private string $type;
    private int $max_length=255;
    private int $min_length=0; 
    private $min;
    private $max;
    private array $values;
    private bool $required=false;
    private bool $unique=false;

    public function __construct($config, $data, $model, $name){
        /*
         * config : [type, min_length, max_length, min, max, required]
         * type : [integer, double, string, boolean, array, object, NULL, password]
         *
         */
        $this->config = $config;
        $this->data = $data;
        $this->model = $model;
        $this->name = $name; 
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }
    public function validate_type(): void {
        if ($this->required == false && $this->data == NULL)
            return;
        if ($this->type === 'integer' && (!is_numeric($this->data) || intval($this->data) != $this->data)) {
            throw new \Exception("Field {$this->name} is not an integer", 400);
        }
        if ($this->type === 'double' && (!is_numeric($this->data) || doubleval($this->data) != $this->data)) {
            throw new \Exception("Field {$this->name} is not a double", 400);
        }
        if ($this->type === 'string' && !is_string($this->data)) {
            throw new \Exception("Field {$this->name} is not a string", 400);
        }
        if ($this->type === 'boolean' && !in_array(strtolower($this->data), ['true', 'false', '1', '0', 'yes', 'no'], true)) {
            throw new \Exception("Field {$this->name} is not a boolean", 400);
        }
    }
    public function validate():void{

        $this->validate_type();

        if($this->required && $this->data == NULL)
            throw new \Exception("Field {$this->name} is required", 400);
        if($this->type == 'password'){
            if(strlen($this->data) < $this->min_length)
                throw new \Exception("Password {$this->name} is too short", 400);
        }
        if($this->type == 'string'){
            if(strlen($this->data) > $this->max_length)
                throw new \Exception("Field {$this->name} is too long", 400);
            if(strlen($this->data) < $this->min_length)
                throw new \Exception("Field {$this->name} is too short", 400);
        }
        if($this->type == 'integer' || $this->type == 'double') {
            if ($this->min && $this->data < $this->min)
                throw new \Exception("Field {$this->name} is too small", 400);
            if ($this->max && $this->data > $this->max)
                throw new \Exception("Field {$this->name} is too big", 400);
        }
        if($this->type == 'enum'){
            if(!in_array($this->data, $this->values)){
                throw new \Exception("enum value not exists", 400);
            }
        }
    }
}
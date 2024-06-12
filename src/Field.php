<?php
namespace App;

class Field{
    private string $name;
    private $data;
    private $model; 
    private array $config;
    private string $type;
    private int $max_length=255;
    private int $min_length=0; 
    private $min;
    private $max;
    private bool $required=false;
    private bool $unique=false;

    public function __construct($config, $data, $model, $name){
        $this->config = $config;
        $this->data = $data;
        $this->model = $model;
        $this->name = $name; 
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }
    public function validate():void{
        if($this->required && $this->data == NULL)
            throw new \Exception("Field {$this->name} is required");
        if($this->type == 'password'){
            if(strlen($this->data) < $this->min_length)
                throw new \Exception("Password {$this->name} is too short");
        }
        if($this->type == 'string'){
            if(strlen($this->data) > $this->max_length)
                throw new \Exception("Field {$this->name} is too long");
            if(strlen($this->data) < $this->min_length)
                throw new \Exception("Field {$this->name} is too short");
        }
        if($this->type == 'int' || $this->type == 'float'){
            if($this->min && $this->data < $this->min)
                throw new \Exception("Field {$this->name} is too small");
            if($this->max && $this->data > $this->max)
                throw new \Exception("Field {$this->name} is too big");
        }
    }
}
<?php

namespace App;

class Field{
    private string $field_name;
    private $data;
    private array $rule;
    private string $type;
    private int $max_length = 255;
    private int $min_length = 0;
    private $min;
    private $max;
    private array $values;
    private bool $required = false;

    /**
     * @param array $rule An associative array containing validation rules for the field. The keys can be:
     * - "type": The data type of the field. Possible values are "integer", "double", "string", "boolean", "array", "object", "NULL", "password".
     * - "min_length": The minimum length of the field. This is applicable for "string" and "password" types.
     * - "max_length": The maximum length of the field. This is applicable for "string" and "password" types.
     * - "min": The minimum value of the field. This is applicable for "integer" and "double" types.
     * - "max": The maximum value of the field. This is applicable for "integer" and "double" types.
     * - "required": A boolean indicating whether the field is required.
     * - "values": An array of possible values for the field. This is applicable for "enum" type.
     */
    public function __construct(array $rule, $data, $model, $field_name){
        /*
         * rule : [type, min_length, max_length, min, max, required, values]
         * type : [integer, double, string, boolean, array, object, NULL, password]
         */
        $this->rule = $rule;
        $this->data = $data;
        $this->field_name = $field_name;
        
        $implemented_rules = ["type", "min_length", "max_length", "min", "max", "required", "values"];
        
        foreach ($rule as $key => $value) {
            if (!in_array($key, $implemented_rules))
                throw new \Exception("Rule {$key} is not implemented");
            $this->$key = $value;
        }
    }

    public function validate(): void{
        if ($this->data == NULL){
            if ($this->required){
                throw new \Exception("Field {$this->field_name} is required", 400);
            }
            else{
                return;
            }
        }


        switch ($this->type) {
            case 'string':
                $this->validateString($this->data);
                break;
            case 'integer':
                $this->validateInteger($this->data);
                break;
            case 'double':
                $this->validateDouble($this->data);
                break;
            case 'boolean':
                $this->validateBoolean($this->data);
                break;
            case 'enum':
                $this->validateEnum($this->values);
                break;
            case 'password':
                $this->validatePassword($this->data);
                break;
            default:
                throw new \Exception("Field {$this->field_name} has an invalid type", 400);
        }
    }
    private function validateEnum(){
        if(!in_array($this->data, $this->values))
            throw new \Exception("enum value not exists", 400);
    }
    private function validatePassword(){
        if(strlen($this->data) < $this->min_length)
            throw new \Exception("Password {$this->field_name} is too short", 400);
    }
    private function validateString(){
        if(!is_string($this->data))
            throw new \Exception("Field {$this->field_name} is not a string", 400);
        if (strlen($this->data) > $this->max_length)
            throw new \Exception("Field {$this->field_name} is too long", 400);
        if (strlen($this->data) < $this->min_length)
            throw new \Exception("Field {$this->field_name} is too short", 400);
    }
    private function validateInteger(){
        if((!is_numeric($this->data) || intval($this->data) != $this->data))
            throw new \Exception("Field {$this->field_name} is not an integer", 400);
        if ($this->min && $this->data < $this->min)
            throw new \Exception("Field {$this->field_name} is too small", 400);
        if ($this->max && $this->data > $this->max)
            throw new \Exception("Field {$this->field_name} is too big", 400);
    }
    private function validateDouble(){
        if((!is_numeric($this->data) || doubleval($this->data) != $this->data))
            throw new \Exception("Field {$this->field_name} is not a double", 400);
        if ($this->min && $this->data < $this->min)
            throw new \Exception("Field {$this->field_name} is too small", 400);
        if ($this->max && $this->data > $this->max)
            throw new \Exception("Field {$this->field_name} is too big", 400);
    }
    private function validateBoolean(){
        if(!in_array(strtolower($this->data), ['true', 'false', '1', '0', 'yes', 'no'], true))
            throw new \Exception("Field {$this->field_name} is not a boolean", 400);
    }
}
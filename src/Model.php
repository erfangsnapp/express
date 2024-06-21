<?php
namespace App;

class Model{ 
    protected $id;

    public static array $fieldRules;
    
    private static function get_model_name():string{
        $str = get_called_class();
        return substr($str, strrpos($str, '\\') + 1);
    }
    public static function all():array{
        $rows_data = Application::$app->db->all(self::get_model_name());
        $objects_list = [];
        foreach ($rows_data as $data){
            $objects_list[] = static::makeObjectInstance($data);
        }
        return $objects_list;
    }
    public static function getById($id):?Model{
        $data = Application::$app->db->getById(self::get_model_name(), $id);
        if($data == null)
            return null;
        return static::makeObjectInstance($data);
    }
    public static function getById_Or404($id):Model{
        $data = Application::$app->db->getById(self::get_model_name(), $id);
        if($data == null)
            throw new \Exception(self::get_model_name() . " not found", 404);
        return static::makeObjectInstance($data);
    }
    public static function get($arr):?Model{
        $data = Application::$app->db->get(self::get_model_name(), $arr);
        if($data == null)
            return null;
        return static::makeObjectInstance($data);
    }
    public static function getMultiple($arr):?array{
        $object_data_list = Application::$app->db->getMultiple(self::get_model_name(), $arr);
        if(empty($object_data_list)){
            return null;
        }
        $objects_list = [];
        foreach ($object_data_list as $object_data){
            $objects_list[] = static::makeObjectInstance($object_data);
        }
        return $objects_list;
    }
    public function save():void{
        Application::$app->db->save(self::get_model_name(), $this->exportData());
    }
    public function create():void{
        $id = Application::$app->db->create(self::get_model_name(), $this->exportData());
        $this->id = $id; 
    }
    public function insertData($data):void{
        if(array_key_exists("password", $data)){
            $data['password'] = $this->hashPassword($data['password']);
        }
        $this->loadData($data);
    }
    public function loadData($data):void {
        $this->validateModelData($data);
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function exportData():?array{
        return get_object_vars($this);
    }
    private function validateModelData($data):void{
        foreach (static::$fieldRules as $field_key => $field_rule){
            $value = $data[$field_key] ?? $this->$field_key ?? null;
            $field = new Field($field_rule, $value, self::get_model_name(), $field_key);
            $field->validate();
        }
    }
    private function hashPassword($plain_password):string{
        return password_hash($plain_password, PASSWORD_DEFAULT);
    }

    public static function makeObjectInstance($data):Model{
        $entry = new static();
        $entry->loadData($data);
        return $entry;
    }
}
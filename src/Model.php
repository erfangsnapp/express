<?php



use Application; 
use Field;

class Model{ 
    protected $id;

    public static $fieldRules;

    public function __construct($data = []){
        if($data != [])
            $this->insertData($data, static::$fieldRules); 
    }
    
    private static function get_model_name(){
        $str = get_called_class();
        return substr($str, strrpos($str, '\\') + 1);
    }
    public static function all(){
        $data = Application::$app->db->all(self::get_model_name());
        $result = [];
        foreach ($data as $object){
            $entry = new static(); 
            $entry->loadData($object, static::$fieldRules);
            $result[] = $entry;
        }
        return $result; 
    }
    public static function getById($id){
        $data = Application::$app->db->getById(self::get_model_name(), $id);
        if(empty($data)){
            return null;
        }
        $entry = new static(); 
        $entry->loadData($data, static::$fieldRules); 
        return $entry; 
    }
    public static function get($arr, $multiple_result=false){
        $data = Application::$app->db->get(self::get_model_name(), $arr, $multiple_result);
        if(empty($data)){
            return null;
        }
        if(!$multiple_result){
            $entry = new static(); 
            $entry->loadData($data, static::$fieldRules); 
            return $entry;
        }
        $result = []; 
        foreach ($data as $object){
            $entry = new static(); 
            $entry->loadData($object, static::$fieldRules);
            $result[] = $entry;
        }
        return $result; 
    }
    public function read($key){
        return $this->$key;
    }
    public function save(){
        Application::$app->db->save(self::get_model_name(), $this->exportData());
    }
    public function create(){
        Application::$app->db->create(self::get_model_name(), $this->exportData());
    }
    public function loadData($data, $rules){
        foreach ($data as $key => $value) {
            if($key != 'id'){
                $field = new Field($rules[$key], $value, self::get_model_name(), $key);
                $field->validate();
            }
            $this->$key = $value;
        }
    }
    public function insertData($data, $rules){
        foreach ($data as $key => $value) {
            if($key != 'id'){
                $field = new Field($rules[$key], $value, self::get_model_name(), $key);
                $field->validate();
            }
            if($rules[$key]['type'] == 'password')
                $this->$key = password_hash($value, PASSWORD_DEFAULT);
            else
                $this->$key = $value;
        }
    }
    public function exportData(){
        return get_object_vars($this);
    }
}
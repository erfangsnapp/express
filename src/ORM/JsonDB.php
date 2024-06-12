<?php
namespace App\ORM;
class JsonDB extends Database{

    public static function db_path($table):string{
        return __DIR__ . "/../db/" . $table . ".json";
    }
    public function all($table){
        $f = file_get_contents(self::db_path($table));
        return json_decode($f, true);
    }
    public function getById($table, $id){
        $all = self::all($table);
        if(!isset($all[$id]))return NULL;
        return $all[$id];
    }
    public function get($table, $arr, $multiple_result=false){
        $all = self::all($table); 
        foreach ($all as $id => $entry){
            foreach ($arr as $key => $value){
                if($entry[$key] != $value)
                    unset($all[$id]);
            }
        }
        if($multiple_result)
            return $all; 
        return reset($all); 
    }
    public function create($table, $data){
        $all = self::all($table); 
        $id_number = (end($all)['id']+1); 
        $data['id'] = $id_number; 
        $this->save($table, $data); 
    }
    public function save($table, $data){
        $objects = self::all($table);
        $objects[$data['id']] = $data ; 
        $f = fopen(self::db_path($table), "w");
        fwrite($f, json_encode($objects));
    }
}


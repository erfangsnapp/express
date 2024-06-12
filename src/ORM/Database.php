<?php

namespace App\ORM;

abstract class Database{
    protected string $db_name;
    abstract public function save($table, $data); 
    abstract public function get($table, $arr, $multiple_result=false);
    abstract public function getById($table, $id);
    abstract public function all($table);
    abstract public function create($table, $data);
//    abstract public function delete($id);
//    abstract public function update($id, $data);
}

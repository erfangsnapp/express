<?php

namespace App\ORM;

abstract class Database{
    abstract public function save($table, $data);
    abstract public function get($table, $conditions);
    abstract public function getMultiple($table, $conditions);
    abstract public function getById($table, $id);
    abstract public function all($table);
    abstract public function create($table, $data);
}

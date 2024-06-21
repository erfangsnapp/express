<?php

namespace App\ORM;

use mysqli;

class MySQL extends Database{
    private string $servername;
    private string $username;
    private string $password;
    private string $dbname;
    private mysqli $conn;
    public function __construct($config){
        $this->servername = $config['servername'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->dbname = $config['dbname'];
    }
    public function connect():void{
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            throw new \Exception("Connection failed: " . $this->conn->connect_error);
        } 
    }

    public function save($table, $data):void{
        $primaryKey = $data['id'];
        unset($data['id']);
        $data = $this->cleanDataForMySQL($data);
        $updates = [];
        foreach ($data as $key => $value) {
            $updates[] =  "$key = '$value'";
        }
        $set = implode(', ', $updates);
        $sql = "UPDATE $table SET $set WHERE id = $primaryKey";
        if ($this->conn->query($sql) === FALSE) {
            throw new \Exception("Error in saving: " . $sql . "<br>" . $this->conn->error);
        }
    }

    public function get($table, $conditions):?array{
        $sql_conditions = [];
        foreach ($conditions as $key => $value) {
            $sql_conditions[] = "$key = '$value'";
        }
        $where = implode(' AND ', $sql_conditions);
        $sql = "SELECT * FROM $table WHERE $where";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
                $ans = $result->fetch_assoc();
                $result->free(); 
                return $ans; 
        } else {
            return null;
        }
    }

    public function getMultiple($table, $conditions):?array{
        $sql_conditions = [];
        foreach ($conditions as $key => $value) {
            $sql_conditions[] = "$key = '$value'";
        }
        $where = implode(' AND ', $sql_conditions);
        $sql = "SELECT * FROM $table WHERE $where";
        $result = $this->conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->free();
        return $data;
    }

    public function getById($table, $id):?array{
        $sql = "SELECT * FROM $table WHERE id = $id";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $ans = $result->fetch_assoc();
            $result->free(); 
            return $ans; 
        } else {
            return null;
        }
    }

    public function all($table):?array{
        $sql = "SELECT * FROM $table";
        $result = $this->conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->free();
        return $data;
    }

    public function create($table, $data):string{
        unset($data['id']);
        $data = $this->cleanDataForMySQL($data);
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        if ($this->conn->query($sql) === TRUE) {
            return (string) $this->conn->insert_id;
        } else {
            throw new \Exception("Error: " . $sql . "<br>" . $this->conn->error);
        }  
    }
    private function cleanDataForMySQL($data):array{
        $cleaned_data = $data;
        foreach ($data as $key => $value) {
            if (is_bool($value)) {
                $cleaned_data[$key] = $value ? 1 : 0;
            }
        }
        return $cleaned_data;
    }


}
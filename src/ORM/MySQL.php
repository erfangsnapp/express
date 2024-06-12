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
        $this->connect(); 
    }
    public function connect():void{
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        } 
    }

    public function save($table, $data):bool{
        $primaryKey = $data['id'];
        unset($data['id']);
        $updates = [];
        foreach ($data as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? 1 : 0;
            }
            $updates[] = "$key = '$value'";
        }
        $set = implode(', ', $updates);
        $sql = "UPDATE $table SET $set WHERE id = $primaryKey";
        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function get($table, $arr, $multiple_result=false): ?array{
        $conditions = [];
        foreach ($arr as $key => $value) {
            $conditions[] = "$key = '$value'";
        }
        $where = implode(' AND ', $conditions);
        $sql = "SELECT * FROM $table WHERE $where";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            if ($multiple_result) {
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                $result->free();
                return $data;
            } else {
                $ans = $result->fetch_assoc();
                $result->free(); 
                return $ans; 
            }
        } else {
            return null;
        }
    }

    public function getById($table, $id): ?array{
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
        if ($result->num_rows > 0) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $result->free();
            return $data;
        } else {
            return [];
        }
    }

    public function create($table, $data):bool{
        foreach ($data as $key => $value) {
            if (is_bool($value)) {
                $data[$key] = $value ? 1 : 0;
            }
        }
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }  
    }


}
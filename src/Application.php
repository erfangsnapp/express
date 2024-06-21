<?php
namespace App;
//use ORM\JsonDB;
use App\ORM\MySQL;
class Application{
    public static Application $app;
    public Router $router;
    public MySQL $db;

    public function __construct($config){ 
        $this->router = new Router(); 
        $this->db = new MySQL($config['db']);
        $this->db->connect();
        self::$app = $this;
    }
}
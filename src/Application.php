<?php



use ORM\JsonDB;
use ORM\MySQL;

class Application{
    public static Application $app;
    public Router $router;
    public $db; 

    public function __construct($config){ 
        $this->router = new Router(); 
        $this->db = new MySQL($config['db']);
        self::$app = $this;
    }
}
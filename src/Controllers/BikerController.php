<?php
namespace App\Controllers;
use App\Response;
use App\Models\Biker;
use App\Errors;
class BikerController{
    public function index(array $params):void{
        if ($_SERVER['REQUEST_METHOD'] === 'GET'){
            $this->get($params);
        } else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $this->put($params);
        }
    }
    public function get(array $params){
        $biker = new Biker();
        $res = $biker->getById($params['bikerId']);
        if ($res == null) {
            Errors::NotFound();
        }
        Response::JsonResponse($res->exportData());
    }
    public function put(array $params){
        $biker = new Biker();
        $res = $biker->getById($params['bikerId']);
        if ($res == null) {
            Errors::NotFound();
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $res->insertData($data, Biker::$fieldRules);
        $res->save();
        Response::JsonResponse($res->exportData());
    }
}
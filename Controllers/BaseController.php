<?php

namespace Controller;
use component\Validator;
use helpers\RequestHelper;
use http\Encoding\Stream\Debrotli;
use http\Params;
use Models\User;

abstract class  BaseController
{
    /*
     * in MVC Model
     *
     * Controller provide 5 methode
     *
     *          ********** handler  ************
     *
     *        -[GET/users] index() -> get list of user
     *        -[GET/users/{id}]  show(id) -> get one resource
     *        -[POST/users]  create()  -> create new resource in datastore
     *        -[PUT/users/{id}] update(id)  -> update data of some resource (match by id )
     *        -[DELETE/users/{id}]  delete(id) -> delete some resource (match by id)
     *
     *
     * */




    protected $handlerMap = [
        "GET" => "index",
        "POST" => "create",
        "PUT" => "update",
        "DELETE" => "delete"
    ];



private $validator;
public function __construct(){
    $this->validator=new Validator();
}




    public function __call($method,$arguments){


        $arguments=$arguments[0];
       $handler=(key_exists($method,$this->handlerMap))?$this->handlerMap[$method]:$method;
        if (!method_exists($this,$handler)){
            return ["message"=>"no ".$handler ." defined as handler "];
        }

         $schema=[];


        if (key_exists($handler,$this->validationSchema)){
            $schema=$this->validationSchema;
        }
        if (key_exists($handler,$schema)) {

            // validate url variables

            if (key_exists("url", $schema[$handler])) {

                $this->validator->validateUrlVariables($schema[$handler]["url"], $arguments);
            }

            //validate query params

            if (key_exists("query", $schema[$handler])) {

                $values = $_GET;
                $this->validator->validateQueryParams($schema[$handler]["query"], $values);
            }

            //validate payload data

            if (key_exists($handler,$schema) && key_exists("payload",$schema[$handler] )) {

                $this->validator->validatePayloadData($schema[$handler]["payload"], RequestHelper::getRequestPayload());
            }

        }



        return["data"=>  $this->$handler(...$arguments )];
    }
}




<?php

namespace constants;
use ReflectionClass;
class BaseConstants{
    /**
     * @return array
     */
    public  function listOfExistConstant(){
       return (new ReflectionClass($this))->getConstants();
    }
}
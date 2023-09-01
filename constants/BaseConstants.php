<?php

namespace constants;
use ReflectionClass;
class BaseConstants{

    public  function listOfExistConstant(){
       return (new ReflectionClass($this))->getConstants();
    }
}
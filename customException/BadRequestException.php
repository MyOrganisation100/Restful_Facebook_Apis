<?php

namespace customException;

use Exception;
class BadRequestException extends Exception
{
 public function __construct( $message , int $code = 422)
 {
     parent::__construct($message, $code);
 }
}
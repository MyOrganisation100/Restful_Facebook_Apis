<?php

namespace customException;
use Exception;
class SourceNotFound extends Exception
{
   public function __construct(string $message = "source not found ", int $code = 404)
   {
       parent::__construct($message, $code);
   }

}
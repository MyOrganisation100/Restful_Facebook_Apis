<?php

namespace customException;

use constants\StatusCode;
use Exception;

class BadRequestException extends BaseException
{
    protected function getMessageException($message)
    {
        return $message ?: "Bad Usage Exception";
    }

    protected function getCodeException()
    {
        return StatusCode::VALIDATION_ERROR;
    }
}
<?php

namespace customException;

use constants\StatusCode;
use Exception;

class BadRequestException extends BaseException
{
    /**
     * @param $message
     * @return string
     */
    protected function getMessageException($message)
    {
        return $message ?: "Bad Usage Exception";
    }

    /**
     * @return StatusCode int
     */
    protected function getCodeException()
    {
        return StatusCode::VALIDATION_ERROR;
    }
}
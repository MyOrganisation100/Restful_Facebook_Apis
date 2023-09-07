<?php

namespace customException;

use constants\StatusCode;

class UnAuthenticatedUserException extends BaseException
{


    protected function getMessageException($message)
    {
        return $message ?: "Wrong Credential";
    }

    protected function getCodeException()
    {
        return StatusCode::UNAUTHORIZED;
    }
}
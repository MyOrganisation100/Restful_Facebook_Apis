<?php

namespace customException;

use constants\StatusCode;

class UnAuthorizedException extends BaseException
{

    protected function getMessageException($message)
    {
        return $message?:"you are note authorized to do that.";
    }

    protected function getCodeException()
    {
        return StatusCode::FORBIDDEN;

    }
}
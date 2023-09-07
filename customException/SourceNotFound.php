<?php

namespace customException;

use constants\StatusCode;
use Exception;

class SourceNotFound extends BaseException
{

    protected function getMessageException($message = "")
    {
        return $message ?: "source not found";
    }

    protected function getCodeException()
    {
        return StatusCode::NOT_FOUND;
    }
}
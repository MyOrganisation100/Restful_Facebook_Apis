<?php

header('Content-Type:application/json; charset=utf-8');

//error handler
include_once "error_handling.php";

require 'vendor/autoload.php';

use customException\SourceNotFound;
use constants\StatusCode;
use customException\BadRequestException;

/** setup database dependencies */

require "bootstrap.php";

/** require routes for several versions */

require "routes/v1/route.php";

use component\Route;

/*$users=\Models\User::all();*/
$code = StatusCode::SUCCESS;

try {
    $response = Route::handelRequest();
    echo json_encode($response);
} catch (SourceNotFound $e) {
    echo "error : " . $e->getMessage();
    $code = StatusCode::NOT_FOUND;
} catch (BadRequestException $e) {
    echo "error : " . $e->getMessage();
    $code = StatusCode::VALIDATION_ERROR;
} catch (Exception $e) {
    // Exception handling code
    echo "Exception : " . $e->getMessage();
    $code=StatusCode::SERVER_ERROR;
}
http_response_code($code);



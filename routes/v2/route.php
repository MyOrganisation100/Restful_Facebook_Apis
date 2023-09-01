<?php


namespace routes;

use component\Route;
use Controller\UserController;

Route::setVersion(2);


Route::GET("users/{id}", UserController::class, "like");




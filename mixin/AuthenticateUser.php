<?php

namespace Mixins;

use customException\BadRequestException;
use customException\SourceNotFound;
use customException\UnAuthenticatedUserException;
use Models\User;

/* this trait should be use only in controller class*/

trait AuthenticateUser
{
/**
 * @var User $userAuthenticated
 * */
    private $userAuthenticated;

    private array $skipHandler = [];

    public function __call($method, $arguments)
    {

        $handler = (key_exists($method, $this->handlerMap)) ? $this->handlerMap[$method] : $method;

        if (in_array($handler, $this->skipHandler)) {
            return parent::__call($method, $arguments);

        }


        if (!key_exists('PHP_AUTH_USER', $_SERVER) && !key_exists('PHP_AUTH_PW', $_SERVER)) {
            throw new BadRequestException("use basic authentication with your credential");
        }
        $email = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];

        $authenticatedUser = User::query()->where('email', $email)->first();
        if (!$authenticatedUser) {
            throw new SourceNotFound("your email isn't match with any user in system ");
        }
        if ($authenticatedUser->password != md5($password)) {

            throw new UnAuthenticatedUserException();
        }
        $this->userAuthenticated = $authenticatedUser;
        return parent::__call($method, $arguments);

    }
}

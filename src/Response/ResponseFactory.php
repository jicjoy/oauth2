<?php

namespace Wolf\Authentication\Oauth2\Response;

use Psr\Container\ContainerInterface;
use Laminas\Diactoros\ResponseFactory as Base;
class ResponseFactory
{

    public function __invoke(ContainerInterface $container) {

        return new Base();
    }
}

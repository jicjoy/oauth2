<?php

namespace Wolf\Authentication\Oauth2\Repository\Factory;

use Psr\Container\ContainerInterface;
use Wolf\Authentication\Oauth2\Repository\AuthCodeRepository;

class AuthCodeRepositoryFactory
{

    public function __invoke(ContainerInterface $container) {

        return new AuthCodeRepository($container);
    }
}

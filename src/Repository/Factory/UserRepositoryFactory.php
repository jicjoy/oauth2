<?php

namespace Wolf\Authentication\Oauth2\Repository\Factory;
use Psr\Container\ContainerInterface;
use Wolf\Authentication\Oauth2\Repository\UserRepository;

class UserRepositoryFactory
{
    public function __invoke(ContainerInterface $container) {

        return new UserRepository($container);
    }
}

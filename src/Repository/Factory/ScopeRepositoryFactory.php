<?php

namespace Wolf\Authentication\Oauth2\Repository\Factory;
use Psr\Container\ContainerInterface;
use Wolf\Authentication\Oauth2\Repository\ScopeRepository;

class ScopeRepositoryFactory
{
    public function __invoke(ContainerInterface $container) {

        return new ScopeRepository($container);
    }
}

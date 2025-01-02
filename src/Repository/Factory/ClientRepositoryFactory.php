<?php

namespace Wolf\Authentication\Oauth2\Repository\Factory;
use Psr\Container\ContainerInterface;
use Wolf\Authentication\Oauth2\Repository\ClientRepository;

class ClientRepositoryFactory
{
    public function __invoke(ContainerInterface $container) {

        return new ClientRepository($container);
    }
}

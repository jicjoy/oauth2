<?php

namespace Wolf\Authentication\Oauth2\Repository\Factory;
use Psr\Container\ContainerInterface;
use Wolf\Authentication\Oauth2\Repository\RefreshTokenRepository;

class RefreshTokenRepositoryFactory
{
    public function __invoke(ContainerInterface $container) {

        return new RefreshTokenRepository($container);
    }
}

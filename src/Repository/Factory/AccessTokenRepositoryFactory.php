<?php

namespace Wolf\Authentication\Oauth2\Repository\Factory;

use Psr\Container\ContainerInterface;
use Wolf\Authentication\Oauth2\Repository\Model\Entity\AccessToken;
use Wolf\Authentication\Oauth2\Repository\AccessTokenRepository;

class AccessTokenRepositoryFactory
{

    public function __invoke(ContainerInterface $container) {

        return new AccessTokenRepository($container);
    }
}

<?php

namespace Wolf\Authentication\Oauth2\Factory;

use League\OAuth2\Server\AuthorizationServer;
use Psr\Container\ContainerInterface;
use Wolf\Authentication\Oauth2\ResponseFactoryTrait;
use Wolf\Authentication\Oauth2\AuthorizationHandler;
 
class AuthorizationHandlerFactory
{

    use ResponseFactoryTrait;

    /**
     * Undocumented function
     *
     * @param ContainerInterface $container
     * @return AuthorizationHandler
     */
    public function __invoke(ContainerInterface $container): AuthorizationHandler
    {
        return new AuthorizationHandler(
            $container->get(AuthorizationServer::class),
            $this->detectResponseFactory($container)
        );
    }
}

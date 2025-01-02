<?php

namespace Wolf\Authentication\Oauth2\Factory;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Container\ContainerInterface;
use Wolf\Authentication\Oauth2;
final class TokenEndpointHandlerFactory
{
 use Oauth2\ResponseFactoryTrait;
 public function __invoke(ContainerInterface $container): Oauth2\TokenEndpointHandler
 {
     return new Oauth2\TokenEndpointHandler(
         $container->get(AuthorizationServer::class),
         $this->detectResponseFactory($container)
     );
 }
}

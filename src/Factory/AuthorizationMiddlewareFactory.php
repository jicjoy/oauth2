<?php
declare(strict_types = 1);
namespace Wolf\Authentication\Oauth2\Factory;

use Psr\Container\ContainerInterface;
use Wolf\Authentication\Oauth2\Api\AuthenticationInterface;
use Wolf\Authentication\Oauth2\Middleware\AuthorizationMiddleware;
use Wolf\Authentication\Oauth2\ResponseFactoryTrait;
final class AuthorizationMiddlewareFactory
{
   use ResponseFactoryTrait;
   public function __invoke(ContainerInterface $containerInterface):AuthorizationMiddleware
   {
    
    return new AuthorizationMiddleware(
          auth:    $containerInterface->get(AuthenticationInterface::class)
      
      );
   }
}

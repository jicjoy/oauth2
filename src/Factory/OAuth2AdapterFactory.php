<?php

namespace Wolf\Authentication\Oauth2\Factory;
use League\OAuth2\Server\ResourceServer;
use Wolf\Authentication\Oauth2\Api\UserInterface;
use Psr\Container\ContainerInterface;
use Wolf\Authentication\Oauth2;

class OAuth2AdapterFactory
{

    use Oauth2\ResponseFactoryTrait;

    public function __invoke(ContainerInterface $container): Oauth2\OAuth2Adapter
    {
        $resourceServer = $container->has(ResourceServer::class)
            ? $container->get(ResourceServer::class)
            : null;

        if (null === $resourceServer) {
            throw new Oauth2\Exception\InvalidConfigException(
                'OAuth2 resource server is missing for authentication'
            );
        }

        return new Oauth2\OAuth2Adapter(
            $resourceServer,
            $this->detectResponseFactory($container)
        );
    }
}

<?php

namespace Wolf\Authentication\Oauth2\Factory;
use League\OAuth2\Server\ResourceServer;
use Psr\Container\ContainerInterface;
use Wolf\Authentication\Oauth2;
class ResourceServerFactory
{
    use Oauth2\CryptKeyTrait;
    use Oauth2\RepositoryTrait;

    public function __invoke(ContainerInterface $container): ResourceServer
    {
      
        $config = $container->get(\Wolf\Authentication\Oauth2\Api\ConfigInterface::class);
        $config = $config->get(Oauth2\ConfigProvider::CONFIG_PATH);
  

        if (! isset($config['public_key'])) {
            throw new Oauth2\Exception\InvalidConfigException(
                'The public_key value is missing in config authentication'
            );
        }

        $publicKey = $this->getCryptKey($config['public_key'], 'oauth2.public_key');

        return new ResourceServer(
            $this->getAccessTokenRepository($container),
            $publicKey
        );
    }

}

<?php
declare(strict_types=1);
namespace Wolf\Authentication\Oauth2\Factory;


use Psr\Container\ContainerInterface;
use DateInterval;
use League\OAuth2\Server\AuthorizationServer;
use Wolf\Authentication\Oauth2;
class AuthorizationServerFactory
{
    use Oauth2\ConfigTrait;
    use Oauth2\CryptKeyTrait;
    use Oauth2\RepositoryTrait;

    public function __invoke(ContainerInterface $container)
    {
        $clientRepository = $this->getClientRepository($container);
        $accessTokenRepository = $this->getAccessTokenRepository($container);
        $scopeRepository       = $this->getScopeRepository($container);

        $privateKey = $this->getCryptKey($this->getPrivateKey($container), 'oauth2.private_key');
        $encryptKey = $this->getEncryptionKey($container);
        $grants     = $this->getGrantsConfig($container);

        $authServer = new AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            $privateKey,
            $encryptKey
        );

        $accessTokenInterval = new DateInterval($this->getAccessTokenExpire($container));

        foreach ($grants as $grant) {
            // Config may set this grant to null. Continue on if grant has been disabled
            if (empty($grant)) {
                continue;
            }

            $authServer->enableGrantType(
                $container->get($grant),
                $accessTokenInterval
            );
        }

        // add listeners if configured
        $this->addListeners($authServer, $container);

       

        return $authServer;
    }

     /**
     * Optionally add event listeners
     */
    private function addListeners(
        AuthorizationServer $authServer,
        ContainerInterface $container
    ): void {
        $listeners = $this->getListenersConfig($container);

        foreach ($listeners as $idx => $listenerConfig) {
            $event    = $listenerConfig[0];
            $listener = $listenerConfig[1];
            $priority = $listenerConfig[2] ?? 0;
            if (is_string($listener)) {
                if (! $container->has($listener)) {
                    throw new Oauth2\Exception\InvalidConfigException(sprintf(
                        'The second element of event_listeners config at '
                            . 'index "%s" is a string and therefore expected to '
                            . 'be available as a service key in the container. '
                            . 'A service named "%s" was not found.',
                        $idx,
                        $listener
                    ));
                }
                $listener = $container->get($listener);
            }
            if (! is_int($priority)) {
                throw new Oauth2\Exception\InvalidConfigException(sprintf(
                    'The third element of event_listeners config at index "%s" (priority) '
                        . 'is expected to be an integer, received "%s"',
                    $idx,
                    $priority
                ));
            }
            $authServer->getEmitter()
                ->addListener($event, $listener, $priority);
        }
    }

    
}

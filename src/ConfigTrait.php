<?php

declare(strict_types = 1);

namespace Wolf\Authentication\Oauth2;

use Psr\Container\ContainerInterface;
use Wolf\Authentication\Oauth2\Exception\InvalidConfigException;
trait ConfigTrait
{

    /**
     * Summary of getConfigArray
     * @param \Psr\Container\ContainerInterface $container
     * @return ?array
     */
    protected function getConfigArray(ContainerInterface $container,$index = ''):array|string {
 
        $config = $container->get(\Wolf\Authentication\Oauth2\Api\ConfigInterface::class);
        $config = $config->get(ConfigProvider::CONFIG_PATH);
        return $index && isset($config[$index]) ? $config[$index] :  $config;
    }

    protected function getPrivateKey(ContainerInterface $container) {

        
        $config = $this->getConfigArray($container);

        if (! isset($config['private_key']) || empty($config['private_key'])) {
            throw new InvalidConfigException(
                'The private_key value is missing in config authentication'
            );
        }

        return $config['private_key'];
    }


    protected function getEncryptionKey(ContainerInterface $container): string
    {
        $config = $this->getConfigArray($container);

        if (! isset($config['encryption_key']) || empty($config['encryption_key'])) {
            throw new InvalidConfigException(
                'The encryption_key value is missing in config authentication'
            );
        }

        return $config['encryption_key'];
    }

    protected function getAccessTokenExpire(ContainerInterface $container): string
    {
        $config = $this->getConfigArray($container);

        if (! isset($config['access_token_expire'])) {
            throw new InvalidConfigException(
                'The access_token_expire value is missing in config authentication'
            );
        }

        return $config['access_token_expire'];
    }

    protected function getRefreshTokenExpire(ContainerInterface $container): string
    {
        $config = $this->getConfigArray($container);

        if (! isset($config['refresh_token_expire'])) {
            throw new InvalidConfigException(
                'The refresh_token_expire value is missing in config authentication'
            );
        }

        return $config['refresh_token_expire'];
    }

    protected function getAuthCodeExpire(ContainerInterface $container): string
    {
        $config = $this->getConfigArray($container);

        if (! isset($config['auth_code_expire'])) {
            throw new InvalidConfigException(
                'The auth_code_expire value is missing in config authentication'
            );
        }

        return $config['auth_code_expire'];
    }

    protected function getGrantsConfig(ContainerInterface $container): array
    {
        $config = $this->getConfigArray($container);

        if (empty($config['grants'])) {
            throw new InvalidConfigException(
                'The grants value is missing in config authentication and must be an array'
            );
        }
        if (! is_array($config['grants'])) {
            throw new InvalidConfigException(
                'The grants must be an array value'
            );
        }

        return $config['grants'];
    }
    protected function getListenersConfig(ContainerInterface $container): array
    {
        $config = $this->getConfigArray($container);

        if (empty($config['event_listeners'])) {
            return [];
        }
        if (! is_array($config['event_listeners'])) {
            throw new InvalidConfigException(
                'The event_listeners config must be an array value'
            );
        }

        return $config['event_listeners'];
    }

    protected function getListenerProvidersConfig(ContainerInterface $container): array
    {
        $config = $this->getConfigArray($container);

        if (empty($config['event_listener_providers'])) {
            return [];
        }
        if (! is_array($config['event_listener_providers'])) {
            throw new InvalidConfigException(
                'The event_listener_providers config must be an array value'
            );
        }

        return $config['event_listener_providers'];
    }
}

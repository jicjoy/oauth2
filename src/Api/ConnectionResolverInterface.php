<?php

namespace Wolf\Authentication\Oauth2\Api;

interface ConnectionResolverInterface
{
    /**
     * Get a database connection instance.
     */
    public function connection(?string $name = null): ConnectionInterface;

    /**
     * Get the default connection name.
     */
    public function getDefaultConnection(): string;

    /**
     * Set the default connection name.
     */
    public function setDefaultConnection(string $name): void;
}

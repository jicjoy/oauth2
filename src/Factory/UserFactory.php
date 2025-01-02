<?php

declare(strict_types=1);

namespace Wolf\Authentication\Oauth2\Factory;

use Psr\Container\ContainerInterface;

class UserFactory
{

    public function __invoke($identity, $roles, $details)
    {
       
        return new \Wolf\Authentication\Oauth2\DefaultUser($identity, $roles, $details);
    }
}
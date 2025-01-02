<?php

declare(strict_types=1);

namespace Wolf\Authentication\Oauth2\Repository\Factory;
use Wolf\Authentication\Oauth2\Repository\DB\PDODriver;
class EntityFactoryProvider
{

    public function __invoke($resolvedName) {

        return new $resolvedName(PDODriver::getInstance('pgsql://postgres:5432/wolf_oauth', 'postgres', 'postsql'));
    }
}

<?php

namespace Wolf\Authentication\Oauth2\Api;

interface EntityFactoryInterface
{

    public function create(array $params = []):ModelEntityInterface;
}

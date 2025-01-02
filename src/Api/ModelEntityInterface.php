<?php

declare(strict_types = 1);

namespace Wolf\Authentication\Oauth2\Api;
use Psr\Container\ContainerInterface;
interface ModelEntityInterface
{

    public function getId();

    public function find(string|int $id):static;

    public function setRawAttributes(array $attributes, bool $sync = false):static;

    public function setAttribute(string $key,mixed $value);

    public function getAttributes();

    public function getAttribute(string $key);

    public function update();

    public function save();

    public function getRevoked():?int;

  
}

<?php
declare(strict_types=1);
namespace Wolf\Authentication\Oauth2;
use Psr\Container\ContainerInterface;
use  Wolf\Authentication\Oauth2\Api\ConnectionResolverInterface;
use Wolf\Authentication\Oauth2\Api\ConnectionInterface;
trait HasContainer
{
 
   
    protected function getContainer(): ContainerInterface
    {
        return  $this->container;
    }
}

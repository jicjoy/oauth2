<?php

declare(strict_types=1);

namespace Wolf\Authentication\Oauth2\Repository\DB;
use Psr\Container\ContainerInterface;
class EntityModelFactory
{

    
    /**
     * Undocumented function
     *
     * @param ContainerInterface $container
     */
    public function __invoke(\ArrayAccess $modelProvider ): ModelEntity
    {
        return new ModelEntity(
              $modelProvider
        );
    }
}

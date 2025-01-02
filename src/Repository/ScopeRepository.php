<?php

namespace Wolf\Authentication\Oauth2\Repository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Wolf\Authentication\Oauth2\Entity\ScopeEntity;
use Wolf\Authentication\Oauth2\Repository\DB\EntityModelFactory;
class ScopeRepository extends AbstractRepository implements ScopeRepositoryInterface
{

     /**
     * @param string $identifier
     * @return ScopeEntity|void
     */
    public function getScopeEntityByIdentifier($identifier): ScopeEntityInterface
    {
       
        $entity = $this->createModelFactory()->find($identifier);
       
        $scope = new ScopeEntity();
        if (! $entity->getId()) {
            return $scope;
        }

        $scope->setIdentifier($entity->getId());
        return $scope;
    }

    public function finalizeScopes(array $scopes, string $grantType, ClientEntityInterface $clientEntity, string|null $userIdentifier = null, string|null $authCodeId = null): array {
        
        return $scopes;
    }

    protected function createModelFactory($arguments = []): \Wolf\Authentication\Oauth2\Api\ModelEntityInterface {

        if(!$this->container->has('scopeProvider')) {
            throw new \Exception('scopeProvider not found');
       }
       $model = $this->container->get('scopeProvider');
       $factory =  new EntityModelFactory();

       return $factory($model);
    }
}

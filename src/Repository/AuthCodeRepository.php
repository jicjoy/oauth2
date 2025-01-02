<?php

namespace Wolf\Authentication\Oauth2\Repository;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use Wolf\Authentication\Oauth2\Entity\AuthCodeEntity;
use Wolf\Authentication\Oauth2\Api\ModelEntityInterface;
use Wolf\Authentication\Oauth2\Repository\DB\EntityModelFactory;
class AuthCodeRepository extends AbstractRepository implements AuthCodeRepositoryInterface
{

     /**
     * {@inheritDoc}
     */
    public function getNewAuthCode():AuthCodeEntityInterface
    {
        return new AuthCodeEntity();
    }

      /**
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void {

        $this->persistNewAuthentication([
            'id' => $authCodeEntity->getIdentifier(),
            'user_id' => $authCodeEntity->getUserIdentifier(),
            'client_id' => $authCodeEntity->getClient()->getIdentifier(),
            'scopes' => $this->scopesToString($authCodeEntity->getScopes()),
            'revoked' => 0,
            'expires_at' => date(
                'Y-m-d H:i:s',
                $authCodeEntity->getExpiryDateTime()->getTimestamp()
            )
      
        ]);
      
        
    }

    public function revokeAuthCode(string $codeId): void {

        $entity = $this->createModelFactory()->find($codeId);
        if($entity->getId()) {
           $entity->setAttribute('revoked',1)->save();
            
        }
    }

    public function isAuthCodeRevoked(string $codeId): bool {

        $entity = $this->createModelFactory()->find($codeId);
        if($entity->getId()) {
          
            return (bool)$entity->getAttribute('revoked');
            
        }

        return false;
    }

    protected function createModelFactory($arguments = []): ModelEntityInterface {

        if(!$this->container->has('authCodeProvider')) {
            throw new \Exception('authCodeProvider not found');
       }
       $model = $this->container->get('authCodeProvider');
       $factory =  new EntityModelFactory();

       return $factory($model);
        
    }
}

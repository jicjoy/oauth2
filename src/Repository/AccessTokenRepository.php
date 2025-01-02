<?php
declare(strict_types = 1);

namespace Wolf\Authentication\Oauth2\Repository;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Wolf\Authentication\Oauth2\Api\ModelEntityInterface;
use Wolf\Authentication\Oauth2\Repository\DB\EntityModelFactory;
use Wolf\Authentication\Oauth2\Entity\AccessTokenEntity;

class AccessTokenRepository extends AbstractRepository implements AccessTokenRepositoryInterface
{


    /**
     * Create a new access token
     *
     * @param ScopeEntityInterface[] $scopes
     */
    public function getNewToken(
        ClientEntityInterface $clientEntity,
        array $scopes,
        string|null $userIdentifier = null
    ): AccessTokenEntityInterface {
      $accessTokenEntity = new AccessTokenEntity();
      $accessTokenEntity->setClient($clientEntity);
       foreach($scopes as $scope) {
          $accessTokenEntity->addScope($scope);
       }

       $accessTokenEntity->setUserIdentifier((string)$userIdentifier );

      return $accessTokenEntity;
    }

    /**
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void {

        $data = [
           'id' => $accessTokenEntity->getIdentifier(),
           'user_id' => $accessTokenEntity->getUserIdentifier(),
           'client_id' => $accessTokenEntity->getClient()->getIdentifier(),
           'scopes'     => $this->scopesToString($accessTokenEntity->getScopes()),
           'revoked'  => 0,
           'expires_at' => date(
                'Y-m-d H:i:s',
                $accessTokenEntity->getExpiryDateTime()->getTimestamp()
            )
        ];
    
        $this->persistNewAuthentication($data);
    }

    public function revokeAccessToken(string $tokenId): void {

      $this->revokedToken($tokenId);

    }

    /**
     * 
     * 
     * @param string $tokenId
     * @return bool
     */
    public function isAccessTokenRevoked(string $tokenId): bool {

        $model = $this->createModelFactory()->find($tokenId);
        return (bool)$model->getAttribute('revoked');
       
    }
    

    /**
     * Undocumented function
     *
     * @param array $arguments
     * @return ModelEntityInterface
     */
    protected function createModelFactory($arguments = []): ModelEntityInterface {
     
        if(!$this->container->has('accessTokenProvider')) {
             throw new \Exception('AccessTokenProvider not found');
        }
        $model = $this->container->get('accessTokenProvider');
        $factory =  new EntityModelFactory();

        return $factory($model);
         
    }
   
   
}

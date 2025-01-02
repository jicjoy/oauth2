<?php

namespace Wolf\Authentication\Oauth2\Repository;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Wolf\Authentication\Oauth2\Entity\RefreshTokenEntity;
use Wolf\Authentication\Oauth2\Repository\DB\EntityModelFactory;
class RefreshTokenRepository extends AbstractRepository implements RefreshTokenRepositoryInterface
{

    public function getNewRefreshToken(): ?RefreshTokenEntityInterface {

        return new RefreshTokenEntity();
    }

     
    /**
     * 
     * 
     * @param \League\OAuth2\Server\Entities\RefreshTokenEntityInterface $refreshTokenEntity
     * @return void
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void {
        var_dump($refreshTokenEntity);
           $this->persistNewAuthentication([
              'id' => $refreshTokenEntity->getIdentifier(),
            
              'access_token_id' => $refreshTokenEntity->getAccessToken()->getIdentifier(),
              'revoked'         => 0,
              'expires_at'      =>  date(
                'Y-m-d H:i:s',
                $refreshTokenEntity->getExpiryDateTime()->getTimestamp()
            )
           ]); 
    }

    /**
     * 
     * 
     * @param string $tokenId
     * @return void
     */
    public function revokeRefreshToken(string $tokenId): void {
        $this->revokedToken($tokenId);
    }

    /**
     * 
     * 
     * @param string $tokenId
     * @return bool
     */
    public function isRefreshTokenRevoked(string $tokenId): bool {

        return $this->isTokenRevoked($tokenId);
    }

    protected function createModelFactory($arguments = []): \Wolf\Authentication\Oauth2\Api\ModelEntityInterface {

        if(!$this->container->has('refreshToekenProvider')) {
            throw new \Exception('refreshToekenProvider not found');
       }
       $model = $this->container->get('refreshToekenProvider');
       $factory =  new EntityModelFactory();

       return $factory($model);
    }
}

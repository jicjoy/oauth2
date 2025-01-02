<?php

namespace Wolf\Authentication\Oauth2\Repository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Wolf\Authentication\Oauth2\Entity\UserEntity;
use function password_verify;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Entities\UserEntityInterface;
use Wolf\Authentication\Oauth2\Repository\DB\EntityModelFactory;
class UserRepository extends AbstractRepository implements UserRepositoryInterface
{

     /**
     * Get a user entity.
     */
    public function getUserEntityByUserCredentials(
        string $username,
        string $password,
        string $grantType,
        ClientEntityInterface $clientEntity
    ): ?UserEntityInterface {
      return new UserEntity($username);
        $entity =  $this->createModelFactory()->find($username);
       if(!$entity->getId()) throw new OAuthServerException('User Entity exsit',400,'auth_fiald');


      if(!password_verify($password,$entity->getData('password_hash'))) {
        throw new OAuthServerException('User Entity exsit',400,'auth_fiald');
      }
 
      return new UserEntity($username);
    }

    protected function createModelFactory($arguments = []): \Wolf\Authentication\Oauth2\Api\ModelEntityInterface {

      if(!$this->container->has('usersProvider')) {
        throw new \Exception('usersProvider not found');
      }
      $model = $this->container->get('usersProvider');
      $factory =  new EntityModelFactory();

      return $factory($model);
  }
}

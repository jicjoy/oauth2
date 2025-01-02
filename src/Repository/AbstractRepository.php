<?php

namespace Wolf\Authentication\Oauth2\Repository;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
 
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use Psr\Container\ContainerInterface;
use Wolf\Authentication\Oauth2\Api\ModelEntityInterface;
 
use Wolf\Authentication\Oauth2\HasContainer;
 
use League\OAuth2\Server\Exception\OAuthServerException;
use Wolf\Authentication\Oauth2\ConfigProvider;

abstract class AbstractRepository
{


    /**
     * 
     * 
     * @var ContainerInterface
     */
    protected $container;
   
    public function __construct(ContainerInterface $container) {

        $this->container     = $container;
      
    }

      /**
     * Return a string of scopes, separated by space
     * from ScopeEntityInterface[]
     *
     * @param ScopeEntityInterface[] $scopes
     */
    protected function scopesToString(array $scopes): string
    {
        if (empty($scopes)) {
            return '';
        }

        return trim(array_reduce($scopes, static fn($result, $item): string => $result . ' ' . $item->getIdentifier()));
    }



    protected function revokedToken(string $tokenId): void {
    
        try{
        
            $this->createModelFactory()->find($tokenId)->setRawAttributes([
                'revoked' => 1
            ])->save();
            
   
        }catch(\Exception $e) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }
        
    }

    protected function persistNewAuthentication(array $data) {
        try{
      
            $this->createModelFactory()->setRawAttributes($data)->save();
            
   
        }catch(\Exception $e) {
 
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }
        
    }

    protected function isTokenRevoked(string $tokenId): bool {

        $entity = $this->createModelFactory()->find($tokenId);
        if($entity->getId()) {
            return (bool)$entity->getRevoked();
        }

        return false;
    }

     protected function createModelFactory($arguments = []):ModelEntityInterface {
          throw new OAuthServerException('the model miss',400,'auth code');
    }
 
    protected function make($resolvedName,$arguments = []) {


        if(!$this->container->has(\Wolf\Authentication\Oauth2\Api\ConfigInterface::class)) {
             throw new OAuthServerException(sprintf("Not found the model Entity %s.",$resolvedName),400,'auth Code');
        }
        
        $config = $this->container->get(\Wolf\Authentication\Oauth2\Api\ConfigInterface::class);
        $entityResolverdName = $config->get(ConfigProvider::CONFIG_PATH)['entity'][$resolvedName];
          
     
       return   $this->container->make($entityResolverdName,$arguments);
        
    }

    
}

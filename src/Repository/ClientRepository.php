<?php

namespace Wolf\Authentication\Oauth2\Repository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Wolf\Authentication\Oauth2\Entity\ClientEntity;
use Wolf\Authentication\Oauth2\Repository\DB\EntityModelFactory;
class ClientRepository extends AbstractRepository implements ClientRepositoryInterface
{

    /**
     * {@inheritDoc}
     */
    public function getClientEntity($clientIdentifier): ?ClientEntityInterface
    {
        $clientData = $this->getClientData($clientIdentifier);

        if ($clientData === null || $clientData === []) {
            return null;
        }

        return new ClientEntity(
            $clientIdentifier,
            $clientData['name'] ?? '',
            $clientData['redirect'] ?? '',
            (bool) ($clientData['is_confidential'] ?? null)
        );
    }
    /**
     * {@inheritDoc}
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        $clientData = $this->getClientData($clientIdentifier);

        if ($clientData === null || $clientData === []) {
            return false;
        }

 
        if (! $this->isGranted($clientData, $grantType)) {
             
            return false;
        }
    
        if (empty($clientData['secret']) || ! password_verify((string) $clientSecret, $clientData['secret'])) {
            return false;
        }

        return true;
    }

    /**
     * Check the grantType for the client value, stored in $row
     */
    protected function isGranted(array $row, ?string $grantType = null): bool
    {
        return match ($grantType) {
            'authorization_code' => true,
            'personal_access' => (bool) $row['personal_access_client'],
          //  'password' => (bool) $row['password_client'],
            default => true,
        };
    }

    private function getClientData(string $clientIdentifier): array {

        return $this->createModelFactory()->find($clientIdentifier)->getAttributes();
    }

    protected function createModelFactory($arguments = []): \Wolf\Authentication\Oauth2\Api\ModelEntityInterface {

        if(!$this->container->has('clientProvider')) {
            throw new \Exception('authCodeProvider not found');
       }
       $model = $this->container->get('clientProvider');
       $factory =  new EntityModelFactory();

       return $factory($model);
    }
}

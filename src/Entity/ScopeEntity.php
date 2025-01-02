<?php

namespace Wolf\Authentication\Oauth2\Entity;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use ReturnTypeWillChange;

class ScopeEntity implements ScopeEntityInterface
{

    use EntityTrait;
    
    /**
     * @return mixed
     */
    #[ReturnTypeWillChange]
    public function jsonSerialize() : string {
        return $this->getIdentifier();
    }
}

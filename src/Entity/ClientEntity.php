<?php
declare(strict_types=1);

namespace Wolf\Authentication\Oauth2\Entity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
class ClientEntity implements ClientEntityInterface
{

    use ClientTrait;
    use EntityTrait;
    use RevokableTrait;
    use TimestampableTrait;

    /** @var string */
    protected $secret;

    /** @var bool */
    protected $personalAccessClient;

    /** @var bool */
    protected $passwordClient;

    protected $user_id = null;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(string $identifier, string $name, string $redirectUri, bool $isConfidential = false,$user_id = null)
    {
        $this->setIdentifier($identifier);
        $this->name           = $name;
        $this->redirectUri    = explode(',', $redirectUri);
        $this->isConfidential = $isConfidential;
        $this->user_id        = $user_id;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }

    public function hasPersonalAccessClient(): bool
    {
        return $this->personalAccessClient;
    }

    public function setPersonalAccessClient(bool $personalAccessClient): void
    {
        $this->personalAccessClient = $personalAccessClient;
    }

    public function hasPasswordClient(): bool
    {
        return $this->passwordClient;
    }

    public function setPasswordClient(bool $passwordClient): void
    {
        $this->passwordClient = $passwordClient;
    }

    public function getUserId() {
         return $this->user_id;
    }
}

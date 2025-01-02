<?php

namespace Wolf\Authentication\Oauth2;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Wolf\Authentication\Oauth2\Api\UserInterface;
use Wolf\Authentication\Oauth2\Api\AuthenticationInterface;
use Wolf\Authentication\Oauth2\Response\CallableResponseFactoryDecorator;
class OAuth2Adapter implements AuthenticationInterface
{

    /** @var ResourceServer */
    protected $resourceServer;

    /** @var (callable():ResponseInterface)|ResponseFactoryInterface */
    protected $responseFactory;

    /** @var callable */
    protected $userFactory;

    /**
     * @param (callable():ResponseInterface)|ResponseFactoryInterface $responseFactory
     */
    public function __construct(
        ResourceServer $resourceServer,
        $responseFactory,
        callable $userFactory
    ) {
        $this->resourceServer = $resourceServer;

        if (is_callable($responseFactory)) {
            $responseFactory = new CallableResponseFactoryDecorator(
                static fn(): ResponseInterface => $responseFactory()
            );
        }

        $this->responseFactory = $responseFactory;
        $this->userFactory     = static fn(string $identity, array $roles = [], array $details = []): UserInterface
                =>   $userFactory($identity, $roles, $details);
    }

    public function authenticate(ServerRequestInterface $serverRequest): ?UserInterface {

        try {
            $result   = $this->resourceServer->validateAuthenticatedRequest($serverRequest);
          
            $userId   = $result->getAttribute('oauth_user_id', null);
            $clientId = $result->getAttribute('oauth_client_id', null);
  
            if (isset($userId)) {
          
                return ($this->userFactory)(
                    $userId,
                    [],
                    [
                        'oauth_user_id'         => $userId,
                        'oauth_client_id'       => $clientId,
                        'oauth_access_token_id' => $result->getAttribute('oauth_access_token_id', null),
                        'oauth_scopes'          => $result->getAttribute('oauth_scopes', null),
                    ]
                );
            }
        } catch (OAuthServerException) {
            return null;
        }
        return null;

    }

    public function unauthorizedResponse(ServerRequestInterface $request): ResponseInterface {

        return $this->responseFactory
        ->createResponse(401)
        ->withHeader(
            'WWW-Authenticate',
            'Bearer realm="OAuth2 token"'
        );
    }
}

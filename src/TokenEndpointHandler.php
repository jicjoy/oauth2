<?php

namespace Wolf\Authentication\Oauth2;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Wolf\Authentication\OAuth2\Response\CallableResponseFactoryDecorator;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
class TokenEndpointHandler implements RequestHandlerInterface
{

    /** @var AuthorizationServer */
    protected $server;

    /** @var ResponseFactoryInterface */
    protected $responseFactory;

    /**
     * @param (callable():ResponseInterface)|ResponseFactoryInterface $responseFactory
     */
    public function __construct(AuthorizationServer $server, $responseFactory)
    {
        $this->server = $server;
        if (is_callable($responseFactory)) {
            $responseFactory = new CallableResponseFactoryDecorator(
                static fn(): ResponseInterface => $responseFactory()
            );
        }
        $this->responseFactory = $responseFactory;
    }

    private function createResponse(): ResponseInterface
    {
        return $this->responseFactory->createResponse();
    }

    /**
     * 
     * Request an access token
     * Used for client credential grant, password grant, and refresh token grant
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->createResponse();

        try {
            
            return $this->server->respondToAccessTokenRequest($request, $response);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
        }
    }

}

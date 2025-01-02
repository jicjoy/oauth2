<?php
declare(strict_types = 1);

namespace Wolf\Authentication\Oauth2;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Wolf\Authentication\Oauth2\Response\CallableResponseFactoryDecorator;
class AuthorizationHandler implements RequestHandlerInterface
{
    private readonly ResponseFactoryInterface $responseFactory;

     /**
     * @param (callable():ResponseInterface)|ResponseFactoryInterface $responseFactory
     */
    public function __construct(private readonly AuthorizationServer $sever , $responseFactory)
    {
            if (is_callable($responseFactory)) {
            $responseFactory = new CallableResponseFactoryDecorator(
                static fn(): ResponseInterface => $responseFactory()
            );
        }

        $this->responseFactory = $responseFactory;
    }

    /**
     * Undocumented function
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $authRequest = $request->getAttribute(AuthorizationRequest::class);
      
        return $this->sever->completeAuthorizationRequest(
            $authRequest,
            $this->responseFactory->createResponse()
        );
    }
}

<?php
declare(strict_types=1);

namespace Wolf\Authentication\Oauth2\Api;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
interface  AuthenticationInterface
{

    public function authenticate(ServerRequestInterface $serverRequest):?UserInterface;
     /**
     * Generate the unauthorized response
     */
    public function unauthorizedResponse(ServerRequestInterface $request): ResponseInterface;
}
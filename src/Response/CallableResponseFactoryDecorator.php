<?php
declare(strict_types=1);

namespace Wolf\Authentication\Oauth2\Response;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
class CallableResponseFactoryDecorator implements ResponseFactoryInterface
{

      /** @var callable():ResponseInterface */
      private $responseFactory;

      /**
       * @param callable():ResponseInterface $responseFactory
       */
      public function __construct(callable $responseFactory)
      {
          $this->responseFactory = $responseFactory;
      }
  
      /**
       * Undocumented function
       *
       * @param integer $code
       * @param string $reasonPhrase
       * @return ResponseInterface
       */
      public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
      {
          return $this->getResponseFromCallable()->withStatus($code, $reasonPhrase);
      }
  
      /**
       * Undocumented function
       *
       * @return ResponseInterface
       */
      public function getResponseFromCallable(): ResponseInterface
      {
          return ($this->responseFactory)();
      }
}

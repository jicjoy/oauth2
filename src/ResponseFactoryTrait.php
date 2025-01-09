<?php
declare(strict_types=1);

namespace Wolf\Authentication\Oauth2;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Wolf\Authentication\Oauth2\Response\CallableResponseFactoryDecorator;
trait ResponseFactoryTrait
{

    /**
     * Undocumented function
     *
     * @param ContainerInterface $container
     * @return ResponseFactoryInterface
     */
    private function detectResponseFactory(ContainerInterface $container):ResponseFactoryInterface {
         $factoryAvailabe = $container->has(ResponseFactoryInterface::class);
         if(!$factoryAvailabe) {

            return $this->createResponseFactoryFromDeprecatedCallable($container);
         }

         $responseFactory = $container->get(ResponseFactoryInterface::class);
  
         //Assert::isInstanceOf($responseFactory, ResponseFactoryInterface::class);
         return $responseFactory;

    }

    /**
     * Undocumented function
     *
     * @param ContainerInterface $container
     * @return ResponseFactoryInterface
     */
    private function createResponseFactoryFromDeprecatedCallable(
        ContainerInterface $container
    ): ResponseFactoryInterface {
        /** @var callable():ResponseInterface $responseFactory */
        $responseFactory = $container->get(ResponseInterface::class);

    
        return new CallableResponseFactoryDecorator($responseFactory);
    }
}

<?php
namespace Wasm\ApiBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Wasm\ApiBundle\Factory\ApiResponseFactory;
use Wasm\ApiBundle\Exception\ApiProblemException;
use Wasm\ApiBundle\Util\ApiProblem;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    private $isDebug;
    private $responseFactory;

    public function __construct($isDebug, ApiResponseFactory $responseFactory)
    {
        $this->isDebug = $isDebug;
        $this->responseFactory = $responseFactory;
    }

    public static function getSubscribedEvents()
    {
       return array(
            KernelEvents::EXCEPTION => 'onKernelException'
       );
    }

    // @todo -> Log errors on production
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if(strpos($event->getRequest()->getPathInfo(), '/api') === false)
            return;

        $e = $event->getException();
        if($e instanceof ApiProblemException)
            $apiProblem = $e->getApiProblem();
        else {
            if($e instanceof HttpExceptionInterface)
                $statusCode = $e->getStatusCode();
            else 
                $statusCode = 500;

            $apiProblem = new ApiProblem($statusCode);
        }

        // Catch 404/403 reasons -> like 'Model not found'.
        // Deep exceptions(Like Db $e) will not be exposed.
        if($e instanceof HttpExceptionInterface) 
            $apiProblem->set('detail', $e->getMessage());

        $response = $this->responseFactory->createProblemResponse($apiProblem);
        
        // Enable symfony exceptions for debug
        if($apiProblem->getStatusCode() == 500 && $this->isDebug)
            return;

        $event->setResponse($response);
    }
}
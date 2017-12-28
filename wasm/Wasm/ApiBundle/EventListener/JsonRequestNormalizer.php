<?php
namespace Wasm\ApiBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Wasm\ApiBundle\Exception\ApiProblemException;
use Wasm\ApiBundle\Util\ApiProblem;

// By def only 'application/x-www-form-unencoded' request data is transformed
// to $request->request->get() data. This class transforms 
// request json data with 'application/json' header into request->request->get
// data;
class JsonRequestNormalizer
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $content = $request->getContent();

        if(empty($content))
            return;

        if(!$this->isJsonRequest($request))
            return;

        $this->normalizeJsonRequest($request);
    }

    private function isJsonRequest(Request $request)
    {
        return 'json' === $request->getContentType();
    }

    private function normalizeJsonRequest(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if(json_last_error() !== JSON_ERROR_NONE || $data === null) {
            $apiProblem = new ApiProblem(
                400, ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT
            );
            throw new ApiProblemException($apiProblem);
        }

        $request->request->replace($data);
    }
}


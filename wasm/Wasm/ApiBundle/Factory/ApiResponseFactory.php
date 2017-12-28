<?php
namespace Wasm\ApiBundle\Factory;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\FormInterface;
use JMS\Serializer\SerializationContext;
use Wasm\ApiBundle\Util\ApiProblem;
use Wasm\ApiBundle\Exception\ApiProblemException;

class ApiResponseFactory
{
    private $requestStack;
    private $serializer;
    private $serializerFormat = 'json';

    public function __construct($requestStack, $serializer)
    {
        $this->requestStack = $requestStack;
        $this->serializer = $serializer;
    }

    public function serialize($data, $groups = null)
    {
        // $context = new SerializationContext();
        // $context->setSerializeNull(true);

        // $request = $this->requestStack->getCurrentRequest();
        // $groups = array('Default');

        // if($request->query->get('deep')) {
        //     $groups[] = 'deep';
        // }
        // $context->setGroups($groups);

        //return $this->serializer->serialize($data, $format, $context);
        $params = array();
        if($groups != null)
            $params['groups'] = $groups;

        return $this->serializer->serialize(
            $data, $this->serializerFormat, $params
        );
    }

    public function createResponse($data, $groups = null, $statusCode = 200)
    {
        $data["status"] = $statusCode;
        $json = $this->serialize($data, $groups);

        return new Response($json, $statusCode, array(
            'Content-Type' => 'application/hal+json'
        ));
    }

    public function createProblemResponse(ApiProblem $apiProblem)
    {
        $data = $apiProblem->toArray();
        // @todo -> Get correct url from problem type.
        // if($data['type'] != 'about:blank')
        //      $data['type'] = getUrlFromProblemType($data['type']);

        return new JsonResponse($data, $apiProblem->getStatusCode(), array(
            'Content-Type' => 'application/problem+json'
        ));
    }

    public function throwFormValidationException(array $errors)
    {
        $apiProblem = new ApiProblem(
            400,
            ApiProblem::TYPE_VALIDATION_ERROR
        );
        $apiProblem->setErrors($errors);

        throw new ApiProblemException($apiProblem);
    }

    public function throwValidationException($errors)
    {
        $this->throwFormValidationException(
            is_array($errors) ? $errors : array($errors)
        );
    }
}
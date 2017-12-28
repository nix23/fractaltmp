<?php
namespace Wasm\ApiBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Wasm\ApiBundle\Util\ApiProblem;

class ApiProblemException extends HttpException
{
    private $apiProblem;

    public function __construct(ApiProblem $apiProblem, 
                                \Exception $previous = null, 
                                array $headers = array(), 
                                $code = 0)
    {
        $this->apiProblem = $apiProblem;

        $statusCode = $apiProblem->getStatusCode();
        $message = $apiProblem->getTitle();

        parent::__construct(
            $statusCode, $message, $previous, $headers, $code
        );
    }

    public function getApiProblem()
    {
        return $this->apiProblem;
    }
}
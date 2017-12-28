<?php
namespace Wasm\ApiBundle\Util;

use Symfony\Component\HttpFoundation\Response;

/**
 * A wrapper for holding data to be used for 
 * a application/problem+json response
 */
class ApiProblem
{
    const TYPE_VALIDATION_ERROR = 'validation_error';
    const TYPE_INVALID_REQUEST_BODY_FORMAT = 'invalid_body_format';
    const TYPE_AUTHORIZATION_REQUIRED = 'authorization_required';
    const TYPE_INVALID_CREDENTIALS = 'invalid_credentials';
    const TYPE_EXPIRED_CREDENTIALS = 'expired_credentials';

    private static $titles = array(
        self::TYPE_VALIDATION_ERROR => 'There was a validation error',
        self::TYPE_INVALID_REQUEST_BODY_FORMAT => 'Invalid JSON format sent',
        self::TYPE_AUTHORIZATION_REQUIRED => 'Missing credentials',
        self::TYPE_INVALID_CREDENTIALS => 'Invalid credentials',
        self::TYPE_EXPIRED_CREDENTIALS => 'Expired credentials',
    );

    private $statusCode;

    private $type;

    private $title;

    // "detail", "validation_messages", etc...;
    private $extraData = array();

    public function __construct($statusCode, $type = null)
    {
        $this->statusCode = $statusCode;
        
        if($type === null) {
            $type = 'about:blank';
            if(isset(Response::$statusTexts[$statusCode]))
                $title = Response::$statusTexts[$statusCode];
            else
                $title = 'Unknown status code';
        }
        else {
            if(!isset(self::$titles[$type])) 
                throw new \InvalidArgumentException('No title for type ' . $type);

            $title = self::$titles[$type];
        }

        $this->type = $type;
        $this->title = $title;
    }

    public function toArray()
    {
        return array_merge(
            $this->extraData,
            array(
                'status' => $this->statusCode,
                'type' => $this->type,
                'title' => $this->title,
            )
        );
    }

    public function set($name, $value)
    {
        $this->extraData[$name] = $value;
    }

    public function setErrors($errors)
    {
        $this->extraData["errors"] = $errors;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
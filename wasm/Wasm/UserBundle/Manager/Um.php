<?php
namespace Wasm\UserBundle\Manager;

class Um
{
    private $tokenStorage;

    public function __construct($tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function hasToken()
    {
        return $this->tokenStorage->getToken() !== null;
    }

    public function getUser()
    {
        if(!$this->hasToken())
            return null;

        return $this->tokenStorage->getToken()->getUser();
    }
}
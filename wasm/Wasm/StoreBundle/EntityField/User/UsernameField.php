<?php
namespace Wasm\StoreBundle\EntityField\User;

use Doctrine\ORM\Mapping AS ORM;

trait UsernameField
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username = "";

    public function setUsername($name)
    {
        $this->username = $name;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }
}
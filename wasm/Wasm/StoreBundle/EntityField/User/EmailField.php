<?php
namespace Wasm\StoreBundle\EntityField\User;

use Doctrine\ORM\Mapping AS ORM;

trait EmailField
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email = "";

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
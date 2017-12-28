<?php
namespace Wasm\StoreBundle\EntityField\User;

use Doctrine\ORM\Mapping AS ORM;

trait PasswordField
{
    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $password = "";

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }
}
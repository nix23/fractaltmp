<?php
namespace Wasm\StoreBundle\EntityField\Account;

use Doctrine\ORM\Mapping AS ORM;

trait LastNameField
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName = "";

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }
}
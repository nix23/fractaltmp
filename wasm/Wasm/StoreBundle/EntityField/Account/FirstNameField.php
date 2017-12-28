<?php
namespace Wasm\StoreBundle\EntityField\Account;

use Doctrine\ORM\Mapping AS ORM;

trait FirstNameField
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName = "";

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }
}
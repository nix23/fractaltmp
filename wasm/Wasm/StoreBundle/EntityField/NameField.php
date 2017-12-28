<?php
namespace Wasm\StoreBundle\EntityField;

use Doctrine\ORM\Mapping AS ORM;

trait NameField
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name = "";

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
}

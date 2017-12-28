<?php
namespace Wasm\StoreBundle\EntityField;

use Doctrine\ORM\Mapping AS ORM;

trait DescriptionField
{
    /**
     * @ORM\Column(type="string", length=500)
     */
    private $description = "";

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }
}

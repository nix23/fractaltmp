<?php
namespace Wasm\StoreBundle\EntityField;

use Doctrine\ORM\Mapping AS ORM;

trait IsDefaultField
{
    /**
     * @ORM\Column(type="boolean")
     */
    private $isDefault = false;

    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;
        return $this;
    }

    public function getIsDefault()
    {
        return $this->isDefault;
    }
}

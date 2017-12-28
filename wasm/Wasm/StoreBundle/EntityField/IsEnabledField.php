<?php
namespace Wasm\StoreBundle\EntityField;

use Doctrine\ORM\Mapping AS ORM;

trait IsEnabledField
{
    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnabled = true;

    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getIsEnabled()
    {
        return $this->isEnabled;
    }
}

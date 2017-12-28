<?php
namespace Wasm\StoreBundle\EntityField\App;

use Doctrine\ORM\Mapping AS ORM;

trait VendorField
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vendor = "";

    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
    }

    public function getVendor()
    {
        return $this->vendor;
    }
}
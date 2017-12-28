<?php
namespace Wasm\FsBundle\FilePath\Mod;

use Wasm\ModBundle\Entity\Mod;

class VendorFilePath
{
    private $modFilePath;
    private $groupFilePath;

    public function __construct($groupFilePath)
    {
        $this->groupFilePath = $groupFilePath;
        $this->groupFilePath->initialize($this);
    }

    public function initialize($modFilePath)
    {
        $this->modFilePath = $modFilePath;
    }

    public function group()
    {
        return $this->groupFilePath;
    }

    // /wmod/Wasm/
    public function getPath(Mod $mod)
    {
        return $this->modFilePath->getPath() . $mod->getVendor() . "/";
    }
}
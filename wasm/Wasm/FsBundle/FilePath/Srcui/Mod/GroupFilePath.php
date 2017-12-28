<?php
namespace Wasm\FsBundle\FilePath\Srcui\Mod;

use Wasm\ModBundle\Entity\Mod;

class GroupFilePath
{
    private $vendorFilePath;
    private $modFilePath;

    public function __construct($modFilePath)
    {
        $this->modFilePath = $modFilePath;
        $this->modFilePath->initialize($this);
    }

    public function initialize($vendorFilePath)
    {
        $this->vendorFilePath = $vendorFilePath;
    }

    public function mod()
    {
        return $this->modFilePath;
    }

    // /wsrcui/wmod/Wasm/Group/
    public function getPath(Mod $mod)
    {
        return $this->vendorFilePath->getPath($mod) . 
               $mod->getGroupName() . 
               "/";
    }
}
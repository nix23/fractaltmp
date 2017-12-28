<?php
namespace Wasm\FsBundle\FilePath;

use Wasm\AppBundle\Entity\Group;
use Wasm\AppBundle\Entity\App;
use Wasm\AppBundle\Entity\Section;
use Wasm\AppBundle\Entity\Package;
use Wasm\ModBundle\Entity\Mod;

class ModFilePath
{
    private $rootFilePath;
    private $vendorFilePath;

    public function __construct($rootFilePath, $vendorFilePath)
    {
        $this->rootFilePath = $rootFilePath;
        $this->vendorFilePath = $vendorFilePath;
        $this->vendorFilePath->initialize($this);
    }

    public function vendor()
    {
        return $this->vendorFilePath;
    }

    // /wmod/
    public function getPath()
    {
        return $this->rootFilePath->getPath() . "wmod/";
    }
}
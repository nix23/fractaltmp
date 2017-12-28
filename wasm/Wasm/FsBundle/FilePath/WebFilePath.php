<?php
namespace Wasm\FsBundle\FilePath;

use Wasm\AppBundle\Entity\Group;
use Wasm\AppBundle\Entity\App;
use Wasm\AppBundle\Entity\Section;
use Wasm\AppBundle\Entity\Package;
use Wasm\ModBundle\Entity\Mod;

class WebFilePath
{
    private $rootFilePath;

    public function __construct($rootFilePath)
    {
        $this->rootFilePath = $rootFilePath;
    }

    // /wweb
    public function getPath()
    {
        return $this->rootFilePath->getPath() . "wweb/";
    }
}
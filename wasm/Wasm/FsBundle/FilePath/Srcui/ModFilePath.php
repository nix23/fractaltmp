<?php
namespace Wasm\FsBundle\FilePath\Srcui;

use Wasm\AppBundle\Entity\Group;
use Wasm\AppBundle\Entity\App;
use Wasm\AppBundle\Entity\Section;
use Wasm\AppBundle\Entity\Package;
use Wasm\ModBundle\Entity\Mod;

class ModFilePath
{
    private $srcuiFilePath;
    private $vendorFilePath;

    public function __construct($vendorFilePath)
    {
        $this->vendorFilePath = $vendorFilePath;
        $this->vendorFilePath->initialize($this);
    }

    public function initialize($srcuiFilePath)
    {
        $this->srcuiFilePath = $srcuiFilePath;
    }

    public function vendor()
    {
        return $this->vendorFilePath;
    }

    // /wsrcui/wmod/
    public function getPath()
    {
        return $this->srcuiFilePath->getPath() . "wmod/";
    }
}
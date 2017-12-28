<?php
namespace Wasm\FsBundle\FilePath\Srcui;

use Wasm\AppBundle\Entity\Group;
use Wasm\AppBundle\Entity\App;
use Wasm\AppBundle\Entity\Section;
use Wasm\AppBundle\Entity\Package;
use Wasm\ModBundle\Entity\Mod;

class SrcFilePath
{
    private $srcuiFilePath;
    private $groupFilePath;

    public function __construct($groupFilePath)
    {
        $this->groupFilePath = $groupFilePath;
        $this->groupFilePath->initialize($this);
    }

    public function initialize($srcuiFilePath)
    {
        $this->srcuiFilePath = $srcuiFilePath;
    }

    public function group()
    {
        return $this->groupFilePath;
    }

    // /wsrcui/wsrc/
    public function getPath()
    {
        return $this->srcuiFilePath->getPath() . "wsrc/";
    }
}
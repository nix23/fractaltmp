<?php
namespace Wasm\FsBundle\FilePath;

use Wasm\AppBundle\Entity\Group;
use Wasm\AppBundle\Entity\App;
use Wasm\AppBundle\Entity\Section;
use Wasm\AppBundle\Entity\Package;
use Wasm\ModBundle\Entity\Mod;

class SrcuiFilePath
{
    private $rootFilePath;
    private $modFilePath;
    private $srcFilePath;

    public function __construct(
        $rootFilePath,
        $modFilePath,
        $srcFilePath
    ) {
        $this->rootFilePath = $rootFilePath;
        $this->modFilePath = $modFilePath;
        $this->srcFilePath = $srcFilePath;

        $this->modFilePath->initialize($this);
        $this->srcFilePath->initialize($this);
    }

    // /wsrcui/
    public function getPath()
    {
        return $this->rootFilePath->getPath() . "wsrcui/";
    }

    public function mod()
    {
        return $this->modFilePath;
    }

    public function src()
    {
        return $this->srcFilePath;
    }
}
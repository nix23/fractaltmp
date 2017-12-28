<?php
namespace Wasm\FsBundle\FilePath\Srcui\Src;

use Wasm\AppBundle\Entity\Group;

class GroupFilePath
{
    private $srcFilePath;
    private $appFilePath;

    public function __construct($appFilePath)
    {
        $this->appFilePath = $appFilePath;
        $this->appFilePath->initialize($this);
    }

    public function initialize($srcFilePath)
    {
        $this->srcFilePath = $srcFilePath;
    }

    public function app()
    {
        return $this->appFilePath;
    }

    // /wsrcui/wsrc/Apps/
    public function getPath(Group $group)
    {
        return $this->srcFilePath->getPath() . $group->getName() . "/";
    }
}
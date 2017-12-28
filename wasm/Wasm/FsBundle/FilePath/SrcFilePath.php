<?php
namespace Wasm\FsBundle\FilePath;

class SrcFilePath
{
    private $rootFilePath;
    private $groupFilePath;

    public function __construct($rootFilePath, $groupFilePath)
    {
        $this->rootFilePath = $rootFilePath;
        $this->groupFilePath = $groupFilePath;
        $this->groupFilePath->initialize($this);
    }

    public function group()
    {
        return $this->groupFilePath;
    }

    // /wsrc/
    public function getPath()
    {
        return $this->rootFilePath->getPath() . "wsrc/";
    }
}
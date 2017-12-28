<?php
namespace Wasm\FsBundle\FilePath\Src;

use Wasm\AppBundle\Entity\Section;
use Wasm\FsBundle\FilePath\Util\IsSynthetic;

class SectionFilePath
{
    private $appFilePath;
    private $packageFilePath;

    public function __construct($packageFilePath)
    {
        $this->packageFilePath = $packageFilePath;
        $this->packageFilePath->initialize($this);
    }

    public function initialize($appFilePath)
    {
        $this->appFilePath = $appFilePath;
    }

    public function package()
    {
        return $this->packageFilePath;
    }

    // /wsrc/Apps/DemoApp/App/
    public function getPath(Section $section)
    {
        $path = $this->appFilePath->getPath($section->getApp());
        if(IsSynthetic::section($section))
            return $path;

        return $path . $section->getName() . "/";
    }

    // /wsrc/Apps/DemoAppCms/App/
    public function getCmsPath(Section $section)
    {
        $path = $this->appFilePath->getCmsPath($section->getApp());
        if(IsSynthetic::section($section))
            return $path;

        return $path . $section->getName() . "/";
    }
}
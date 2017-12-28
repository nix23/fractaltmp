<?php
namespace Wasm\FsBundle\FilePath\Srcui\Src;

use Wasm\AppBundle\Entity\Package;
use Wasm\FsBundle\FilePath\Util\IsSynthetic;

class PackageFilePath
{
    private $sectionFilePath;
    private $appSceneFilePath;
    private $webSceneFilePath;

    public function __construct($appSceneFilePath, $webSceneFilePath)
    {
        $this->appSceneFilePath = $appSceneFilePath;
        $this->webSceneFilePath = $webSceneFilePath;

        $this->appSceneFilePath->initialize($this);
        $this->webSceneFilePath->initialize($this);
    }

    public function initialize($sectionFilePath)
    {
        $this->sectionFilePath = $sectionFilePath;
    }

    public function appScene()
    {
        return $this->appSceneFilePath;
    }

    public function webScene()
    {
        return $this->webSceneFilePath;
    }

    // /wsrcui/wsrc/Apps/DemoApp/App/App/
    public function getPath(Package $package)
    {
        $path = $this->sectionFilePath->getPath($package->getSection());
        if(IsSynthetic::package($package))
            return $path;

        return $path . $package->getName() . "/";
    }

    // /wsrcui/wsrc/Apps/DemoAppCms/App/App/
    public function getCmsPath(Package $package)
    {
        $path = $this->sectionFilePath->getCmsPath($package->getSection());
        if(IsSynthetic::package($package))
            return $path;

        return $path . $package->getName() . "/";
    }
}
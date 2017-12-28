<?php
namespace Wasm\FsBundle\FilePath\Srcui\Src\Package;

use Wasm\AppBundle\Entity\Package;

class AppSceneFilePath
{
    private $packageFilePath;

    public function initialize($packageFilePath)
    {
        $this->packageFilePath = $packageFilePath;
    }

    // /wsrcui/wsrc/Apps/DemoApp/App/App/AppScene/
    public function getPath(Package $package)
    {
        $packagePath = $this->packageFilePath->getPath($package);
        return $packagePath . "AppScene" . "/";
    }

    // /wsrcui/wsrc/Apps/DemoAppCms/App/App/AppScene/
    public function getCmsPath(Package $package)
    {
        $packagePath = $this->packageFilePath->getCmsPath($package);
        return $packagePath . "AppScene" . "/";
    }
}
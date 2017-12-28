<?php
namespace Wasm\FsBundle\FilePath\Srcui\Src\Package;

use Wasm\AppBundle\Entity\Package;

class WebSceneFilePath
{
    private $packageFilePath;

    public function initialize($packageFilePath)
    {
        $this->packageFilePath = $packageFilePath;
    }

    // /wsrcui/wsrc/Apps/DemoApp/App/App/WebScene/
    public function getPath(Package $package)
    {
        $packagePath = $this->packageFilePath->getPath($package);
        return $packagePath . "WebScene" . "/";
    }

    // /wsrcui/wsrc/Apps/DemoAppCms/App/App/WebScene/
    public function getCmsPath(Package $package)
    {
        $packagePath = $this->packageFilePath->getPath($package);
        return $packagePath . "WebScene" . "/";
    }
}
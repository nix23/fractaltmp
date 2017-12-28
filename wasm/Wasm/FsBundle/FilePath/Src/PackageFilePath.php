<?php
namespace Wasm\FsBundle\FilePath\Src;

use Wasm\AppBundle\Entity\Package;
use Wasm\FsBundle\FilePath\Util\IsSynthetic;

class PackageFilePath
{
    private $sectionFilePath;

    public function initialize($sectionFilePath)
    {
        $this->sectionFilePath = $sectionFilePath;
    }

    // /wsrc/Apps/DemoApp/App/AppBundle/
    public function getPath(Package $package)
    {
        $path = $this->sectionFilePath->getPath($package->getSection());
        if(IsSynthetic::package($package))
            return $path;

        return $path . $package->getFullName() . "/";
    }

    // /wsrc/Apps/DemoAppCms/App/AppBundle/
    public function getCmsPath(Package $package)
    {
        $path = $this->sectionFilePath->getCmsPath($package->getSection());
        if(IsSynthetic::package($package))
            return $path;

        return $path . $package->getFullName() . "/";
    }
}
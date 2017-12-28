<?php
namespace Wasm\AppBundle\Store;

use Wasm\AppBundle\Entity\App;
use Wasm\AppBundle\Entity\Section;
use Wasm\AppBundle\Entity\Package;

class PackageStore
{
    public function __construct($em)
    {

    }

    public function getSyntheticPackageForAppRoot(App $app)
    {
        $section = new Section();
        $section->setApp($app);

        $package = new Package();
        $package->setSection($section);

        return $package;
    }
}
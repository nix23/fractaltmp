<?php
namespace Wasm\AppBundle\Form\DataSource;

use Wasm\FormBundle\Util\DataSource;
use Wasm\AppBundle\Entity\Package;

class PackageData
{
    public function getPackageTypeData()
    {
        return DataSource::createItems(
            Package::getPackageTypeLabels(),
            Package::getPackageTypeVals()
        );
    }
}
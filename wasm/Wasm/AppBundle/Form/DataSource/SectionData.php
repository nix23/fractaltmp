<?php
namespace Wasm\AppBundle\Form\DataSource;

use Wasm\FormBundle\Util\DataSource;
use Wasm\AppBundle\Entity\Section;

class SectionData
{
    public function getSectionTypeData()
    {
        return DataSource::createItems(
            Section::getSectionTypeLabels(),
            Section::getSectionTypeVals()
        );
    }
}
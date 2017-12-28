<?php
namespace Wasm\AppSceneBundle\Form\DataSource;

use Wasm\AppSceneBundle\Entity\Breakpoint;
use Wasm\FormBundle\Util\DataSource;

class BreakpointData
{
    private $repo;

    public function initialize($em)
    {
        $this->repo = $em->getEm()->getRepository("WasmAppSceneBundle:Breakpoint");
    }

    public function getBreakpointData()
    {
        return $this->repo->getAllSorted();
    }

    public static function getBreakpointDataProps($breakpoint)
    {
        return array("label" => $breakpoint->getName());
    }

    public function getBreakpointTypeData()
    {
        return DataSource::createItems(
            Breakpoint::getBreakpointTypeLabels(),
            Breakpoint::getBreakpointTypeVals()
        );
    }
}
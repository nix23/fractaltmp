<?php
namespace Wasm\AppSceneBundle\Form\DataSource;

use Wasm\FormBundle\Util\DataSource;

class LayoutData
{
    private $repo;

    public function initialize($em)
    {
        $this->repo = $em->getEm()->getRepository("WasmAppSceneBundle:Layout");
    }

    public function getLayoutData()
    {
        return $this->repo->getAllWebLayouts();
    }

    public static function getLayoutDataProps($layout)
    {
        return array("label" => $layout->getName());
    }
}
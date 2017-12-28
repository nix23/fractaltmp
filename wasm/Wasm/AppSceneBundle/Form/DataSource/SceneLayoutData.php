<?php
namespace Wasm\AppSceneBundle\Form\DataSource;

use Wasm\FormBundle\Util\DataSource;

class SceneLayoutData
{
    private $entityId;
    private $repo;

    public function initialize($em, $formData)
    {
        $this->repo = $em->getEm()->getRepository("WasmAppSceneBundle:SceneLayout");
    }

    public function getSceneLayoutData()
    {
        return $this->repo->getAllWebLayouts();
    }

    public static function getSceneLayoutDataProps($layout)
    {
        return array("label" => $layout->getName());
    }
}
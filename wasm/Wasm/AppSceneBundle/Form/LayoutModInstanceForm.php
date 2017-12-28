<?php
namespace Wasm\AppSceneBundle\Form;

use Wasm\FormBundle\Form\Annotation\Form;
use Wasm\FormBundle\Field\Annotation\Field;
use Wasm\FormBundle\Validator\Constraints\Symfony\NotBlank;
use Wasm\FormBundle\Validator\Constraints\Symfony\Length;
use Wasm\AppSceneBundle\Entity\LayoutItem;
use Wasm\AppSceneBundle\Entity\LayoutModInstance;

// @todo -> Normalize name -> spaces, ucfirst
/**
 * @Form(entity="\Wasm\AppSceneBundle\Entity\LayoutModInstance")
 */
class LayoutModInstanceForm
{
    /**
     * @Field(
     *     type="select",
     *     dataSource="\Wasm\AppSceneBundle\Form\DataSource\SceneLayoutData"
     * )
     * @NotBlank
     */
    public $sceneLayout;

    /**
     * @Field(type="hidden")
     * @NotBlank
     */
    public $layoutItem;

    /**
     * @Field(
     *     type="select",
     *     dataSource="\Wasm\AppSceneBundle\Form\DataSource\ModInstanceData"
     * )
     * @NotBlank
     */
    public $modInstance;

    public function getSceneDefState($entityId, $em)
    {
        $scene = $em
            ->getEm()
            ->getRepository("WasmAppSceneBundle:Scene")
            ->find($entityId["scene"]);

        return $scene->getId();
    }

    public function getSceneState($entity)
    {
        return $entity->getScene()->getId();
    }

    public function setSceneState($em)
    {
        return $em
            ->getEm()
            ->getRepository("WasmAppSceneBundle:Scene")
            ->find($this->scene);
    }

    public function getLayoutDefState($entityId, $em)
    {
        $layout = $em
            ->getEm()
            ->getRepository("WasmAppSceneBundle:Layout")
            ->find($entityId["layout"]);

        return $layout->getId();
    }

    public function getLayoutState($entity)
    {
        return $entity->getLayout()->getId();
    }

    public function setLayoutState($em)
    {
        return $em 
            ->getEm()
            ->getRepository("WasmAppSceneBundle:Layout")
            ->find($this->layout);
    }

    public function setModInstanceState($em)
    {
        return $em
            ->getEm()
            ->getRepository("WasmModBundle:ModInstance")
            ->find($this->modInstance);
    }
}
<?php
namespace Wasm\AppSceneBundle\Form;

use Wasm\FormBundle\Form\Annotation\Form;
use Wasm\FormBundle\Field\Annotation\Field;
use Wasm\FormBundle\Validator\Constraints\Symfony\NotBlank;
use Wasm\FormBundle\Validator\Constraints\Symfony\Length;
use Wasm\AppSceneBundle\Entity\SceneLayout;

// @todo -> Normalize name -> spaces, ucfirst
/**
 * @Form(entity="\Wasm\AppSceneBundle\Entity\SceneLayout")
 */
class SceneLayoutForm
{
    /**
     * @Field(type="hidden")
     * @NotBlank
     */
    public $scene;

    /**
     * @Field(
     *     type="select",
     *     dataSource="\Wasm\AppSceneBundle\Form\DataSource\LayoutData"
     * )
     * @NotBlank
     */
    public $layout;

    /**
     * @Field(
     *     type="radios",
     *     dataSource="\Wasm\AppSceneBundle\Form\DataSource\BreakpointData"
     * )
     * @NotBlank
     */
    public $breakpointType;

    /**
     * @Field(
     *     type="select",
     *     dataSource="\Wasm\AppSceneBundle\Form\DataSource\BreakpointData"
     * )
     * @NotBlank
     */
    public $breakpoint;

    /**
     * @Field(
     *     type="select",
     *     dataSource="\Wasm\AppSceneBundle\Form\DataSource\BreakpointData"
     * )
     */
    public $secondBreakpoint;

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

    public function setLayoutState($em)
    {
        return $em
            ->getEm()
            ->getRepository("WasmAppSceneBundle:Layout")
            ->find($this->layout);
    }

    public function setBreakpointState($em)
    {
        return $em
            ->getEm()
            ->getRepository("WasmAppSceneBundle:Breakpoint")
            ->find($this->breakpoint);
    }

    public function setSecondBreakpointState($em)
    {
        if(!is_numeric($this->secondBreakpoint))
            return null;

        return $em
            ->getEm()
            ->getRepository("WasmAppSceneBundle:Breakpoint")
            ->find($this->secondBreakpoint);
    }
}
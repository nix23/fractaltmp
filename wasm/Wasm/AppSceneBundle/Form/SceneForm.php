<?php
namespace Wasm\AppSceneBundle\Form;

use Wasm\FormBundle\Form\Annotation\Form;
use Wasm\FormBundle\Field\Annotation\Field;
use Wasm\FormBundle\Validator\Constraints\Symfony\NotBlank;
use Wasm\FormBundle\Validator\Constraints\Symfony\Length;
use Wasm\AppSceneBundle\Entity\Scene;

// @todo -> Normalize name -> spaces, ucfirst
/**
 * @Form(entity="\Wasm\AppSceneBundle\Entity\Scene")
 */
class SceneForm
{
    /**
     * @Field
     * @NotBlank
     * @Length
     */
    public $name;

    /**
     * @Field(
     *     type="select",
     *     label="Scene type",
     *     dataSource="\Wasm\AppSceneBundle\Form\DataSource\SceneData"
     * )
     * @NotBlank(field="scene type")
     */
    public $sceneType = Scene::SCENE_TYPE_WEB;

    /**
     * @Field(type="hidden")
     * @NotBlank(field="application section package")
     */
    public $package;

    public function getPackageDefState($entityId, $em)
    {
        $package = $em
            ->getEm()
            ->getRepository("WasmAppBundle:Package")
            ->find($entityId["package"]);

        return $package->getId();
    }

    public function getPackageState($entity)
    {
        return $entity->getPackage()->getId();
    }

    public function setPackageState($em)
    {
        return $em
            ->getEm()
            ->getRepository("WasmAppBundle:Package")
            ->find($this->package);
    }
}
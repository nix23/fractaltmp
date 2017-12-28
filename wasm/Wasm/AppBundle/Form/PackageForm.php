<?php
namespace Wasm\AppBundle\Form;

use Wasm\FormBundle\Form\Annotation\Form;
use Wasm\FormBundle\Field\Annotation\Field;
use Wasm\FormBundle\Validator\Constraints\Symfony\NotBlank;
use Wasm\FormBundle\Validator\Constraints\Symfony\Length;
use Wasm\AppBundle\Entity\Package;

// @todo -> Normalize name -> spaces, ucfirst
/**
 * @Form(entity="\Wasm\AppBundle\Entity\Package")
 */
class PackageForm
{
    /**
     * @Field
     * @NotBlank
     * @Length
     */
    public $name;

    /**
     * @Field(type="hidden")
     * @NotBlank(field="application section")
     */
    public $section;

    /**
     * @Field(
     *     type="select",
     *     label="Section package type",
     *     dataSource="\Wasm\AppBundle\Form\DataSource\PackageData"
     * )
     * @NotBlank(field="section package type")
     */
    public $packageType = Package::PACKAGE_TYPE_APP_AND_CMSAPP;

    public function getSectionDefState($entityId, $em)
    {
        $section = $em
            ->getEm()
            ->getRepository("WasmAppBundle:Section")
            ->find($entityId["section"]);

        return $section->getId();
    }

    public function getSectionState($entity)
    {
        return $entity->getSection()->getId();
    }

    public function setSectionState($em)
    {
        return $em
            ->getEm()
            ->getRepository("WasmAppBundle:Section")
            ->find($this->section);
    }
}
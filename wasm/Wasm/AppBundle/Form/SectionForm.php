<?php
namespace Wasm\AppBundle\Form;

use Wasm\FormBundle\Form\Annotation\Form;
use Wasm\FormBundle\Field\Annotation\Field;
use Wasm\FormBundle\Validator\Constraints\Symfony\NotBlank;
use Wasm\FormBundle\Validator\Constraints\Symfony\Length;
use Wasm\AppBundle\Entity\Section;

// @todo -> Normalize name -> spaces, ucfirst
/**
 * @Form(entity="\Wasm\AppBundle\Entity\Section")
 */
class SectionForm
{
    /**
     * @Field
     * @NotBlank
     * @Length
     */
    public $name;

    // @todo -> Add type hiddenEntity(Auto-transform?)
    /**
     * @Field(type="hidden")
     * @NotBlank(field="application")
     */
    public $app;

    /**
     * @Field(
     *     type="select",
     *     label="Section type",
     *     dataSource="\Wasm\AppBundle\Form\DataSource\SectionData"
     * )
     * @NotBlank(field="section type")
     */
    public $sectionType = Section::SECTION_TYPE_APP_AND_CMSAPP;

    public function getAppDefState($entityId, $em)
    {
        $app = $em
            ->getEm()
            ->getRepository("WasmAppBundle:App")
            ->find($entityId["app"]);

        $section = new Section();
        $section->setApp($app);

        return $section;
    }

    public function getAppState($entity)
    {
        return $entity->getApp()->getId();
    }

    public function setAppState($em)
    {
        return $em
            ->getEm()
            ->getRepository("WasmAppBundle:App")
            ->find($this->app);
    }
}
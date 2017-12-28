<?php
namespace Wasm\AppBundle\Form;

use Wasm\FormBundle\Form\Annotation\Form;
use Wasm\FormBundle\Field\Annotation\Field;
use Wasm\FormBundle\Validator\Constraints\Symfony\NotBlank;
use Wasm\FormBundle\Validator\Constraints\Symfony\Length;

/**
 * @Form(entity="\Wasm\AppBundle\Entity\App")
 */
class AppForm
{
    /**
     * @Field
     * @NotBlank
     * @Length
     */
    public $name;

    /**
     * @Field
     * @NotBlank
     * @Length
     */
    public $namespace;

    /**
     * @Field(
     *     type="select",
     *     label="Application group",
     *     dataSource="\Wasm\AppBundle\Form\DataSource\GroupData"
     * )
     * @NotBlank(field="application group")
     */
    public $group;
}
<?php
namespace Wasm\AppBundle\Form;

use Wasm\FormBundle\Form\Annotation\Form;
use Wasm\FormBundle\Field\Annotation\Field;
use Wasm\FormBundle\Validator\Constraints\Symfony\NotBlank;
use Wasm\FormBundle\Validator\Constraints\Symfony\Length;

// @todo -> Normalize name -> spaces, ucfirst
/**
 * @Form(entity="\Wasm\AppBundle\Entity\Group")
 */
class GroupForm
{
    /**
     * @Field
     * @NotBlank
     * @Length
     */
    public $name;

    /**
     * @Field(type="hidden")
     */
    public $isDefault;
}
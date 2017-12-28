<?php
namespace Wasm\FormBundle\Validator\Constraints\Symfony;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotBlankNumber extends Constraint
{
    public $field = null;
}
<?php
namespace Wasm\FormBundle\Validator\Constraints\Symfony;

use Symfony\Component\Validator\Constraints\NotBlank as NotBlankConstraint;

/**
 * @Annotation
 */
class NotBlank extends NotBlankConstraint
{
    public $field = null;
}
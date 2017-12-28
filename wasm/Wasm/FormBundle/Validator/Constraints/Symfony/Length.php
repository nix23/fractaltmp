<?php
namespace Wasm\FormBundle\Validator\Constraints\Symfony;

use Symfony\Component\Validator\Constraints\Length as LengthConstraint;

/**
 * @Annotation
 */
class Length extends LengthConstraint
{
    public $field = null;
    public $max = 255;
}
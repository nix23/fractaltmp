<?php
namespace Wasm\FormBundle\Validator\Constraints\Symfony;

use Symfony\Component\Validator\Constraints\Count as CountConstraint;

/**
 * @Annotation
 */
class Count extends CountConstraint
{
    public $field = null;
    public $min = null;
    public $max = null;
}
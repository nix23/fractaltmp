<?php
namespace Wasm\FormBundle\Validator\Constraints\Security;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsEnoughStrong extends Constraint
{
    public $message = '%message%';
}
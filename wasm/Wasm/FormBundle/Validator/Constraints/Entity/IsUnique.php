<?php
namespace Wasm\FormBundle\Validator\Constraints\Entity;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsUnique extends Constraint
{
    public $message = '%message%';
    public $errMessage = '';
    public $repo = 'NtechCoreBundle:Account';
}
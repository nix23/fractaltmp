<?php
namespace Wasm\FormBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsValidEndDate extends Constraint
{
    public $monthMsg = null;
    public $yearMsg = null;

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
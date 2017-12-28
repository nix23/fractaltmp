<?php
namespace Wasm\FormBundle\Validator\Constraints\Collection;

use Symfony\Component\Validator\Constraints\Count as CountConstraint;

/**
 * @Annotation
 */
class CountCollectionStates extends CountConstraint
{
    public $field = null;
    public $min = null;
    public $max = null;

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
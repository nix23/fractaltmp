<?php
namespace Wasm\FormBundle\Validator\Constraints\Comparison;

use Symfony\Component\Validator\Constraint;

// @todo -> Add multiple errors support
// (Set error to each of $props fields)
/**
 * @Annotation
 */
class EqualStates extends Constraint
{
    public $message = '%messagePrefix% must match %messagePostfix%.';
    public $messagePrefix = '';
    public $messagePostfix = '';
    // With this prop violation is added to 'targetProp'.
    // @todo -> Otherwise add violation to all props.
    // @todo -> Add 'global' prop???(For global errors)
    public $atProp = null;
    public $props = array();

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
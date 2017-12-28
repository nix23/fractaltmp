<?php
namespace Wasm\FormBundle\Validator\Constraints\Location;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AddressHasLocation extends Constraint
{
    public $message = '%message%';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
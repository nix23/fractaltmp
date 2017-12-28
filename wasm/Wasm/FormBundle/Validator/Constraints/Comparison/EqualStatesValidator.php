<?php
namespace Wasm\FormBundle\Validator\Constraints\Comparison;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Wasm\UtilBundle\Util\Str;

class EqualStatesValidator extends ConstraintValidator
{
    public function validate($object, Constraint $constraint)
    {
        $allProps = $constraint->props;
        if(count($allProps) == 0)
            return;
        
        $messagePrefix = $constraint->messagePrefix;
        if(Str::isBlank($messagePrefix)) {
            $messagePrefix = Str::ucfirst(
                Str::spacesBeforeUpper($allProps[0])
            );
        }

        $subprops = $this->filterAllExceptFirstProps($allProps);

        $messagePostfix = $constraint->messagePostfix;
        if(Str::isBlank($messagePostfix)) {
            $messagePostfix = implode("|", array_map(
                function($prop) {
                    return Str::spacesBeforeUpper($prop);
                },
                $subprops
            ));
        }

        if(!$this->shouldValidate($object, $allProps))
            return;

        if($this->areStatesEqual($object, $allProps[0], $subprops))
            return;

        $this->context->buildViolation($constraint->message)
            ->setParameter("%messagePrefix%", $messagePrefix)
            ->setParameter("%messagePostfix%", $messagePostfix)
            ->atPath($constraint->atProp)
            ->addViolation();
    }

    private function filterAllExceptFirstProps($props)
    {
        $filtered = array();
        for($i = 1; $i < count($props); $i++)
            $filtered[] = $props[$i];

        return $filtered;
    }

    private function shouldValidate($object, $props)
    {
        foreach($props as $prop) {
            if(Str::isBlank($object->$prop))
                return false;
        }

        return true;
    }

    private function areStatesEqual($object, $mainProp, $subprops)
    {
        return array_reduce($subprops, function($res, $prop) use ($object, $mainProp) {
            if(!$res)
                return false;

            return ($object->$prop == $object->$mainProp);
        }, true);
    }
}
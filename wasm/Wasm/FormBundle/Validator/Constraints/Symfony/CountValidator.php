<?php
namespace Wasm\FormBundle\Validator\Constraints\Symfony;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\CountValidator as CountConstraintValidator;
use Wasm\UtilBundle\Util\Str;

class CountValidator extends CountConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $this->setMaxData($constraint);
        $this->setMinData($constraint);

        parent::validate($value, $constraint);
    }

    private function setMaxData(Constraint $constraint)
    {
        if($constraint->max == null)
            return;

        $constraint->maxMessage = "Please enter up to {$constraint->max} ";
        if($constraint->field == null)
            $constraint->maxMessage .= Str::spacesBeforeUpper(
                $this->context->getPropertyPath()
            );
        else 
            $constraint->maxMessage .= $constraint->field;

        $constraint->maxMessage .= ".";
    }

    private function setMinData(Constraint $constraint)
    {
        if($constraint->min == null)
            return;

        $constraint->minMessage = "Please enter at least {$constraint->min} ";
        if($constraint->field == null)
            $constraint->minMessage .= Str::spacesBeforeUpper(
                $this->context->getPropertyPath()
            );
        else 
            $constraint->minMessage .= $constraint->field;

        $constraint->minMessage .= ".";
    }
}
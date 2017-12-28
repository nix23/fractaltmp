<?php
namespace Wasm\FormBundle\Validator\Constraints\Symfony;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\LengthValidator as LengthConstraintValidator;
use Wasm\UtilBundle\Util\Str;

class LengthValidator extends LengthConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $this->setMaxData($constraint);
        $this->setMinData($constraint);

        parent::validate($value, $constraint);
    }

    private function setMaxData(Constraint $constraint)
    {
        $constraint->maxMessage = "Max ";
        if($constraint->field == null)
            $constraint->maxMessage .= Str::spacesBeforeUpper(
                $this->context->getPropertyPath()
            );
        else 
            $constraint->maxMessage .= $constraint->field;

        $constraint->maxMessage .= " length is {$constraint->max} chars.";
    }

    private function setMinData(Constraint $constraint)
    {
        if($constraint->min == null)
            return;

        $constraint->minMessage = "Min ";
        if($constraint->field == null)
            $constraint->minMessage .= Str::spacesBeforeUpper(
                $this->context->getPropertyPath()
            );
        else 
            $constraint->minMessage .= $constraint->field;

        $constraint->minMessage .= " length is {$constraint->min} chars.";
    }
}
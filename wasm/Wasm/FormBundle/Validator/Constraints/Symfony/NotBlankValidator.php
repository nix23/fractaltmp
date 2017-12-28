<?php
namespace Wasm\FormBundle\Validator\Constraints\Symfony;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlankValidator as NotBlankConstraintValidator;
use Wasm\UtilBundle\Util\Str;

class NotBlankValidator extends NotBlankConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $constraint->message = "Please enter ";
        if($constraint->field == null)
            $constraint->message .= Str::spacesBeforeUpper(
                $this->context->getPropertyPath()
            );
        else 
            $constraint->message .= $constraint->field;

        $constraint->message .= ".";
        parent::validate($value, $constraint);
    }
}
<?php
namespace Wasm\FormBundle\Validator\Constraints\Symfony;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidator;
use Wasm\UtilBundle\Util\Str;

class NotBlankNumberValidator extends ConstraintValidator
{
    private $validator;

    public function __construct($validator)
    {
        $this->validator = $validator;
    }

    public function validate($value, Constraint $constraint)
    {
        if($this->isBlank($value) || (int)$value === 0) {
            $this->context->buildViolation($this->getErrMsg($constraint))
                 ->addViolation();
        }
    }

    private function isBlank($val)
    {
        $notBlankConstraint = new NotBlank();
        $errors = $this->validator->validate($val, $notBlankConstraint);

        return (count($errors) > 0);
    }

    private function getErrMsg(Constraint $constraint)
    {
        $message = "Please enter ";
        if($constraint->field == null)
            $message .= Str::spacesBeforeUpper(
                $this->context->getPropertyPath()
            );
        else 
            $message .= $constraint->field;

        $message .= ".";

        return $message;
    }
}
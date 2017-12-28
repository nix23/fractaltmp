<?php
namespace Wasm\FormBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidator;
use Wasm\UtilBundle\Util\Str;

class IsValidEndDateValidator extends ConstraintValidator
{
    private $validator;

    public function __construct($validator)
    {
        $this->validator = $validator;
    }

    // @todo -> Make isPresent optional + correct msgs if is optional
    public function validate($entity, Constraint $constraint)
    {
        if(!$entity->isPresent()) {
            if($this->isBlank($entity->getEndMonth())) {
                $this->context
                     ->buildViolation($this->getMonthErrMsg($constraint))
                     ->atPath('endMonth')
                     ->addViolation();
            }

            if($this->isBlank($entity->getEndYear())) {
                $this->context
                    ->buildViolation($this->getYearErrMsg($constraint))
                    ->atPath('endYear')
                    ->addViolation();
            }
        }
    }

    private function isBlank($val)
    {
        $notBlankConstraint = new NotBlank();
        $errors = $this->validator->validate($val, $notBlankConstraint);

        return (count($errors) > 0);
    }

    private function getMonthErrMsg(Constraint $constraint)
    {
        $message = "Please select end month or mark as present";
        if($constraint->monthMsg != null)
            $message = $constraint->monthMsg;

        return $message . ".";
    }

    private function getYearErrMsg(Constraint $constraint)
    {
        $message = "Please select end year or mark as present";
        if($constraint->yearMsg != null)
            $message = $constraint->monthMsg;

        return $message . ".";
    }
}
<?php
namespace Wasm\FormBundle\Validator\Constraints\Security;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Wasm\UtilBundle\Util\Str;

class IsEnoughStrongValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if(Str::isBlank($value))
            return;

        $containsLetter = preg_match('/[\p{L}]/u', $value);
        $containsDigit  = preg_match('/[\p{N}]/u', $value);

        if($containsLetter && $containsDigit)
            return;

        $msg = "Password must contain at least one letter and number.";

        $this->context->buildViolation($constraint->message)
            ->setParameter("%message%", $msg)
            ->addViolation();
    }
}
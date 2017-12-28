<?php
namespace Wasm\FormBundle\Validator\Constraints\Entity;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Wasm\UtilBundle\Util\Str;

class IsUniqueValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        if(Str::isBlank($value))
            return;

        // @todo -> Move to repo???
        $prop = $this->context->getPropertyPath();

        $q = $this->em->createQuery("
            SELECT COUNT(e.id) FROM " . $constraint->repo . " e WHERE
            e.$prop = :propVal
        ")->setParameter('propVal', $value);

        if($q->getSingleScalarResult() == 0)
            return;

        $msg = $constraint->errMessage;
        if(Str::isBlank($msg))
            $msg = Str::ucfirst($prop) . " is already registred.";

        $this->context->buildViolation($constraint->message)
            ->setParameter("%message%", $msg)
            ->addViolation();
    }
}
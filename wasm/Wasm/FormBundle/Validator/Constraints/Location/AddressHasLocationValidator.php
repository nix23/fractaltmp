<?php
namespace Wasm\FormBundle\Validator\Constraints\Location;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Wasm\UtilBundle\Util\Str;

// @todo -> Rmp (Replaced with code in store)
class AddressHasLocationValidator extends ConstraintValidator
{
    private $geocoder;
    private $zipCodeRepo;

    public function __construct($geocoder, EntityManager $em)
    {
        $this->geocoder = $geocoder;
        $this->zipCodeRepo = $em->getRepository("NtechCoreBundle:ZipCode");
    }

    public function validate($object, Constraint $constraint)
    {
        if(Str::isBlank($object->address))
            return;

        $zipCode = $this->zipCodeRepo->find($object->zipCode);
        if(!$zipCode) {
            $this->registerViolation($constraint);
            return;
        }
        
        $coords = $this->geocoder->getCoords(
            $object->address, $zipCode->getZipCode()
        );
        if($coords != null)
            return;

        $this->registerViolation($constraint);
    }

    private function registerViolation(Constraint $constraint)
    {
        $msg = "Can't get coordinates from your address. ";
        $msg .= "Please enter precise restaurant address and zip code.";

        $this->context->buildViolation($constraint->message)
            ->setParameter("%message%", $msg)
            ->atPath("address")
            ->addViolation();
    }
}
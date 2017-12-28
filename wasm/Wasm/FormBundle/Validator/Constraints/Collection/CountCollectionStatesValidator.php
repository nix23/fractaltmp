<?php
namespace Wasm\FormBundle\Validator\Constraints\Collection;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\CountValidator as CountConstraintValidator;
use Wasm\UtilBundle\Util\Str;

// @todo -> Merge with FormBundle CountValidator?
// @todo -> Fix plural(Check constraint struct + docs)
// @todo -> Fix spaces count(Before singular/plural word)
class CountCollectionStatesValidator extends CountConstraintValidator
{
    private $collectionValidator;

    public function __construct($collectionValidator)
    {
        $this->collectionValidator = $collectionValidator;
    }

    public function validate($object, Constraint $constraint)
    {
        // If formData == null -> It is each 'State' entry
        // validation. (We should check 'global' collection errors only once)
        if(!$this->collectionValidator->hasFormData())
            return;

        $formData = $this->collectionValidator->getFormData();
        $value = $formData->form;

        $this->setMaxData($formData, $constraint);
        $this->setMinData($formData, $constraint);

        parent::validate($value, $constraint);
    }

    // @todo -> Move to get formIdSeparator
    private function getDefFieldName($formData)
    {
        $parts = explode("_", $formData->formId);
        return $parts[count($parts) - 1];
    }

    private function setMaxData($formData, Constraint $constraint)
    {
        if($constraint->max == null)
            return;

        $constraint->maxMessage = "Please enter up to {$constraint->max} ";
        if($constraint->field == null)
            $constraint->maxMessage .= Str::spacesBeforeUpper(
                $this->getDefFieldName($formData)
            );
        else 
            $constraint->maxMessage .= $constraint->field;

        $constraint->maxMessage .= ".";
    }

    private function setMinData($formData, Constraint $constraint)
    {
        if($constraint->min == null)
            return;

        $constraint->minMessage = "Please enter at least {$constraint->min} ";
        if($constraint->field == null)
            $constraint->minMessage .= Str::spacesBeforeUpper(
                $this->getDefFieldName($formData)
            );
        else 
            $constraint->minMessage .= $constraint->field;

        $constraint->minMessage .= ".";
    }
}
<?php
namespace Wasm\FormBundle\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Wasm\FormBundle\Model\AllErrorData;
use Wasm\FormBundle\Model\CollectionErrorData;
use Wasm\FormBundle\Model\ErrorData;
use Wasm\UtilBundle\Util\Str;

class Validator
{
    private $responseFactory;
    private $formFactory;
    private $validator;
    private $collectionValidator;

    public function __construct(
        $apiResponseFactory, 
        $formFactory,
        ValidatorInterface $validator,
        $collectionValidator
    ) {
        $this->responseFactory = $apiResponseFactory;
        $this->formFactory = $formFactory;
        $this->validator = $validator;
        $this->collectionValidator = $collectionValidator;
    }

    public function validate($formsData = array())
    {
        $allErrors = new AllErrorData();

        foreach($formsData as $formData) {
            if(!$formData->manyStates) {
                $allErrors->push($this->validateForm($formData, $formData->form));
            }
            else {
                $collectionErrorData = new CollectionErrorData($formData);
                $this->validateAllCollectionForms($formData, $collectionErrorData);
                $this->validateEachCollectionForm($formData, $collectionErrorData);

                $allErrors->push($collectionErrorData);
            }
        }

        if($allErrors->hasErrors())
            $this->responseFactory->throwFormValidationException(
                $allErrors->getErrors()
            );
    }

    private function validateAllCollectionForms($formData, $collectionErrorData)
    {
        $virtualRootForm = $this->formFactory->createForm(clone $formData);
        $this->collectionValidator->setFormData($formData);

        $errors = $this->validator->validate($virtualRootForm);
        if(count($errors) == 0)
            return;

        foreach($errors as $err) {
            $prop = $err->getPropertyPath();
            if(!$this->isGlobalFormError($prop))
                continue;

            $collectionErrorData->addFormError($err->getMessage());
        }
    }

    private function validateEachCollectionForm($formData, $collectionErrorData)
    {
        foreach($formData->form as $form) 
            $collectionErrorData->push($this->validateForm($formData, $form));
    }

    private function validateForm($formData, $form)
    {
        $errorData = new ErrorData($formData);

        $errors = $this->validator->validate($form);
        if(count($errors) == 0)
            return $errorData;

        foreach($errors as $err) {
            $prop = $err->getPropertyPath();
            if($this->isGlobalFormError($prop)) {
                $errorData->addFormError($err->getMessage());
                continue;
            }

            if(!in_array($prop, $formData->storeFields))
                continue;

            $errorData->addError($prop, $err->getMessage());
        }

        return $errorData;
    }

    private function isGlobalFormError($prop)
    {
        return Str::isBlank($prop);
    }
}
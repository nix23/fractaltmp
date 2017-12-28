<?php
namespace Wasm\FormBundle\Model;

use Wasm\FormBundle\Model\ErrorData;

class CollectionErrorData
{
    public $formId;
    public $formErrors = array();
    public $errors = array();

    public function __construct($formData)
    {
        $this->formId = $formData->formId;
    }

    public function push($errorData)
    {
        $this->errors[] = $errorData;
    }

    public function addFormError($error)
    {
        $this->formErrors[] = $error;
    }

    public function hasErrors()
    {
        foreach($this->errors as $errorData) {
            if($errorData->hasErrors())
                return true;
        }

        if(count($this->formErrors) > 0)
            return true;

        return false;
    }

    public function getErrors()
    {
        $errors = array();
        if(count($this->formErrors) > 0)
            $errors[ErrorData::FORM_ERRORS_KEY] = $this->formErrors;

        $errors["errors"] = array();
        foreach($this->errors as $errorData)
            $errors["errors"][] = $errorData->getErrors();

        return $errors;
    }
}
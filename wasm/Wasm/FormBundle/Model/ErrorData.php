<?php
namespace Wasm\FormBundle\Model;

/*
    $allErrors = array(
        "FormId1" => array(
            "__form" => array(err1, err2, ..., errN),
            "propA" => array(err1, err2, ..., errN),
            "propB" => array(err1, err2, ..., errN),
        ),
        "FormId2" => array(
            "__form" => array(err1, err2, ..., errN),
            "propA" => array(err1, err2, ..., errN),
            "propB" => array(err1, err2, ..., errN),
        ),
        ...,
        "CollectionFormId1" => array(
            "__form" => array(err1, err2, ..., errN),
            "errors" => array(
                array(
                    "__form" => array(err1, err2, ..., errN),
                    "propA" => array(err1, err2, ..., errN),
                    "propB" => array(err1, err2, ..., errN),
                ),
                array(
                    "__form" => array(err1, err2, ..., errN),
                    "propA" => array(err1, err2, ..., errN),
                    "propB" => array(err1, err2, ..., errN),
                ),
            ),
        ),
    );
*/

class ErrorData
{
    const FORM_ERRORS_KEY = "__form";

    public $formId;
    public $formErrors = array();
    public $errors = array();

    public function __construct($formData)
    {
        $this->formId = $formData->formId;
    }

    public function addFormError($error)
    {
        $this->formErrors[] = $error;
    }

    public function hasErrors()
    {
        return (count($this->formErrors) > 0) ||
               (count($this->errors) > 0);
    }

    public function addError($prop, $error)
    {
        if(!array_key_exists($prop, $this->errors))
            $this->errors[$prop] = array();

        $this->errors[$prop][] = $error;
    }

    public function getErrors()
    {
        if(array_key_exists(self::FORM_ERRORS_KEY, $this->errors))
            throw new \Exception(self::FORM_ERRORS_KEY . " is a reserved form property.");

        if(count($this->formErrors) > 0)
            $this->errors[self::FORM_ERRORS_KEY] = $this->formErrors;

        return $this->errors;
    }
}
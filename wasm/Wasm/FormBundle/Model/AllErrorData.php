<?php
namespace Wasm\FormBundle\Model;

class AllErrorData
{
    public $allErrors = array();

    public function push($errorData)
    {
        $this->allErrors[] = $errorData;
    }

    public function hasErrors()
    {
        foreach($this->allErrors as $errorData) {
            if($errorData->hasErrors())
                return true;
        }

        return false;
    }

    public function getErrors()
    {
        $errorsByFormIds = array();
        foreach($this->allErrors as $errorData) {
            if(!$errorData->hasErrors())
                continue;

            $errorsByFormIds[$errorData->formId] = $errorData->getErrors();
        }

        return $errorsByFormIds;
    }
}
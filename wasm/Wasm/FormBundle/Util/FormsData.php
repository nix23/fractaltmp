<?php
namespace Wasm\FormBundle\Util;

class FormsData
{
    public static function getFormDataByFormId($formId, $formsData)
    {
        if(!is_array($formsData))
            return $formsData;

        foreach($formsData as $formData) {
            if($formData->formId == $formId)
                return $formData;
        }
    }
}
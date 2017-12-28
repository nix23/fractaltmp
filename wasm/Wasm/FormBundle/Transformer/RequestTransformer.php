<?php
namespace Wasm\FormBundle\Transformer;

use Wasm\FormBundle\Model\FormData;

class RequestTransformer
{
    const NAMESPACE_SEPARATOR_MARKER = "_";
    const EXCEPT_FIELD_MARKER = "!";

    private $classReader;
    private $formReader;

    public function __construct($classReader, $formReader)
    {
        $this->classReader = $classReader;
        $this->formReader = $formReader;
    }

    public function transformToFormsData($forms = array())
    {
        $formsData = array();
        foreach($forms as $key => $val) {
            $formData = new FormData();

            if(is_array($val))
                $formId = $key;
            else 
                $formId = $val;

            $formData->formId = $formId;
            $formData->formClass = $this->getFormClass($formId);
            $formData->storeClass = $this->getStoreClass($formId);
            
            $this->formReader->readFormAnnotations($formData);
            if(is_array($val))
                $this->transformFormSettings($forms[$key], $formData);

            $formsData[] = $formData;
        }

        return $formsData;
    }

    private function transformFormSettings($formSettings, $formData)
    {
        if(array_key_exists("fields", $formSettings)) {
            list($only, $except) = $this->getFieldsToReadLimitations(
                $formSettings["fields"]
            );

            $formData->onlyFields = $only;
            $formData->exceptFields = $except;
        }

        if(array_key_exists("state", $formSettings)) {
            $formData->state = $formSettings["state"];
            if(!$formData->manyStates)
                $formData->stateFields = array_keys($formData->state);
            else {
                if(count($formData->state) > 0)
                    $formData->stateFields = array_keys($formData->state[0]);
            }
        }

        if(array_key_exists("entityId", $formSettings)) {
            $formData->entityId = $formSettings["entityId"];
        }
    }

    private function getFieldsToReadLimitations($fieldNames)
    {
        $onlyFields = array_filter($fieldNames, function($fieldName) {
            return $fieldName[0] != self::EXCEPT_FIELD_MARKER;
        });

        $exceptFields = array_filter($fieldNames, function($fieldName) {
            return $fieldName[0] == self::EXCEPT_FIELD_MARKER;
        });
        $exceptFields = array_map(function($val) {
            return str_replace(self::EXCEPT_FIELD_MARKER, "", $val);
        }, $exceptFields);

        return array($onlyFields, $exceptFields);
    }

    private function getFormClass($formId)
    {
        list($namespaceId, $bundleId, $formId) = $this->getFormIdParts($formId);
        $formClass = $this->classReader->getFormClassPrefix($namespaceId, $bundleId);

        $formClass .= str_replace(
            self::NAMESPACE_SEPARATOR_MARKER, "\\", $formId
        ) . $this->classReader->getFormClassPostfix();

        return $formClass;
    }

    private function getStoreClass($formId)
    {
        list($namespaceId, $bundleId, $formId) = $this->getFormIdParts($formId);
        $storeClass = $this->classReader->getStoreClassPrefix($namespaceId, $bundleId);

        $storeClass .= str_replace(
            self::NAMESPACE_SEPARATOR_MARKER, "\\", $formId
        ) . $this->classReader->getStoreClassPostfix();

        return $storeClass;
    }

    private function getFormIdParts($formId)
    {
        $parts = explode(self::NAMESPACE_SEPARATOR_MARKER, $formId);
        
        $namespace = $parts[0];
        $bundle = $parts[1];
        $form = null;

        if(count($parts) > 2)
            $form = implode(
                self::NAMESPACE_SEPARATOR_MARKER,
                array_slice($parts, 2, count($parts) - 1)
            );

        return array($namespace, $bundle, $form);
    }
}
<?php
namespace Wasm\FormBundle\StateFactory;

use Wasm\UtilBundle\Util\Str;
use Wasm\FormBundle\Util\FormsData;

class StateFactory
{
    private $em;
    private $uploader;
    private $user;
    private $entityFactory;
    private $entityTransformer;

    public function __construct(
        $em, 
        $uploader,
        $um, 
        $entityFactory,
        $entityTransformer
    ) {
        $this->em = $em;
        $this->uploader = $uploader;
        $this->user = $um->getUser();
        $this->entityFactory = $entityFactory;
        $this->entityTransformer = $entityTransformer;
    }

    public function createFormInitialStates($formFields, $formsData)
    {
        foreach($formFields as $formId => &$fields) {
            $formData = FormsData::getFormDataByFormId($formId, $formsData);
            $formObject = new $formData->formClass();
            $formEntity = $this->entityFactory->createFormEntityByEntityId(
                $formObject, $formData
            );

            $fields["state"] = array();
            $fields["errors"] = null;
            $fields["manyStates"] = $formData->manyStates;
            $fields["statePrototype"] = array();

            foreach($fields["fields"] as $key => $fieldData) {
                $fieldValue = $this->createFieldInitialStateWithInitialData(
                    $fields, 
                    $key, 
                    null, 
                    $formObject, 
                    $fieldData,
                    $formData->getEntityId()
                );
                $fields["statePrototype"][$fieldData["name"]] = $fieldValue;
            }

            if(!$formData->manyStates) {
                foreach($fields["fields"] as $key => $fieldData) {
                    $fieldValue = $this->createFieldInitialStateWithInitialData(
                        $fields, 
                        $key, 
                        $formEntity, 
                        $formObject, 
                        $fieldData,
                        $formData->getEntityId()
                    );
                    $fields["state"][$fieldData["name"]] = $fieldValue;
                }
            }
            else {
                $states = array();
                if($formEntity !== null) {
                    foreach($formEntity as $formStateEntity) {
                        $state = array();

                        foreach($fields["fields"] as $key => $fieldData) {
                            $fieldValue = $this->createFieldInitialStateWithInitialData(
                                $fields, 
                                $key, 
                                $formStateEntity, 
                                $formObject, 
                                $fieldData,
                                $formData->getEntityId()
                            );
                            $state[$fieldData["name"]] = $fieldValue;
                        }

                        $states[] = $state;
                    }
                }

                $fields["state"] = $states;
            }
        }
        
        return $formFields;
    }

    private function createFieldInitialStateWithInitialData(
        &$fields,
        $fieldIndex,
        $formEntity,
        $formObject,
        $fieldData,
        $entityId
    ) {
        $fieldValue = $this->createFieldInitialState(
            $formEntity, $formObject, $fieldData, $entityId
        );

        // Gets extra data(Label + extra props) for 
        // select/etc... initial state
        if(array_key_exists("dataSourceId", $fieldData))
            $fields["fields"][$fieldIndex]["data"] = $this
                ->addFieldInitialStateData(
                    $formEntity, 
                    $formObject,
                    $fieldData["data"],
                    $fieldData["dataSourceId"],
                    (array_key_exists("dataSourceProps", $fieldData))
                        ? $fieldData["dataSourceProps"]
                        : null,
                    $fieldData["name"],
                    $fieldData["type"]
                );

        return $fieldValue;
    }

    private function createFieldInitialState(
        $formEntity,
        $formObject,
        $fieldData,
        $entityId
    ) {
        if($formEntity != null) {
            return $this->initFieldWithEntityValue(
                $formEntity, 
                $formObject,
                $fieldData["name"],
                $fieldData["type"],
                (array_key_exists("data", $fieldData)) ? $fieldData["data"] : array()
            );
        }

        $value = $formObject->$fieldData["name"];
        if($value !== null)
            return $value;

        $getter = "get" . Str::ucfirst($fieldData["name"]);
        $formGetter = $getter . "DefState";

        if(method_exists($formObject, $formGetter)) { 
            $value = $formObject->$formGetter(
                $entityId, $this->em, $this->user
            );
            if($fieldData["type"] == "checkboxes") {
                $value = $this->initCheckboxesField(
                    $fieldData["data"], $value
                );
            }

            return $value;
        }

        return $this->initFieldWithDefaultValue(
            $fieldData["type"], $fieldData
        );
    }

    private function initFieldWithDefaultValue($fieldType, $fieldData)
    {
        if(in_array($fieldType, array(
            "text", 
            "textarea", 
            "phone",
            "hidden", 
            "password", 
            "selectDate",
            "checkboxes",
            "file"
        )))
            return "";

        if($fieldType == "checkbox")
            return 0;

        if($fieldType == "radios") {
            // Auto-select first radio
            if(count($fieldData["data"]) > 0)
                return $fieldData["data"][0]["id"];

            return null;
        }

        if($fieldType == "select" || $fieldType == "selectSearch")
            return null;

        if($fieldType == "selectMultiple" || "selectSearchMultiple")
            return array();

        throw new \Exception("Can't set default value for field $fieldType.");
    }

    private function initFieldWithEntityValue(
        $entity,
        $form,
        $fieldName,
        $fieldType,
        $fieldData
    ) {
        $value = $this->getEntityFieldRawValue(
            $form, $entity, $fieldName, $fieldType
        );
        
        if(in_array($fieldType, array(
            "text", 
            "textarea", 
            "phone",
            "hidden", 
            "password", 
            "checkbox"
        ))) {
            // Protecting from "0" => false casting
            if($fieldType == "checkbox") {
                if(is_numeric($value))
                    return (int)$value;
                else if(is_bool($value))
                    return ($value) ? 1 : 0;
            }

            return $value;
        }

        if($fieldType == "file") {
            return $this->uploader->getUrl($value);
        }

        if($fieldType == "radios") {
            if(is_object($value) && method_exists($value, "getId"))
                return $value->getId();

            return $value;
        }

        if($fieldType == "checkboxes")
            return $this->initCheckboxesField($fieldData, $value);

        if($fieldType == "select" || $fieldType == "selectSearch") {
            if(is_object($value) && method_exists($value, "getId"))
                return $value->getId();

            return $value;
        }

        if($fieldType == "selectDate")
            return $value->format("Y-m-d");

        if($fieldType == "selectMultiple" || $fieldType == "selectSearchMultiple") {
            $vals = array();
            foreach($value as $object)
                $vals[] = $object->getId();

            return $vals;
        }

        throw new \Exception("Can't set initial value for field $fieldType.");
    }

    // Raw = objects for linked entities(1:1, 1:M, M:M)
    private function getEntityFieldRawValue($form, $entity, $fieldName, $fieldType)
    {
        $getter = "get" . Str::ucfirst($fieldName);
        $formGetter = $getter . "State";

        if(method_exists($form, $formGetter)) 
            $value = $form->$formGetter($entity, $this->em, $this->user);
        else if(method_exists($entity, $getter))
            $value = $entity->$getter();
        else {
            $msg = "Can't find method to get initial field state";
            $msg .= " for field '$fieldName, $fieldType' in Entity/Form.";
            throw new \Exception($msg);
        }

        return $value;
    }

    private function initCheckboxesField($fieldData, $value)
    {
        $allOption = array_reduce(
            $fieldData,
            function($allOption, $dataEntry) {
                if($allOption)
                    return $allOption;

                if($dataEntry["type"] == "all")
                    return $dataEntry;

                return null;
            }, 
            null
        );

        if(!$allOption)
            return $value;

        $areAllSelected = array_reduce(
            $fieldData,
            function($areAllSelected, $dataEntry) use ($value) {
                if(!$areAllSelected)
                    return $areAllSelected;

                if($dataEntry["type"] == "all")
                    return $areAllSelected;

                return in_array($dataEntry["id"], $value);
            },
            true
        );

        return array_merge(
            ($areAllSelected) ? array($allOption["id"]) : array(),
            $value
        );
    }

    private function addFieldInitialStateData(
        $entity,
        $form,
        $data, 
        $dataSourceId, 
        $dataSourceProps,
        $fieldName,
        $fieldType
    ) {
        if(Str::isBlank($dataSourceId) || $entity == null)
            return $data;

        $fieldValue = $this->getEntityFieldRawValue(
            $form, $entity, $fieldName, $fieldType
        );

        if(is_object($fieldValue)) {
            $data[] = $this->entityTransformer->transformDataSourceEntity(
                $dataSourceId, $fieldValue, $fieldName, $dataSourceProps
            );
            return $data;
        }

        if(is_array($fieldValue)) {
            foreach($fieldValue as $maybeObject) {
                if(!is_object($maybeObject))
                    continue;

                $data[] = $this->entityTransformer->transformDataSourceEntity(
                    $dataSourceId, $maybeObject, $fieldName, $dataSourceProps
                );
            }
        }

        return $data;
    }
}
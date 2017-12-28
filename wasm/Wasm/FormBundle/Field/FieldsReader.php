<?php
namespace Wasm\FormBundle\Field;

use Doctrine\Common\Annotations\Reader;
use Wasm\UtilBundle\Util\Str;

class FieldsReader
{
    const ANNOTATION_CLASS_PREFIX = "\\Wasm\\FormBundle\\Field\\Annotation\\";

    private $annotationReader;
    private $fieldDataResolver;

    public function __construct(
        Reader $annotationReader,
        $fieldDataResolver
    ) {
        $this->annotationReader = $annotationReader;
        $this->fieldDataResolver = $fieldDataResolver;
    }

    // @todo -> Auto add ids on manyStates???
    // (Example: requested fields name and age, id is required any way!!!)
    // (Also auto add to getState when not all fields were requested!!!)
    public function findFieldsToStore($formsData = array())
    {
        foreach($formsData as $formData)
            $formData->storeFields = $this->getFormFieldNames($formData);
    }

    public function getFieldsByFormsData($formsData = array())
    {
        $formFields = array();
        foreach($formsData as $formData) {
            $formFields[$formData->formId] =
                $this->getFormFields($formData);
        }

        return $formFields;
    }

    // Used in findFieldsToStore(On Post/Validate)
    private function getFormFieldNames($formData)
    {
        $formFields = $this->readFilteredFields($formData);
        return array_reduce($formFields, function($res, $formField) {
            $res[] = $formField["fieldName"];
            return $res;
        }, array());
    }

    // Used to get form definitions(On Get requests)
    public function getFormFields($formData)
    {
        $formFields = $this->readFilteredFields($formData);

        $fieldsData = array();
        foreach($formFields as $formField) {
            $annotation = $formField["fieldAnnotation"];
            $fieldData = array(
                "name" => $formField["fieldName"],
                "type" => $annotation->getType(),
                "label" => $this->getFieldLabel(
                    $annotation->getLabel(),
                    $formField["fieldName"]
                ),
                "placeholder" => $annotation->getPlaceholder(),
            );
            if(Str::isNotBlank($annotation->getUploadType()))
                $fieldData["uploadType"] = $annotation->getUploadType();

            $data = $this->fieldDataResolver->getData(
                $formData,
                $formField["fieldName"],
                $annotation->getType(),
                $annotation->getDataSource(),
                $annotation->getDataSourceId(),
                $annotation->getDataSourceParams(),
                $annotation->getDataSourceAllOption(),
                $annotation->getDataSourceProps()
            );
            foreach($data as $key => $val)
                $fieldData[$key] = $val;

            $fieldsData[] = $fieldData;
        }

        return array(
            "fields" => $fieldsData,
        );
    }

    private function getFieldLabel($label, $fieldName)
    {
        if(Str::isNotBlank($label))
            return $label;

        return Str::ucfirst(
            Str::spacesBeforeUpper($fieldName)
        );
    }

    private function readFilteredFields($formData)
    {
        return $this->filterOnlyFields(
            $formData, 
            $this->filterExceptFields(
                $formData,
                $this->readFormFields($formData)
            )
        );
    }

    // @todo -> Add cache?(For TransformFormCmd -> Many calls in Validators?)
    // Or cache at global level(share between validators and stores)
    private function readFormFields($formData)
    {
        $objectReflection = new \ReflectionClass($formData->formClass);
        $formClassProps = $objectReflection->getProperties();

        $formFields = array();
        foreach($formClassProps as $formClassProp) {
            $prop = new \ReflectionProperty(
                $formData->formClass, $formClassProp->name
            );
            $propAnnotations = $this->annotationReader
                ->getPropertyAnnotations($prop);

            $fieldAnnotation = $this->filterFieldAnnotation(
                $propAnnotations
            );

            if($fieldAnnotation == null)
                continue;

            if($fieldAnnotation->isVirtual())
                $formData->virtualFields[] = $formClassProp->name;

            $formFields[] = array(
                "fieldName" => $formClassProp->name,
                "fieldAnnotation" => $fieldAnnotation,
            );
        }

        return $formFields;
    }

    private function filterExceptFields($formData, $formFields)
    {
        return array_filter($formFields, function($formField) use ($formData) {
            return !in_array($formField["fieldName"], $formData->exceptFields);
        });
    }

    private function filterOnlyFields($formData, $formFields)
    {
        if(count($formData->onlyFields) == 0)
            return $formFields;

        $onlyFields = array();
        foreach($formData->onlyFields as $onlyFieldName) {
            foreach($formFields as $formField) {
                if($formField["fieldName"] == $onlyFieldName) {
                    $onlyFields[] = $formField;
                    break;
                }
            }
        }

        return $onlyFields;
    }

    private function filterFieldAnnotation($annotations)
    {
        foreach($annotations as $annotation) {
            $annotationClass = self::ANNOTATION_CLASS_PREFIX . "Field";

            if($annotation instanceof $annotationClass)
                return $annotation;
        }

        return null;
    }
}
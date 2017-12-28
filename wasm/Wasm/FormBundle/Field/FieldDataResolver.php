<?php
namespace Wasm\FormBundle\Field;

use Wasm\FormBundle\Model\FormData;
use Wasm\UtilBundle\Util\Str;

class FieldDataResolver
{
    private $container;
    private $em;
    private $entityTransformer;

    public function __construct(
        $container,
        $em,
        $entityTransformer
    ) {
        $this->container = $container;
        $this->em = $em;
        $this->entityTransformer = $entityTransformer;
    }

    public function getCollectionData($fields)
    {
        $emptyFormData = new FormData();

        $data = array();
        foreach($fields as $field) {
            $dataRows = $this->getData(
                $emptyFormData,
                $field["fieldName"],
                $field["fieldType"],
                $field["dataSource"],
                null,
                $field["dataSourceParams"],
                false
            )["data"];

            $data[] = array(
                "fieldName" => $field["fieldName"],
                "fieldData" => $dataRows,
            );
        }

        return $data;
    }

    public function getData(
        $formData,
        $fieldName, 
        $fieldType, 
        $dataSource, 
        $dataSourceId,
        $dataSourceParams,
        $dataSourceAllOption,
        $dataSourceProps = null
    ) {
        if($dataSource == null && $dataSourceId == null)
            return array();

        // if($fieldType == "select" ||
        //    $fieldType == "selectMultiple" ||
        //    $fieldType == "selectSearch" ||
        //    $fieldType == "selectSearchMultiple" ||
        //    $fieldType == "checkbox" ||
        //    $fieldType == "checkboxes") {
            $data = array(
                "data" => $this->resolveDataCollection(
                    $formData,
                    $fieldName, 
                    $fieldType,
                    $dataSource,
                    $dataSourceParams,
                    $dataSourceAllOption
                ),
                "dataSourceId" => $dataSourceId,
            );

            // Used only in Backend to get label for initial state
            // for dataSourceId prop
            if($dataSourceProps != null)
                $data["dataSourceProps"] = $dataSourceProps;

            return $data;
        // }

        // return array();
    }

    private function resolveDataCollection(
        $formData,
        $fieldName, 
        $fieldType,
        $dataSourceIdentity,
        $dataSourceParams,
        $dataSourceAllOption
    ) {
        if($dataSourceIdentity == null)
            return array();

        $dataSource = $this->createDataSourceClass(
            $dataSourceIdentity
        );
        $this->initializeDataSourceClass(
            $dataSource, $this->em, $formData
        );

        $getter = "get" . Str::ucfirst($fieldName) . "Data";
        if(method_exists($dataSource, $getter))
            //$collection = $dataSource->$getter($dataSourceParams);
            $maybeEntities = $dataSource->$getter($dataSourceParams);
        else
            //$collection = $dataSource->getData($dataSourceParams);
            $maybeEntities = $dataSource->getData($dataSourceParams);

        return $this->injectOptionTypes(
            //$collection->buildData(),
            $this->transformEntitiesToDataArray(
                $dataSource, $maybeEntities, $fieldName
            ),
            $fieldType,
            $dataSourceAllOption
        );
    }

    public function createDataSourceClass($dataSourceIdentity)
    {
        if($dataSourceIdentity[0] == "@") {
            return $this->container->get(
                ltrim($dataSourceIdentity, "@")
            );
        }
        else {
            return new $dataSourceIdentity();
        }
    }

    public function initializeDataSourceClass(
        $dataSource, 
        EntityManager $em,
        $formData
    ) {
        if(!method_exists($dataSource, "initialize"))
            return;

        $dataSource->initialize($em, $formData);
    }

    private function isArrayOfEntities($maybeEntities)
    {
        if(!is_array($maybeEntities) ||
           count($maybeEntities) == 0)
            return $maybeEntities;

        $isSecondItem = false;
        $isArrayOfEntities = false;
        foreach($maybeEntities as $maybeEntity) {
            if($isSecondItem)
                break;

            if(is_object($maybeEntity))
                $isArrayOfEntities = true;

            $isSecondItem = true;
        }

        return $isArrayOfEntities;
    }

    private function transformEntitiesToDataArray(
        $dataSource,
        $maybeEntities,
        $fieldName
    ) { 
        if(!$this->isArrayOfEntities($maybeEntities))
            return $maybeEntities;

        $data = array();
        foreach($maybeEntities as $entity)
            $data[] = $this->entityTransformer->transformDataSourceEntity(
                $dataSource, $entity, $fieldName
            );

        return $data;
    }

    private function injectOptionTypes($data, $fieldType, $allOption)
    {
        if(!$allOption)
            return $data;

        if($fieldType != "checkboxes")
            return $data;

        return array_merge(
            array(
                array(
                    "id" => -1,
                    "label" => "All",
                    "type" => "all"
                )
            ),
            array_values(
                array_map(function($dataEntry) {
                    $dataEntry["type"] = "def";
                    return $dataEntry;
                }, $data)
            )
        );
    }
}
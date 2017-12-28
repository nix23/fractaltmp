<?php
namespace Wasm\FormBundle\Transformer;

use Doctrine\ORM\EntityManager;
use Wasm\UtilBundle\Util\Str;

class EntityTransformer
{
    // $dataSourceProps is used to get label or additional props
    // for dataSourceId delayed datas. (On createState for entity)
    // Example::zipCodeId. (Should get zipCode label on form editing)
    public function transformDataSourceEntity(
        $dataSource, 
        $entity, 
        $fieldName,
        $dataSourceProps = null
    ) {
        $extraPropsGetter = "get" . Str::ucfirst($fieldName) . "DataProps";
        $dataEntry = array();

        if(method_exists($entity, "getId"))
            $dataEntry["id"] = $entity->getId();
        // @todo -> Rm this?(Can be used wrong if entity has label field)
        if(method_exists($entity, "getLabel"))
            $dataEntry["label"] = $entity->getLabel();

        if(!method_exists($dataSource, $extraPropsGetter) &&
           !method_exists($dataSourceProps, $extraPropsGetter))
            return $dataEntry;

        if(method_exists(($dataSource), $extraPropsGetter))
            $transformData = $dataSource::$extraPropsGetter($entity);
        else
            $transformData = $dataSourceProps::$extraPropsGetter($entity);

        foreach($transformData as $prop => $value)
            $dataEntry[$prop] = $value;
        
        return $dataEntry;
    }
}
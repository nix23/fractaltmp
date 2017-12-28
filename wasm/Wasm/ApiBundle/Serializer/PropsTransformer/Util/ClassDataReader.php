<?php
namespace Wasm\ApiBundle\Serializer\PropsTransformer\Util;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\Annotation\Groups;

class ClassDataReader
{
    const CLASS_PREFIX = "\\Wasm\\ApiBundle\\Serializer\\PropsTransformer\\";
    const ANNOTATION_DIR_POSTFIX = "Wasm/ApiBundle/Serializer/PropsTransformer/Annotation";

    private $annotationReader;
    private $rootDir;

    private $classNames = array();

    private $propsDataCache = array();
    private $methodsDataCache = array();

    public function __construct(Reader $annotationReader, $rootDir)
    {
        $this->annotationReader = $annotationReader;
        $this->rootDir = $rootDir;

        $this->readAllPropTransformerClassNames();
    }

    private function readAllPropTransformerClassNames()
    {
        $dir = $this->rootDir . "/../../" . self::ANNOTATION_DIR_POSTFIX;

        $finder = new Finder();
        $finder->files()->in($dir);

        $classNames = array();
        foreach($finder as $file) {
            $name = $file->getRelativePathname();
            $nameParts = explode(".", $name);
            
            $classNames[] = $nameParts[0];
        }
        
        $this->classNames = $classNames;
    }

    public function getPropsData($object)
    {
        $objectReflection = new \ReflectionClass($object);
        if(array_key_exists($objectReflection->name, $this->propsDataCache))
            return $this->propsDataCache[$objectReflection->name];

        $propsData = $this->getClassPropsData($objectReflection);
        // Parent check(Doctrine proxies fix)
        $parentPropsData = ($objectReflection->getParentClass())
            ? $this->getClassPropsData($objectReflection->getParentClass())
            : array();

        $propsData = array_merge(
            $propsData,
            $parentPropsData
        );
        
        $this->propsDataCache[$objectReflection->name] = $propsData;

        return $propsData;
    }

    private function getClassPropsData($objectReflection)
    {
        $classProps = $objectReflection->getProperties();
        $propsData = array();

        foreach($classProps as $classPropData) {
            //$prop = new \ReflectionProperty($object, $classPropData->name);
            $prop = $classPropData;
            $allAnnotations = $this->annotationReader->getPropertyAnnotations($prop);
            $annotations = $this->filterPropTransformerAnnotations(
                $allAnnotations
            );
            $groupAnnotations = $this->filterSerializerGroupsAnnotations(
                $allAnnotations
            );

            $propsData[] = array(
                "prop" => $classPropData->name,
                "propReflection" => $prop,
                "annotations" => $annotations,
                "hasTransformerAnnotations" => (count($annotations) > 0),
                "hasSerializerGroupsAnnotations" => (count($groupAnnotations) > 0),
            );
        }

        return $propsData;
    }

    public function getMethodsData($object)
    {
        $objectReflection = new \ReflectionClass($object);
        if(array_key_exists($objectReflection->name, $this->methodsDataCache))
            return $this->methodsDataCache[$objectReflection->name];

        $methodsData = $this->getClassMethodsData($objectReflection);
        // Parent check(Doctrine proxies fix)
        $parentMethodsData = ($objectReflection->getParentClass())
            ? $this->getClassMethodsData($objectReflection->getParentClass())
            : array();

        $methodsData = array_merge(
            $methodsData,
            $parentMethodsData
        );

        $this->methodsDataCache[$objectReflection->name] = $methodsData;

        return $methodsData;
    }

    private function getClassMethodsData($objectReflection)
    {
        $classMethods = $objectReflection->getMethods();
        $methodsData = array();

        foreach($classMethods as $classMethodData) {
            //$method = new \ReflectionMethod($object, $classMethodData->name);
            $method = $classMethodData;
            $allAnnotations = $this->annotationReader->getMethodAnnotations($method);
            $annotations = $this->filterPropTransformerAnnotations(
                $allAnnotations
            );
            $groupAnnotations = $this->filterSerializerGroupsAnnotations(
                $allAnnotations
            );

            $methodsData[] = array(
                "method" => $classMethodData->name,
                "methodReflection" => $method,
                "annotations" => $annotations,
                "hasTransformerAnnotations" => (count($annotations) > 0),
                "hasSerializerGroupsAnnotations" => (count($groupAnnotations) > 0),
            );
        }

        return $methodsData;
    }

    private function filterPropTransformerAnnotations($annotations)
    {
        $propTransformerAnnotations = array();

        foreach($annotations as $annotation) {
            foreach($this->classNames as $className) {
                $prefix = self::CLASS_PREFIX . "Annotation\\";
                $annotationClass = $prefix . $className;

                if($annotation instanceof $annotationClass)
                    $propTransformerAnnotations[] = $annotation;
            }
        }

        return $propTransformerAnnotations;
    }

    private function filterSerializerGroupsAnnotations($annotations)
    {
        $serializerAnnotations = array();

        foreach($annotations as $annotation) {
            if($annotation instanceof Groups)
                $serializerAnnotations[] = $annotation;
        }

        return $serializerAnnotations;
    }

    public function getTransformerClassNameByAnnotation(
        $object, 
        $propName, 
        $prop,
        $annotation
    ) {
        //$prop = new \ReflectionProperty($object, $propName);
        $annotations = $this->filterPropTransformerAnnotations(
            $this->annotationReader->getPropertyAnnotations($prop)
        );

        foreach($annotations as $annotation) {
            foreach($this->classNames as $className) {
                $prefix = self::CLASS_PREFIX . "Annotation\\";
                $annotationClass = $prefix . $className;

                if($annotation instanceof $annotationClass)
                    return $className;
            }
        }
    }

    public function getTransformerClassNameByMethodAnnotation(
        $object, 
        $methodName, 
        $method,
        $annotation
    ) {
        //$method = new \ReflectionMethod($object, $methodName);
        $annotations = $this->filterPropTransformerAnnotations(
            $this->annotationReader->getMethodAnnotations($method)
        );

        foreach($annotations as $annotation) {
            foreach($this->classNames as $className) {
                $prefix = self::CLASS_PREFIX . "Annotation\\";
                $annotationClass = $prefix . $className;

                if($annotation instanceof $annotationClass)
                    return $className;
            }
        }
    }
}
<?php
namespace Wasm\ApiBundle\Serializer\PropsTransformer;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Wasm\ApiBundle\Serializer\PropsTransformer\Util\ClassDataReader;
use Wasm\UtilBundle\Util\Str;

class PropsTransformer
{
    private $container;
    private $reader;

    public function __construct(ContainerInterface $container, 
                                Reader $annotationReader)
    {
        $this->container = $container;
        $this->reader = new ClassDataReader( 
            $annotationReader, 
            $this->container->get('kernel')->getRootDir()
        );
    }

    public function transform($object, $normalizedProps)
    {
        $normalizedProps = $this->transformProps($object, $normalizedProps);
        $normalizedProps = $this->transformMethods($object, $normalizedProps);

        return $normalizedProps;
    }

    private function transformProps($object, $normalizedProps)
    {
        $propsData = $this->reader->getPropsData($object);

        foreach($propsData as $propData) {
            if(!$propData["hasSerializerGroupsAnnotations"] ||
               !array_key_exists($propData["prop"], $normalizedProps))
                continue;

            $propValue = $normalizedProps[$propData["prop"]];

            if($propData["hasTransformerAnnotations"]) 
                $propValue = $this->transformProp(
                    $object,
                    $propData["prop"],
                    $propData["propReflection"],
                    $propValue, 
                    $propData["annotations"]
                );

            $normalizedProps[$propData["prop"]] = $propValue;
        }

        return $normalizedProps;
    }

    private function transformProp(
        $object, 
        $propName, 
        $propReflection, 
        $propValue, 
        $annotations
    ) {
        foreach($annotations as $annotation) {
            $class = ClassDataReader::CLASS_PREFIX;
            $class .= $this->reader->getTransformerClassNameByAnnotation(
                $object, $propName, $propReflection, $annotation
            ) . "Transformer";
            
            $propTransformer = new $class();
            $propTransformer->setContainer($this->container);
            $propValue = $propTransformer->transform($propValue, $annotation);
        }
        
        return $propValue;
    }

    private function transformMethods($object, $normalizedProps)
    {
        $methodsData = $this->reader->getMethodsData($object);
        $methodNames = array_reduce(
            $methodsData,
            function($res, $methodData) {
                $res[] = $methodData["method"];
                return $res;
            },
            array()
        );

        foreach($normalizedProps as $propName => $propValue) {
            $getter = "get" . Str::ucfirst($propName);
            if(!in_array($getter, $methodNames))
                continue;

            $methodData = array_values(
                array_filter($methodsData, function($methodData) use ($getter) {
                    return $methodData["method"] == $getter;
                })
            )[0];

            if(!$methodData["hasSerializerGroupsAnnotations"])
                continue;

            $propValue = $normalizedProps[$propName];

            if($methodData["hasTransformerAnnotations"]) 
                $propValue = $this->transformMethod(
                    $object,
                    $methodData["method"],
                    $methodData["methodReflection"],
                    $propValue, 
                    $methodData["annotations"]
                );

            $normalizedProps[$propName] = $propValue;
        }

        return $normalizedProps;
    }

    private function transformMethod(
        $object, 
        $methodName, 
        $methodReflection, 
        $propValue, 
        $annotations
    ) {
        foreach($annotations as $annotation) {
            $class = ClassDataReader::CLASS_PREFIX;
            $class .= $this->reader->getTransformerClassNameByMethodAnnotation(
                $object, $methodName, $methodReflection, $annotation
            ) . "Transformer";
            
            $propTransformer = new $class();
            $propTransformer->setContainer($this->container);
            $propValue = $propTransformer->transform($propValue, $annotation);
        }
        
        return $propValue;
    }
}
<?php
namespace Wasm\FormBundle\Form;

use Doctrine\Common\Annotations\Reader;
use Wasm\FormBundle\Form\Annotation\Form;

class FormReader
{
    const ANNOTATION_CLASS_PREFIX = "\\Wasm\\FormBundle\\Form\\Annotation\\";

    private $annotationReader;

    public function __construct(Reader $annotationReader) 
    {
        $this->annotationReader = $annotationReader;
    }

    public function readFormAnnotations($formData) 
    {
        $tree = $this->readClassesTree($formData->formClass);

        foreach($tree as $className) {
            $class = new \ReflectionClass($className);
            $formAnnotation = $this->filterFormAnnotation(
                $this->annotationReader->getClassAnnotations($class)
            );
            
            if($formAnnotation == null)
                continue;

            $formData->entityClass = $formAnnotation->getEntityClass();
            $formData->repoClass = $formAnnotation->getRepoClass();
            $formData->manyStates = $formAnnotation->getHasManyStates();
        }
    }

    private function readClassesTree($rootClassName)
    {
        $class = new \ReflectionClass($rootClassName);
        $tree = array($rootClassName);
        
        while($parent = $class->getParentClass()) {
            $tree[] = $parent->getName();
            $class = $parent;
        }

        return $tree;
    }

    private function filterFormAnnotation($annotations)
    {
        foreach($annotations as $annotation) {
            $annotationClass = self::ANNOTATION_CLASS_PREFIX . "Form";

            if($annotation instanceof $annotationClass)
                return $annotation;
        }

        return null;
    }
}
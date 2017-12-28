<?php
namespace Wasm\FormBundle\Form\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Form
{
    public $entity = null;
    public $repo = null;
    public $manyStates = false;

    public function getEntityClass()
    {
        return $this->entity;
    }

    public function getRepoClass()
    {
        return $this->repo;
    }

    public function getHasManyStates()
    {
        return $this->manyStates;
    }
}
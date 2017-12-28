<?php
namespace Wasm\FormBundle\Model;

class FormData
{
    public $formId;
    public $form = null;

    public $formClass;
    public $storeClass;

    public $onlyFields = array();
    public $exceptFields = array();
    
    public $virtualFields = array(); // Not mapped to entities
    public $stateFields = array();
    public $storeFields = array();

    public $state = array();
    public $manyStates = false;

    // EntityId can be:
    //     -> Scalar: $entityId = 245;
    //     -> Array: ["entityId" => 245, "relatedEntityId" => 300];
    public $entityId = null;
    public $entityClass = null;
    public $entity = null;
    public $repoClass = null;

    public function hasEntityIdArray()
    {
        return (
           is_array($this->entityId) && 
           array_key_exists("entityId", $this->entityId)
        );
    }

    public function hasEntityId()
    {
        if($this->hasEntityIdArray())
            return $this->entityId["entityId"] !== null;

        return $this->entityId !== null;
    }

    public function getEntityId()
    {
        if($this->hasEntityIdArray())
            return $this->entityId["entityId"];

        return $this->entityId;
    }
}
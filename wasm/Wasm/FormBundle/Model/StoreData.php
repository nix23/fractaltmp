<?php
namespace Wasm\FormBundle\Model;

// @todo -> add $message prop? ($notificationMessage)
// 'Model' successfully saved|succ.updated, etc...

class StoreData
{
    public $entity = null;
    public $statusCode = 201;
    public $entityGroups = array("entity");

    public function addEntityGroup($groupName)
    {
        $this->entityGroups[] = $groupName;
    }
}
<?php
namespace Wasm\AppBundle\Form;

use Wasm\FormBundle\Store\StoreTrait;

class GroupStore
{
    use StoreTrait;
    
    private $createGroup;

    public function initialize()
    {
        $this->createGroup = $this->store->get(
            "Wasm.App.Cmd.CreateGroupCmd"
        );
    }

    public function store($formData)
    {
        $this->store->createEntity($formData);
        $this->store->formToEntityState($formData);

        $this->createGroup->exec($formData->entity);
        $this->storeData->entity = $formData->entity;
    }
}
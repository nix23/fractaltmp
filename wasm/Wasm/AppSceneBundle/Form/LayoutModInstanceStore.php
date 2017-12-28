<?php
namespace Wasm\AppSceneBundle\Form;

use Wasm\FormBundle\Store\StoreTrait;

class LayoutModInstanceStore
{
    use StoreTrait;
    
    private $addLayoutModInstance;

    public function initialize()
    {
        $this->addLayoutModInstance = $this->store->get(
            "Wasm.AppScene.Cmd.AddLayoutModInstanceCmd"
        );
    }

    public function store($formData)
    {
        $this->store->createEntity($formData);
        $this->store->formToEntityState($formData);

        $this->addLayoutModInstance->exec($formData->entity);
        $this->storeData->entity = $formData->entity;
    }
}
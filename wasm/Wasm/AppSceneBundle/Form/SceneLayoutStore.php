<?php
namespace Wasm\AppSceneBundle\Form;

use Wasm\FormBundle\Store\StoreTrait;

class SceneLayoutStore
{
    use StoreTrait;
    
    private $addSceneLayout;

    public function initialize()
    {
        $this->addSceneLayout = $this->store->get(
            "Wasm.AppScene.Cmd.AddSceneLayoutCmd"
        );
    }

    public function store($formData)
    {
        $this->store->createEntity($formData);
        $this->store->formToEntityState($formData);

        $this->addSceneLayout->exec($formData->entity);
        $this->storeData->entity = $formData->entity;
    }
}
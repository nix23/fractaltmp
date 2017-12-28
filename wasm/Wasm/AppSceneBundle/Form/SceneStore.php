<?php
namespace Wasm\AppSceneBundle\Form;

use Wasm\FormBundle\Store\StoreTrait;

class SceneStore
{
    use StoreTrait;
    
    private $createScene;

    public function initialize()
    {
        $this->createScene = $this->store->get(
            "Wasm.AppScene.Cmd.CreateSceneCmd"
        );
    }

    public function store($formData)
    {
        $this->store->createEntity($formData);
        $this->store->formToEntityState($formData);

        $this->createScene->exec($formData->entity);
        $this->storeData->entity = $formData->entity;
    }
}
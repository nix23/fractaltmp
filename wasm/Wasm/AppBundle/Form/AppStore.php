<?php
namespace Wasm\AppBundle\Form;

use Wasm\FormBundle\Store\StoreTrait;

class AppStore
{
    use StoreTrait;

    private $createApp;

    public function initialize() 
    {
        $this->createApp = $this->store->get(
            "Wasm.App.Cmd.CreateAppCmd"
        );
    }

    public function store($formData)
    {
        $this->store->createEntity($formData);
        $this->store->formToEntityState($formData);

        $this->createApp->exec($formData->entity);
        $this->storeData->entity = $formData->entity;
    }
}
<?php
namespace Wasm\AppBundle\Form;

use Wasm\FormBundle\Store\StoreTrait;

class SectionStore
{
    use StoreTrait;
    
    private $createSection;

    public function initialize()
    {
        $this->createSection = $this->store->get(
            "Wasm.App.Cmd.CreateSectionCmd"
        );
    }

    public function store($formData)
    {
        $this->store->createEntity($formData);
        $this->store->formToEntityState($formData);

        $this->createSection->exec($formData->entity);
        $this->storeData->entity = $formData->entity;
    }
}
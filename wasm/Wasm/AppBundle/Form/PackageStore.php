<?php
namespace Wasm\AppBundle\Form;

use Wasm\FormBundle\Store\StoreTrait;

class PackageStore
{
    use StoreTrait;
    
    private $createPackage;

    public function initialize()
    {
        $this->createPackage = $this->store->get(
            "Wasm.App.Cmd.CreatePackageCmd"
        );
    }

    public function store($formData)
    {
        $this->store->createEntity($formData);
        $this->store->formToEntityState($formData);

        $this->createPackage->exec($formData->entity);
        $this->storeData->entity = $formData->entity;
    }
}
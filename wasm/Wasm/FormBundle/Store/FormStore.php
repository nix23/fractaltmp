<?php
namespace Wasm\FormBundle\Store;

use Wasm\FormBundle\Store\StoreTrait;

class FormStore
{
    use StoreTrait;

    public function store($formsData)
    {
        foreach($formsData as $formData) {
            $this->store->createEntity($formData);
            $this->store->formsToEntityState(
                array($formData), $formData->entity
            );

            $this->em->persist($formData->entity);
            $this->em->flush();
        }

        // $job = $this->bindFormToEntity(
        //     "\\Ntech\\CoreBundle\\Entity\\Job", $forms
        // );
        // $expressJob = $this->bindFormToEntity(
        //     "\\Ntech\\CoreBundle\\Entity\\ExpressJob", $forms
        // );

        // mapFormStateToEntityState() =>
        // loop through all form fields -> if entity has setter for this field 
        //     -> set entity field

        // $this->em->persist($entity);
        // $this->em->flush();
    }
}
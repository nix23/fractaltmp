<?php
namespace Wasm\FormBundle\StateFactory;

class EntityFactory
{
    private $em;
    private $user;

    public function __construct($em, $um)
    {
        $this->em = $em;
        $this->user = $um->getUser();
    }

    public function createFormEntityByEntityId($form, $formData)
    {
        if(!$formData->hasEntityId())
            return null;

        if(method_exists($form, "getState")) {
            $entity = $form->getState(
                $formData->entityId, $this->em, $this->user
            );
        }
        else {
            $entity = $this->em
                ->getEm()
                ->getRepository($formData->repoClass)
                ->find($formData->getEntityId());
        }

        if(!$entity)
            throw new \Exception("Can't find entity for state.");
        
        return $entity;
    }

    public function createFormEntityForStore($formData)
    {
        if(!$formData->hasEntityId()) {
            $entity = $this->createNewEntityForStore($formData);
            $formData->entity = $entity;
            return;
        }

        $formData->entity = $this->createFormEntityByEntityId(
            $formData->form,
            $formData
        );
    }

    private function createNewEntityForStore($formData)
    {
        if(!$formData->entityClass) {
            $ec = $formData->entityClass;
            throw new \Exception("Unknown FormData entityClass: '$ec'.");
        }

        $entityClass = $formData->entityClass;
        $entity = new $entityClass();

        return $entity;
    }

    // @ping -> Check entityId = array() support
    public function createFormEntitiesForStore($formData)
    {
        $existingIds = array();
        foreach($formData->form as $form) {
            if(is_numeric($form->id))
                $existingIds[] = $form->id;
        }

        $existingEntities = array();
        if(count($existingIds) > 0) {
            $existingEntities = $this->em
                ->getEm()
                ->getRepository($formData->repoClass)
                ->findById($existingIds);
        }

        foreach($formData->form as $form) {
            if(is_numeric($form->id) && ((int)$form->id > 0)) {
                $formData->entity[] = array_values(
                    array_filter($existingEntities, function($item) use ($form) {
                        return $item->getId() == $form->id;
                    })
                )[0];
            }
            else {
                $formData->entity[] = $this->createNewEntityForStore($formData);
            }
        }
    }
}
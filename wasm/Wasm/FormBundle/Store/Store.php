<?php
namespace Wasm\FormBundle\Store;

use Wasm\FormBundle\Util\FormsData;

class Store
{
    const STORE_CLASS_PREFIX = "\\Wasm\\FormBundle\\Store\\";

    private $container;
    private $stateTransformer;
    private $em;
    private $entityFactory;

    public function __construct(
        $container,
        $stateTransformer, 
        $em,
        $entityFactory
    ) {
        $this->container = $container;
        $this->stateTransformer = $stateTransformer;
        $this->em = $em;
        $this->entityFactory = $entityFactory;
    }

    public function get($service)
    {
        return $this->container->get($service);
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function formData($formId, $formsData)
    {
        return FormsData::getFormDataByFormId($formId, $formsData);
    }

    public function persistForms($formsData = array(), $storeId, $storeData)
    {
        $store = $this->createStore($storeId, $storeData);
        $store->store($formsData);
    }

    private function createStore($storeId, $storeData)
    {
        $storeClass = self::STORE_CLASS_PREFIX;
        if(!$storeId || $storeId == "FormStore")
            $storeClass .= "FormStore";
        else if($storeId == "FormCollectionStore")
            $storeClass .= "FormCollectionStore";
        else if($storeId == "FormCustomStore")
            $storeClass .= "FormCustomStore";
        else
            throw new \Exception("Unknown form store id: '$storeId'.");

        $store = new $storeClass($this, $storeData, $this->em);
        return $store;
    }

    public function createEntity($formData)
    {
        $this->entityFactory->createFormEntityForStore($formData);
    }

    public function formToEntityState($formData)
    {
        return $this->formsToEntityState(
            array($formData), $formData->entity
        );
    }

    public function formsToEntityState($formsData = array(), $entity)
    {
        foreach($formsData as $formData) {
            $this->stateTransformer->transformFormToEntity(
                $formData->form, $formData, $entity
            );
        }
    }

    public function createEntities($formData)
    {
        $this->entityFactory->createFormEntitiesForStore($formData);
    }

    public function manyFormsToEntityStates($formData)
    {
        for($i = 0; $i < count($formData->form); $i++) {
            $this->stateTransformer->transformFormToEntity(
                $formData->form[$i], $formData, $formData->entity[$i]
            );
        }
    }
}
<?php
namespace Wasm\FormBundle\Store;

use Wasm\FormBundle\Store\StoreTrait;

class FormCustomStore
{
    use StoreTrait;

    public function store($formsData)
    {
        $customStoreClassExists = false;
        foreach($formsData as $formData) {
            if(!class_exists($formData->storeClass))
                continue;

            $customStoreClassExists = true;

            $store = new $formData->storeClass(
                $this->store, 
                $this->storeData,
                $this->em
            );

            if(method_exists($store, "initialize"))
                $store->initialize();

            // Custom logic for single Form store(Like AccountForm)
            if(count($formsData) == 1)
                $store->store($formData);
            // Custom logic for multiple Form store(Like ExpressJob forms)
            else
                $store->store($formsData);
        }

        if(!$customStoreClassExists) {
            $msg = "FromCustomStore class was used to store form, ";
            $msg .= "but custom store implementation class was not found.";

            throw new \Exception($msg);
        }
    }
}
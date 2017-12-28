<?php
namespace Wasm\FormBundle\Store;

trait StoreTrait
{
    private $store;
    private $storeData;
    private $em;

    public function __construct(
        $store,
        $storeData,
        $em
    ) {
        $this->store = $store;
        $this->storeData = $storeData;
        $this->em = $em;
    }
}
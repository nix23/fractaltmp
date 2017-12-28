<?php
namespace Wasm\AppBundle\Store;

use Wasm\StoreBundle\Store\Repo;

class AppRepo extends Repo
{
    public function getDemoApp()
    {
        $q = $this->q("
            SELECT a FROM WasmAppBundle:App a
            WHERE a.name = :name
        ")->setParameter("name", "Fabalist");

        return $this->row($q);
    }
}
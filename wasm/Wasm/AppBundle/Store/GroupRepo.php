<?php
namespace Wasm\AppBundle\Store;

use Wasm\StoreBundle\Store\Repo;

class GroupRepo extends Repo
{
    public function getDefaultGroup()
    {
        $q = $this->getEntityManager()->createQuery("
            SELECT g FROM WasmAppBundle:Group g
            WHERE g.isDefault = true
        ");

        return $this->row($q);
    }
}
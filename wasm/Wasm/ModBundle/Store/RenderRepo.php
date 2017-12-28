<?php
namespace Wasm\ModBundle\Store;

use Wasm\StoreBundle\Store\Repo;

class RenderRepo extends Repo
{
    public function getMaxRenderSort($modInstance)
    {
        $q = $this->q("
            SELECT MAX(r.sort) FROM WasmModBundle:Render r
            WHERE IDENTITY(r.modInstance) = :modInstanceId
        ")
            ->setParameter("modInstanceId", $modInstance->getId());

        return $this->max($q);
    }
}
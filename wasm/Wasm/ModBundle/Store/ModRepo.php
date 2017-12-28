<?php
namespace Wasm\ModBundle\Store;

use Wasm\StoreBundle\Store\Repo;

class ModRepo extends Repo
{
    public function getMod($vendor, $groupName, $name)
    {
        $q = $this->q("
            SELECT m FROM WasmModBundle:Mod m
            WHERE m.vendor = :vendor
            AND m.groupName = :groupName
            AND m.name = :name
        ")
            ->setParameter("vendor", $vendor)
            ->setParameter("groupName", $groupName)
            ->setParameter("name", $name);

        return $this->row($q);
    }
}
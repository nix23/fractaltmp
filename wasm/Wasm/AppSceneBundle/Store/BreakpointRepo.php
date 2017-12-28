<?php
namespace Wasm\AppSceneBundle\Store;

use Wasm\StoreBundle\Store\Repo;

class BreakpointRepo extends Repo
{
    public function getAllSorted()
    {
        $q = $this->q("
            SELECT b FROM WasmAppSceneBundle:Breakpoint b
            ORDER BY b.sort ASC
        ");

        return $this->rows($q);
    }

    public function getByName($name)
    {
        $q = $this->q("
            SELECT b FROM WasmAppSceneBundle:Breakpoint b
            WHERE b.name = :name
        ")
            ->setParameter("name", $name);

        return $this->row($q);
    }
}
<?php
namespace <%PKGCLASS%>\Store;

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
}
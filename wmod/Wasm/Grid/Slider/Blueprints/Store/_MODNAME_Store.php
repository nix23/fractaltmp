<?php
namespace <%PKGCLASS%>\Store;

use Symfony\Component\HttpFoundation\Request;

class <%MODNAME%>Store
{
    private $modSliderStore;

    public function __construct($modSliderStore)
    {
        $this->modSliderStore = $modSliderStore;
    }

    public function getItems(Request $request)
    {
        $items = $this
            ->modSliderStore
            ->getItems($request);

        return $items;
    }
}
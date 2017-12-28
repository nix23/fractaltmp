<?php
namespace <%PKGCLASS%>\Store;

use Symfony\Component\HttpFoundation\Request;

class DashboardStore
{
    public function getItems(Request $request)
    {
        // Return all dashboard modules data
        // $items = $this
        //     ->get("Wasm.Slider.Module.SliderManager")
        //     ->getItems($request);

        return $items;
    }
}
<?php
namespace Wmod\Wasm\Grid\Slider\Store;

use Symfony\Component\HttpFoundation\Request;

class SliderStore
{
    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function getItems(Request $request)
    {
        

        return $items;
    }
}
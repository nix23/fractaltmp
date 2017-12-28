<?php
namespace Wasm\ApiBundle\DataPager;

class DataPager
{
    const PAGE_SIZE = 20;

    public function pageSize()
    {
        // return 2; // For testing purposes
        return self::PAGE_SIZE;
    }
}
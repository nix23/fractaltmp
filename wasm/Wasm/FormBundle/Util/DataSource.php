<?php
namespace Wasm\FormBundle\Util;

class DataSource
{
    public static function createItems($vals, $labels)
    {
        $data = array();
        for($i = 0; $i < count($vals); $i++) 
            $data[] = array(
                "id" => $vals[$i],
                "label" => $labels[$i],
            );

        return $data;
    }
}
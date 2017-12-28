<?php
namespace Wasm\UtilBundle\Util;

class DateTime
{
    public static function isInLastHour($datetime)
    {
        $datetimeTimestamp = $datetime->getTimestamp();
        $nowTimestamp = (new \DateTime())->getTimestamp();

        if(!$datetimeTimestamp)
            $datetimeTimestamp = 0;

        $diff = $nowTimestamp - $datetimeTimestamp;
        $hourInSecs = 60 * 60;

        return ($diff < $hourInSecs);
    }
}
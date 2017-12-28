<?php
namespace Wasm\ApiBundle\DataPager;

use Wasm\UtilBundle\Util\Str;

// @todo -> Inject PageSize in cursor and check in all
// frontend lists(if no cursor or prev == null no loadMore)
// (How to check if has next/has prev???) -> for buttons, not for
// 'refresh' on mobiles???? Add hasPrev/hasNext bools???
class Cursor
{
    const PREV = "0";
    const NEXT = "1";
    const EMPTY_VAL = "0";
    const SEPARATOR = "__";

    private $dataPager;

    public function __construct($dataPager)
    {
        $this->dataPager = $dataPager;
    }

    public function byOffsetFromAll($cursor = null, $allItems = array()) {
        $pageSize = $this->dataPager->pageSize();

        if(!$cursor) {
            $prev = (count($allItems) > $pageSize) ? ($pageSize - 1) : null;

            return array(
                "prev" => self::prev($prev),
                "next" => self::next(0),
            );
        }

        $res = array();
        if(self::isPrev($cursor)) {
            $nextPrev = (self::valueInt($cursor)) + $pageSize;
            if(count($allItems) - 1 < $nextPrev)
                $nextPrev = self::valueInt($cursor);
            
            $res["prev"] = self::prev($nextPrev);
        }
        if(self::isNext($cursor))
            $res["next"] = self::next(0);

        return $res;
    }

    public function byOffset($cursor = null, $items = array())
    {
        ; // @todo -> Implement
    }

    public function byId($cursor = null, $nextItems = array())
    {
        if(!$cursor) {
            return array(
                "prev" => self::prev(self::minId($nextItems)),
                "next" => self::next(self::maxId($nextItems)),
            );
        }

        $res = array();
        if(self::isPrev($cursor)) 
            $res["prev"] = self::prev(self::minId($nextItems));
        if(self::isNext($cursor))
            $res["next"] = self::next(self::maxId($nextItems));

        return $res;
    }

    private static function minId($items)
    {
        if(count($items) == 0)
            return null;

        $cursorItem = $items[count($items) - 1];
        return $cursorItem->getId();
    }

    private static function maxId($items)
    {
        if(count($items) == 0)
            return null;

        $cursorItem = $items[0];
        return $cursorItem->getId();
    }

    public function byDateTime(
        $cursor = null, 
        $nextItems = array(), 
        $getDateTime = "getCreatedAt"
    ) {
        if(!$cursor) {
            return array(
                "prev" => self::prev(self::oldestDateTime(
                    $nextItems, $getDateTime
                )),
                "next" => self::next(self::newestDateTime(
                    $nextItems, $getDateTime
                )),
            );
        }
        
        $res = array();
        if(self::isPrev($cursor)) {
            $res["prev"] = self::prev(self::oldestDateTime(
                $nextItems, $getDateTime
            ));
        }
        if(self::isNext($cursor)) {
            $res["next"] = self::next(self::newestDateTime(
                $nextItems. $getDateTime
            ));
        }

        return $res;
    }

    private static function oldestDateTime($items, $getDateTime)
    {
        if(count($items) == 0)
            return null;

        $cursorItem = $items[count($items) - 1];
        $datetime = $cursorItem->$getDateTime();
        $ids = self::getAllIdsWithEqualDatetime($datetime, $items, $getDateTime);

        return $datetime->format("Y-m-d H:i:s") . self::SEPARATOR . implode(",", $ids);
    }

    private static function newestDateTime($items, $getDateTime)
    {
        if(count($items) == 0)
            return null;

        $cursorItem = $items[0];
        $datetime = $cursorItem->$getDateTime();
        $ids = self::getAllIdsWithEqualDatetime($datetime, $items, $getDateTime);
    
        return $datetime->format("Y-m-d H:i:s") . self::SEPARATOR . implode(",", $ids);
    }

    private static function getAllIdsWithEqualDatetime(
        $datetime, 
        $items,
        $getDateTime
    ) {
        $ids = array();
        foreach($items as $item) {
            if($item->$getDateTime() == $datetime)
                $ids[] = $item->getId();
        }

        return $ids;
    }

    public static function valueDateTime($cursor = "")
    {
        list($datetime, $ids) = self::value($cursor);
        $ids = explode(",", $ids);

        return array(new \DateTime($datetime), $ids);
    }

    public static function value($cursor = "")
    {
        $cursorParts = explode(self::SEPARATOR, $cursor);
        if(count($cursorParts) == 2)
            return $cursorParts[0];

        return array($cursorParts[0], $cursorParts[1]);
    }

    public static function valueInt($cursor = "")
    {
        $val = self::value($cursor);
        if(!is_array($val))
            return (int)$val;

        foreach($val as &$valEntry)
            $valEntry = (int)$valEntry;

        return $val;
    }

    private static function prev($value = "")
    {
        if($value === null)
            return null;

        return strval($value) . self::SEPARATOR . self::PREV;
    }

    private static function next($value = "")
    {
        if($value === null)
            return null;

        return strval($value) . self::SEPARATOR . self::NEXT;
    }

    private static function isPrev($cursor = "")
    {
        $cursorParts = explode(self::SEPARATOR, $cursor);
        return ($cursorParts[count($cursorParts) - 1] == self::PREV);
    }

    private static function isNext($cursor = "")
    {
        $cursorParts = explode(self::SEPARATOR, $cursor);
        return ($cursorParts[count($cursorParts) - 1] == self::NEXT);
    }
}
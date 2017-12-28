<?php
namespace Wasm\UtilBundle\Util;

class Str
{
    public static function slugify($str)
    {
        $slug = str_replace(" ", "-", $str);
        $slug = self::lower($slug);

        return $slug;
    }

    public static function link($site)
    {
        if(substr($site, 0, 4) == "http" || substr($site, 0, 5) == "https")
            return $site;

        if(self::len($site) == 0)
            return "";

        return "http://" . $site;
    }

    public static function len($str)
    {
        if(function_exists('mb_strlen')
            && false !== $encoding = mb_detect_encoding($str)) {
            return mb_strlen($str, $encoding);
        }

        return strlen($str);
    }

    public static function copy($str, $from, $to)
    {
        if(function_exists('mb_substr')
            && false !== $encoding = mb_detect_encoding($str)) {
            return mb_substr($str, $from, $to, $encoding);
        }

        return substr($str, $from, $to);
    }

    public static function isFirstSlash($str)
    {
        return $str[0] == "/" || $str[0] == "\\";
    }

    public static function isLastSlash($str)
    {
        return $str[Str::len($str) - 1] == "/" || $str[Str::len($str) - 1] == "\\";
    }

    public static function lower($str)
    {
        if(function_exists('mb_strtolower')
            && false !== $encoding = mb_detect_encoding($str)) {
            return mb_strtolower($str, $encoding);
        }

        return strtolower($str);
    }

    public static function upper($str)
    {
        if(function_exists('mb_strtoupper')
            && false !== $encoding = mb_detect_encoding($str)) {
            return mb_strtoupper($str, $encoding);
        }

        return strtoupper($str);
    }

    public static function ucfirst($str)
    {
        if(function_exists('mb_strtoupper') && function_exists('mb_substr')
            && false !== $encoding = mb_detect_encoding($str)) {
            $tail = mb_substr($str, 1, self::len($str) - 1, $encoding);
            return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) . $tail;
        }

        $tail = substr($str, 1, self::len($str) - 1);
        return strtoupper(substr($str, 0, 1)) . $tail;
    }

    public static function lcfirst($str)
    {
       if(function_exists('mb_strtolower') && function_exists('mb_substr')
            && false !== $encoding = mb_detect_encoding($str)) {
            $tail = mb_substr($str, 1, self::len($str) - 1, $encoding);
            return mb_strtolower(mb_substr($str, 0, 1, $encoding), $encoding) . $tail;
        }

        $tail = substr($str, 1, self::len($str) - 1);
        return strtolower(substr($str, 0, 1)) . $tail;
    }

    public static function formatPhone($phone, $open = "(", $close = ")", $end = "-")
    {
        $firstPart = substr($phone, 0, 3);
        $secondPart = substr($phone, 3, 3);
        $lastPart = substr($phone, 6, Str::len($phone) - 6);

        return $open . $firstPart . $close . $secondPart . $end . $lastPart;
    }

    public static function explodeByNewLines($str)
    {
        $rows = preg_split("/\\r\\n|\\r|\\n/", $str);
        $cleanedRows = array();

        foreach($rows as $row) {
            if(Str::len($row) == 0)
                continue;

            $cleanedRows[] = $row;
        }

        return $cleanedRows;
    }

    public static function contains($str, $substr)
    {
        return (strpos($str, $substr) !== false);
    }

    public static function isNotBlank($str)
    {
        if($str == null)
            return false;

        $str = trim($str);
        return (self::len($str) > 0);
    }

    public static function isBlank($str)
    {
        return !self::isNotBlank($str);
    }

    public static function isUpper($char)
    {
        return ctype_upper($char);
    }

    public static function spacesBeforeUpper($string)
    {
        $result = "";
        array_map(function($char) use (&$result) {
            if(self::isUpper($char))
                $result .= " " . self::lower($char);
            else
                $result .= $char;
        }, str_split($string));
        
        return $result;
    }

    public static function spacesToNextUpper($string)
    {
        $result = "";
        $wasPrevSpace = false;
        array_map(function($char) use (&$result, &$wasPrevSpace) {
            if($char == " ") {
                $wasPrevSpace = true;
                return;
            }

            if($wasPrevSpace) {
                $wasPrevSpace = false;
                $result .= self::upper($char);
            }
            else
                $result .= $char;
        }, str_split($string));

        return $result;
    }

    public static function emptyStrIfNull($maybeNull)
    {
        return ($maybeNull == null) ? "" : $maybeNull;
    }
}
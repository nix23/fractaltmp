<?php
namespace Wasm\FsBundle\FilePath\Util;

use Wasm\UtilBundle\Util\Str;

class IsSynthetic
{
    public static function section($section)
    {
        return Str::isBlank($section->getName());
    }

    public static function package($package)
    {
        return Str::isBlank($package->getName());
    }
}
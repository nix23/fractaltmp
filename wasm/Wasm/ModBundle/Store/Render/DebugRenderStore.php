<?php
namespace Wasm\ModBundle\Store\Render;

class DebugRenderStore
{
    private static $debug = false;

    public static function setDebug($debug = false)
    {
        self::$debug = $debug;
    }

    public static function renderStart($package, $modInstance, $txt = "START")
    {
        if(!self::$debug) return;
        $pkgName = implode(".", $package->getFullNameByAppGroupArray());
        $modName = implode(".", $modInstance->getFullNameArray());

        echo "****************************************************\n";
        echo "****************************************************\n";
        echo "                  RERENDER $txt                     \n";
        echo "        Package: " . $pkgName . ", Mod: " . $modName . "\n";
        echo "****************************************************\n";
        echo "****************************************************\n";
    }

    public static function renderEnd($package, $modInstance)
    {
        if(!self::$debug) return;
        self::renderStart($package, $modInstance, "END");
    }

    public static function enterMergeParams($params, $nextParams, $deepness, $txt = "enterMergeParams")
    {
        if(!self::$debug) return;

        if($deepness == 0) {
            echo "_______________________ $txt(deepness = 0) _______________________\n";
            echo "*********** \$params:\n";
            cli($params);
            echo "*********** \$nextParams:\n";
            cli($nextParams);
            echo "_______________________________________________________________________________\n\n";
            return;
        }

        $sep = "";
        for($i = 0; $i < $deepness * 5; $i++)
            $sep .= "-";

        $echoTxt = "$txt(deepness = $deepness)";
        $addSep = "";
        for($i = 0; $i < strlen($echoTxt); $i++)
            $addSep .= "-";
        $addSep .= "--";

        echo "$sep$addSep$sep\n";
        echo "$sep $echoTxt $sep\n";
        echo "$sep$addSep$sep\n\n";
        echo "*********** \$params:\n";
        cli($params);
        echo "*********** \$nextParams:\n";
        cli($nextParams);
        echo "$sep$addSep$sep\n";
        echo "$sep$addSep$sep\n\n";
        return;
    }

    public static function exitMergeParams($params, $nextParams, $deepness)
    {
        if(!self::$debug) return;
        self::enterMergeParams($params, $nextParams, $deepness, "exitMergeParams");
    }

    public static function pushNumericRes($params, $result)
    {
        if(!self::$debug) return;
        echo "++++++++++++++++++++++++++ PUSH_NUMERIC_RES ++++++++++++++++++++++++++++++\n";
        echo "\$params:\n";
        cli($params);
        echo "\$result\n";
        cli($result);
        echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n";
    }
}
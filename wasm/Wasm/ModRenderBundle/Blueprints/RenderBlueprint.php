<?php
namespace Wasm\ModRenderBundle\Blueprints;

use Wasm\ModRenderBundle\Model\BlueprintData;
use Wasm\UtilBundle\Util\Str;

class RenderBlueprint
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function execSrc($bp, $aps, $m, $mi, $ep) {
        $this->render($bp, $aps, $m, $mi, $ep, false);
    }

    public function execSrcui($bp, $aps, $m, $mi, $ep) {
        $this->render($bp, $aps, $m, $mi, $ep, true);
    }

    public function render(
        BlueprintData $blueprint,
        $appPackage,
        $mod,
        $modInstance,
        $extraParams,
        $isSrcuiMod = false
    ) {
        $params = $this->createParams(
            $appPackage,
            $modInstance,
            $extraParams
        );
        $this->renderFilename(
            $blueprint,
            $appPackage,
            $mod,
            $modInstance,
            $params
        );
        $this->renderCode(
            $blueprint,
            $appPackage,
            $mod,
            $modInstance,
            $params,
            ($isSrcuiMod) ? "renderSrcuiModFile" : "renderModFile"
        );
    }

    private function createParams(
        $appPackage, 
        $modInstance,
        $extraParams = array()
    ) {
        $modFullName = $modInstance->getFullNameArray();
        $packageFullName = $appPackage->getFullNameByAppGroupArray();

        $route = Str::lower("/" . implode(
            "/", array_merge($packageFullName, $modFullName)
        ));
        $table = Str::lower(implode(
            "_", array_merge($packageFullName, $modFullName)
        ));
        
        // $repoStoreId = implode(".", array_merge(
        //     $packageFullName,
        //     array("Repository", $moduleInstance->getFullName() . "Store")
        // ));

        $defParams = array(
            // "APPGROUPNAME" => $appGroup->getName(),
            // "APPNAMESPACE" => $app->getNamespace(),
            "PKGNAME" => implode(".", $packageFullName),
            "PKGCLASS" => "\\" . implode("\\", $packageFullName),
            "MODNAME" => $modInstance->getFullName(),
            "MODPREFIX" => $modInstance->getModNamePrefix(),

            "ROUTE" => $route,
            "TABLE" => $table,

            //"REPOSTOREID" => $repoStoreId,
        );
        $defParams["PKGMODNAME"] = $defParams["PKGNAME"] . ".";
        $defParams["PKGMODNAME"] .= $defParams["MODNAME"];
        $params = array_merge($defParams, $extraParams);

        return $params;
    }

    private function renderFilename(
        $blueprint,
        $appPackage,
        $mod,
        $modInstance,
        $params = array()
    ) {
        $keys = array();
        $vals = array();
        foreach($params as $key => $val) {
            if(is_string($val)) {
                $keys[] = "_" . $key . "_";
                $vals[] = $val;
            }
        }

        $blueprint->renderFilename = str_replace(
            $keys,
            $vals,
            $blueprint->filename
        );
    }

    private function renderCode(
        $blueprint,
        $appPackage,
        $mod,
        $modInstance,
        $params = array(),
        $renderFn
    ) {
        $this->twig->$renderFn(
            $blueprint, $mod, $params
        );
    }
}
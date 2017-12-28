<?php
namespace Wasm\ModBundle\Cmd;

use Wasm\ModBundle\Entity\Mod;
use Wasm\ModBundle\Entity\ModInstance;
use Wasm\ModBundle\Entity\Render;
use Wasm\UtilBundle\Util\Str;

class InstallCoreModInstanceCmd
{
    private $installModInstance;
    private $packageStore;

    private $modRepo;

    public function __construct(
        $em, 
        $installModInstance, 
        $packageStore
    ) {
        $this->installModInstance = $installModInstance;
        $this->packageStore = $packageStore;

        $this->modRepo = $em->getEm()->getRepository("WasmModBundle:Mod");
    }

    private function serializeApp($appObj)
    {
        $app = array();
        $sections = array();
        foreach($appObj->getSections() as $sec) {
            $section = array();
            $packages = array();
            foreach($sec->getPackages() as $pkg) {
                $package = array();
                $package["id"] = $pkg->getId();
                $package["name"] = $pkg->getName();
                $package["lcfirstNameWithSection"] = Str::lcfirst(
                    $pkg->getSection()->getName()
                ) . $pkg->getName();
                $package["fullNameByAppGroupArray"] = $pkg->getFullNameByAppGroupArray();

                $packages[] = $package;
            }

            $section["id"] = $sec->getId();
            $section["name"] = $sec->getName();
            $section["packages"] = $packages;

            $sections[] = $section;
        }
        $app["sections"] = $sections;

        return $app;
    }

    public function execAppEntry($app)
    {
        $modInstance = $this->installModInstance->execWithoutPrefix(
            $this->packageStore->getSyntheticPackageForAppRoot($app),
            $this->modRepo->getMod(
                "Wasm", "Core", "AppEntry"
            ),
            array("app" => $this->serializeApp($app))
        );
        $app->setModInstance($modInstance);
    }

    public function execAppEntryReducers($app)
    {
        $this->installModInstance->execRerender(
            $this->packageStore->getSyntheticPackageForAppRoot($app),
            $app->getModInstance(),
            array("app" => $this->serializeApp($app)),
            array("reducers.js")
        );
    }

    public function execPackageRoot($package)
    {
        $this->installModInstance->execWithoutPrefix(
            $package,
            $this->modRepo->getMod(
                "Wasm", "Core", "PackageRoot"
            )
        );
    }

    public function execPackageReducers($package)
    {
        $this->installModInstance->execRerender(
            $package,
            $package->getSection()->getApp()->getModInstance(),
            array(),
            array("Store/reducers.js")
        );
    }

    public function execAppScene($scene)
    {
        return $this->installModInstance->execWithoutPrefix(
            $scene->getPackage(),
            $this->modRepo->getMod(
                "Wasm", "Core", "AppScene"
            ),
            array(
                "SCENENAME" => $scene->getName(),
            )
        );
    }

    public function execWebScene($scene)
    {
        return $this->installModInstance->execWithoutPrefix(
            $scene->getPackage(),
            $this->modRepo->getMod(
                "Wasm", "Core", "WebScene"
            ),
            array(
                "SCENENAME" => $scene->getName(),
            )
        );
    }

    public function execAppSceneLayout($sceneLayout)
    {
        
    }

    public function execWebSceneLayout($sceneLayout)
    {
        $rif = $sceneLayout->getBreakpointTypeLabelForSourceCode();
        $layout = array(
            "renderIfComponentName" => $rif,
            "layoutName" => $sceneLayout->getLayout()->getName(),
        );
        
        $this->installModInstance->execRerender(
            $sceneLayout->getScene()->getPackage(),
            $sceneLayout->getScene()->getModInstance(),
            array("layouts" => array($layout))
        );
    }
}
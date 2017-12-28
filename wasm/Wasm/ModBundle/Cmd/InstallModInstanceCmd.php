<?php
namespace Wasm\ModBundle\Cmd;

use Wasm\ModBundle\Entity\Mod;
use Wasm\ModBundle\Entity\ModInstance;
use Wasm\ModBundle\Entity\Render;

class InstallModInstanceCmd
{
    private $em;
    private $renderRepo;
    private $renderModInstance;
    private $renderStore;

    public function __construct($em, $renderModInstance, $renderStore)
    {
        $this->em = $em;
        $this->renderRepo = $em->getEm()->getRepository(
            "WasmModBundle:Render"
        );
        $this->renderModInstance = $renderModInstance;
        $this->renderStore = $renderStore;
    }

    public function execWithoutPrefix(
        $package,
        $mod,
        $initialParams = array()
    ) {
        return $this->exec(
            $package, $mod, "", $initialParams
        );
    }

    public function exec(
        $package, 
        $mod, 
        $modNamePrefix = "",
        $initialParams = array()
    ) {
        $modInstance = new ModInstance();
        $modInstance->setModNamePrefix($modNamePrefix);
        $modInstance->setMod($mod);
        //$modInstance->setAppPackage($package);

        $this->em->persist($modInstance);
        $this->persistRender($modInstance, $initialParams);

        $this->renderModInstance->exec(
            $package, $mod, $modInstance, $initialParams
        );

        // @ping -> Decide if required
        //if($module->getIsStatic()) {
            // Static modules are not saved in Database(Like Scenes)
            //return;
        //}

        $this->em->getEm()->flush();
        
        return $modInstance;
    }

    public function execRerender(
        $package,
        $modInstance,
        $nextParams = array(),
        $onlyBlueprints = array()
    ) {
        $this->persistRender($modInstance, $nextParams);

        $this->renderModInstance->exec(
            $package, 
            $modInstance->getMod(), 
            $modInstance, 
            $this->renderStore->getRenderParams($package, $modInstance),
            $onlyBlueprints
        );

        $this->em->flush();
    }

    private function persistRender(
        $modInstance, 
        $paramsArray
    ) {
        $render = new Render();
        $render->setModInstance($modInstance);
        $render->setParams($paramsArray);
        $render->setSort(
            $this->renderRepo->getMaxRenderSort($modInstance) + 1
        );
        $modInstance->addRender($render);

        $this->em->persist($render);
    }
}
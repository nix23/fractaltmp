<?php
namespace Wasm\AppSceneBundle\Cmd;

use Wasm\AppSceneBundle\Entity\LayoutModInstance;
use Wasm\AppSceneBundle\Entity\SceneLayout;

class AddLayoutModInstanceCmd
{
    private $em;
    private $submitForm;
    private $installCoreModInstance;

    private $appRepo;

    public function __construct(
        $em, 
        $submitForm,
        $installCoreModInstance
    ) {
        $this->em = $em;
        $this->submitForm = $submitForm;
        $this->installCoreModInstance = $installCoreModInstance;

        $this->appRepo = $this->em
            ->getEm()
            ->getRepository("WasmAppBundle:App");
    }

    public function execDemoScenes()
    {
        $demoApp = $this->appRepo->getDemoApp();
        $scene = $demoApp
            ->getSections()[0]
            ->getPackages()[0]
            ->getWebScenes()[0];
        $sceneLayout = $scene->getSceneLayouts()[0];
        $layoutItems = $sceneLayout->getLayout()->getLayoutItems();
        
        $this->submitForm->exec(array(
            "Wasm_AppScene_LayoutModInstance" => array(
                "state" => array(
                    "scene" => $scene->getId(),
                    "layout" => $layout->getId(),
                    "modInstance" => null,
                ),
                "entityId" => array(
                    "entityId" => null,
                    "scene" => $scene->getId(),
                    "layout" => $layout->getId(),
                ),
            ),
        ));
    }

    public function exec(SceneLayout $sceneLayout)
    {
        $this->em->persist($sceneLayout);
        
        if($sceneLayout->getScene()->isAppScene()) {
            $this->addAppSceneLayout($sceneLayout);
        }
        else if($sceneLayout->getScene()->isWebScene()) {
            $this->addWebSceneLayout($sceneLayout);
        }

        $this->em->flush();
    }

    private function addAppSceneLayout($sceneLayout)
    {
        $this->installModInstance->execRerender(
            $sceneLayout->getScene()->getPackage(),
            $sceneLayout->getScene()->getModInstance(),
            array()
        );
    }

    private function addWebSceneLayout($sceneLayout)
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
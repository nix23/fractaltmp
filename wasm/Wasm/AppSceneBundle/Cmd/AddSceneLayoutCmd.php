<?php
namespace Wasm\AppSceneBundle\Cmd;

use Wasm\AppSceneBundle\Entity\Breakpoint;
use Wasm\AppSceneBundle\Entity\SceneLayout;

class AddSceneLayoutCmd
{
    private $em;
    private $submitForm;
    private $installCoreModInstance;

    private $appRepo;
    private $breakpointRepo;
    private $layoutRepo;

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
        $this->breakpointRepo = $this->em
            ->getEm()
            ->getRepository("WasmAppSceneBundle:Breakpoint");
        $this->layoutRepo = $this->em
            ->getEm()
            ->getRepository("WasmAppSceneBundle:Layout");
    }

    public function execDemoScenes()
    {
        $demoApp = $this->appRepo->getDemoApp();
        $scene = $demoApp
            ->getSections()[0]
            ->getPackages()[0]
            ->getWebScenes()[0];
        $layout = $this->layoutRepo->getWebByName("1Col2Rows");
        $breakpoint = $this->breakpointRepo->getByName("xs");
        
        $this->submitForm->exec(array(
            "Wasm_AppScene_SceneLayout" => array(
                "state" => array(
                    "scene" => $scene->getId(),
                    "layout" => $layout->getId(),
                    "breakpointType" => SceneLayout::BREAKPOINT_TYPE_IS_MIN,
                    "breakpoint" => $breakpoint->getId(),
                ),
                "entityId" => array(
                    "entityId" => null,
                    "scene" => $scene->getId(),
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
        $this->installCoreModInstance->execAppSceneLayout($sceneLayout);
    }

    private function addWebSceneLayout($sceneLayout)
    {
        $this->installCoreModInstance->execWebSceneLayout($sceneLayout);
    }
}
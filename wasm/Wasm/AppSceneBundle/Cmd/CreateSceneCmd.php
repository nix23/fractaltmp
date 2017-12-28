<?php
namespace Wasm\AppSceneBundle\Cmd;

use Wasm\AppSceneBundle\Entity\Scene;

class CreateSceneCmd
{
    private $em;
    private $filePath;
    private $fileCrud;
    private $submitForm;
    private $installCoreModInstance;

    private $appRepo;
    private $modRepo;

    public function __construct(
        $em, 
        $filePath, 
        $fileCrud, 
        $submitForm,
        $installCoreModInstance
    ) {
        $this->em = $em;
        $this->filePath = $filePath;
        $this->fileCrud = $fileCrud;
        $this->submitForm = $submitForm;
        $this->installCoreModInstance = $installCoreModInstance;

        $this->appRepo = $this->em
            ->getEm()
            ->getRepository("WasmAppBundle:App");
        $this->modRepo = $this->em
            ->getEm()
            ->getRepository("WasmModBundle:Mod");
    }

    public function execDemoScenes()
    {
        $demoApp = $this->appRepo->getDemoApp();
        $package = $demoApp
            ->getSections()[0]
            ->getPackages()[0];

        $this->submitForm->exec(array(
            "Wasm_AppScene_Scene" => array(
                "state" => array(
                    "name" => "TestScene",
                    "package" => $package->getId(),
                    "sceneType" => Scene::SCENE_TYPE_WEB,
                ),
            ),
        ));
    }

    public function exec(Scene $scene)
    {
        $this->em->persist($scene);
        
        if($scene->isAppScene()) {
            $this->createAppScene($scene);
        }
        else if($scene->isWebScene()) {
            $this->createWebScene($scene);
        }

        $this->em->flush();
    }

    private function createAppScene($scene)
    {
        $modInstance = $this->installCoreModInstance->execAppScene($scene);

        $scene->setModInstance($modInstance);
        $this->em->persist($modInstance);
    }

    private function createWebScene($scene)
    {
        $modInstance = $this->installCoreModInstance->execWebScene($scene);

        $scene->setModInstance($modInstance);
        $this->em->persist($modInstance);
    }
}
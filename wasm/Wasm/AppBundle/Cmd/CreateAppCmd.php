<?php
namespace Wasm\AppBundle\Cmd;

class CreateAppCmd
{
    private $em;
    private $filePath;
    private $fileCrud;
    private $submitForm;
    private $createSection;
    private $createPackage;
    private $installCoreModInstance;

    private $groupRepo;
    private $modRepo;

    public function __construct(
        $em, 
        $filePath, 
        $fileCrud, 
        $submitForm,
        $createSection,
        $createPackage,
        $installCoreModInstance
    ) {
        $this->em = $em;
        $this->filePath = $filePath;
        $this->fileCrud = $fileCrud;
        $this->submitForm = $submitForm;
        $this->createSection = $createSection;
        $this->createPackage = $createPackage;
        $this->installCoreModInstance = $installCoreModInstance;

        $this->groupRepo = $em->getEm()->getRepository("WasmAppBundle:Group");
        $this->modRepo = $em->getEm()->getRepository("WasmModBundle:Mod");
    }

    public function execDemoApps()
    {
        $this->submitForm->exec(array(
            "Wasm_App_App" => array(
                "state" => array(
                    "name" => "Fabalist",
                    "namespace" => "Faba",
                    "group" => $this->groupRepo->getDefaultGroup(),
                ),
            ),
        ));
    }

    public function exec($app)
    {
        $this->createApp($app);
        $this->createCmsApp($app);

        $this->em->persist($app);
        $this->em->flush();

        $this->createDefaultSection($app);
        $this->createDefaultPackage($app);

        $this->createDefaultCmsSection($app);
        $this->createDefaultCmsPackage($app);
    }

    private function createApp($app)
    {
        $this->fileCrud->createDir(
            $this->filePath
                ->src()
                ->group()
                ->app()
                ->getPath($app)
        );
        $this->fileCrud->createDir(
            $this->filePath
                ->srcui()
                ->src()
                ->group()
                ->app()
                ->getPath($app)
        );

        $this->installCoreModInstance->execAppEntry($app);
    }

    private function createCmsApp($app)
    {
        $this->fileCrud->createDir(
            $this->filePath
                ->src()
                ->group()
                ->app()
                ->getCmsPath($app)
        );
        $this->fileCrud->createDir(
            $this->filePath
                ->srcui()
                ->src()
                ->group()
                ->app()
                ->getCmsPath($app)
        );

        // @todo -> Add files to BlueprintsCms folder
        //       -> Copy BlueprintsCms files to cms folder
        // $this->installModInstance->exec(
        //     $this->createPackage->execSyntheticPackageForAppRoot($app),
        //     $this->modRepo->getMod(
        //         "Wasm", "App", "Entry"
        //     )
        // );
    }

    private function createDefaultSection($app)
    {
        $this
            ->createSection
            ->execDefaultSection($app);
    }

    private function createDefaultPackage($app)
    {
        $this
            ->createPackage
            ->execDefaultPackage($app);
    }

    private function createDefaultCmsSection($app)
    {
        $this
            ->createSection 
            ->execCmsDefaultSection($app);
    }

    private function createDefaultCmsPackage($app)
    {
        $this
            ->createPackage
            ->execCmsDefaultPackage($app);
    }
}
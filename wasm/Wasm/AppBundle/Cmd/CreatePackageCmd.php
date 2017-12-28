<?php
namespace Wasm\AppBundle\Cmd;

use Wasm\AppBundle\Entity\App;
use Wasm\AppBundle\Entity\Section;
use Wasm\AppBundle\Entity\Package;

class CreatePackageCmd
{
    const DEFAULT_PACKAGE_NAME = "App";

    private $em;
    private $filePath;
    private $fileCrud;
    private $submitForm;
    private $installCoreModInstance;

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

        $this->modRepo = $this->em
            ->getEm()
            ->getRepository("WasmModBundle:Mod");
    }

    public function execDefaultPackage(App $app)
    {
        // $app = $this->em
        //     ->getEm()
        //     ->getRepository("WasmAppBundle:App")
        //     ->getDemoApp();

        $this->submitForm->exec(array(
            "Wasm_App_Package" => array(
                "state" => array(
                    "name" => self::DEFAULT_PACKAGE_NAME,
                    "section" => $app->getSections()[0]->getId(),
                ),
                "entityId" => array(
                    "entityId" => null,
                    "section" => $app->getSections()[0]->getId(),
                ),
            ),
        ));
    }

    public function execCmsDefaultPackage(App $app)
    {
        $this->submitForm->exec(array(
            "Wasm_App_Package" => array(
                "state" => array(
                    "name" => "Cms",
                    "section" => $app->getCmsSections()[0]->getId(),
                    "packageType" => Package::PACKAGE_TYPE_ONLY_CMSAPP,
                ),
                "entityId" => array(
                    "entityId" => null,
                    "section" => $app->getCmsSections()[0]->getId(),
                ),
            ),
        ));
    }

    public function exec($package)
    {
        // @todo -> Wrap in $em->transactional
        $this->em->persist($package);
        $this->em->flush(); // Required for installCoreModInstance
        
        if($package->isPackageTypeAppAndCmsApp()) {
            $this->createPackage($package);
            $this->createCmsPackage($package);
        }
        else if($package->isPackageTypeOnlyApp())
            $this->createPackage($package);
        else if($package->isPackageTypeOnlyCmsApp())
            $this->createCmsPackage($package);
    }

    private function createPackage($package)
    {
        $this->createSrcPackageDirs($package);
        $this->createSrcuiPackageDirs($package);
    }

    private function createSrcPackageDirs($package)
    {
        $this->fileCrud->createDir(
            $this->filePath
                ->src()
                ->group()
                ->app()
                ->section()
                ->package()
                ->getPath($package)
        );
    }

    private function createSrcuiPackageDirs($package)
    {
        $packagePath = $this->filePath
            ->srcui()
            ->src()
            ->group()
            ->app()
            ->section()
            ->package()
            ->getPath($package);
        $this->fileCrud->createDir($packagePath);
        $this->installCoreModInstance->execPackageRoot($package);
        $this->installCoreModInstance->execAppEntryReducers(
            $package->getSection()->getApp()
        );
    }

    private function createCmsPackage($package)
    {
        $this->createSrcCmsPackageDirs($package);
        $this->createSrcuiCmsPackageDirs($package);
    }

    private function createSrcCmsPackageDirs($package)
    {
        $this->fileCrud->createDir(
            $this->filePath
                ->src()
                ->group()
                ->app()
                ->section()
                ->package()
                ->getCmsPath($package)
        );
    }

    private function createSrcuiCmsPackageDirs($package)
    {
        $packagePath = $this->filePath
            ->srcui()
            ->src()
            ->group()
            ->app()
            ->section()
            ->package()
            ->getCmsPath($package);
        $this->fileCrud->createDir($packagePath);
    }
}
<?php
namespace Wasm\AppBundle\Cmd;

use Wasm\AppBundle\Entity\App;
use Wasm\AppBundle\Entity\Section;

class CreateSectionCmd
{
    const DEFAULT_SECTION_NAME = "App";

    private $em;
    private $filePath;
    private $fileCrud;
    private $submitForm;
    private $installCoreModInstance;

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
    }

    public function execDefaultSection(App $app)
    {
        // $app = $this->em
        //     ->getEm()
        //     ->getRepository("WasmAppBundle:App")
        //     ->getDemoApp();

        $this->submitForm->exec(array(
            "Wasm_App_Section" => array(
                "state" => array(
                    "name" => self::DEFAULT_SECTION_NAME,
                    "app" => $app->getId(),
                ),
                "entityId" => array(
                    "entityId" => null,
                    "app" => $app->getId(),
                ),
            ),
        ));
    }

    public function execCmsDefaultSection(App $app)
    {
        $this->submitForm->exec(array(
            "Wasm_App_Section" => array(
                "state" => array(
                    "name" => "Cms",
                    "app" => $app->getId(),
                    "sectionType" => Section::SECTION_TYPE_ONLY_CMSAPP,
                ),
                "entityId" => array(
                    "entityId" => null,
                    "app" => $app->getId(),
                ),
            ),
        ));
    }

    public function exec($section)
    {
        // @todo -> Wrap in $em->transactional?
        $this->em->persist($section);
        $this->em->flush(); // Required for installCoreModInstance

        if($section->isSectionTypeAppAndCmsApp()) {
            $this->createSection($section);
            $this->createCmsSection($section);
        }
        else if($section->isSectionTypeOnlyApp())
            $this->createSection($section);
        else if($section->isSectionTypeOnlyCmsApp())
            $this->createCmsSection($section);
    }

    private function createSection($section)
    {
        $this->fileCrud->createDir(
            $this->filePath
                ->src()
                ->group()
                ->app()
                ->section()
                ->getPath($section)
        );
        $this->fileCrud->createDir(
            $this->filePath
                ->srcui()
                ->src()
                ->group()
                ->app()
                ->section()
                ->getPath($section)
        );

        $this->installCoreModInstance->execAppEntryReducers(
            $section->getApp()
        );
    }

    private function createCmsSection($section)
    {
        $this->fileCrud->createDir(
            $this->filePath
                ->src()
                ->group()
                ->app()
                ->section()
                ->getCmsPath($section)
        );
        $this->fileCrud->createDir(
            $this->filePath
                ->srcui()
                ->src()
                ->group()
                ->app()
                ->section()
                ->getCmsPath($section)
        );
    }
}
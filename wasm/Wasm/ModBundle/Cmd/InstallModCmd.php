<?php
namespace Wasm\ModBundle\Cmd;

use Wasm\ModBundle\Entity\Mod;

class InstallModCmd
{
    private $em;
    private $filePath;
    private $fileCrud;
    private $submitForm;

    public function __construct($em, $filePath, $fileCrud, $submitForm)
    {
        $this->em = $em;
        $this->filePath = $filePath;
        $this->fileCrud = $fileCrud;
        $this->submitForm = $submitForm;
    }

    public function execDefaultMods()
    {
        $mod = new Mod();
        $mod->setVendor("Wasm");
        $mod->setGroupName("Core");
        $mod->setName("WebScene");
        //$module->setIsStatic(true);

        $this->em->getEm()->persist($mod);

        $mod = new Mod();
        $mod->setVendor("Wasm");
        $mod->setGroupName("Core");
        $mod->setName("AppScene");

        $this->em->getEm()->persist($mod);

        $mod = new Mod();
        $mod->setVendor("Wasm");
        $mod->setGroupName("Core");
        $mod->setName("PackageRoot");

        $this->em->getEm()->persist($mod);

        $mod = new Mod();
        $mod->setVendor("Wasm");
        $mod->setGroupName("Core");
        $mod->setName("AppEntry");

        $this->em->getEm()->persist($mod);

        $this->em->flush();
        // $app = $this->em
        //     ->getEm()
        //     ->getRepository("WasmAppBundle:App")
        //     ->getDemoApp();

        // $this->submitForm->handle(array(
        //     "Wasm_App_AppSection_AppSection" => array(
        //         "state" => array(
        //             "name" => self::DEFAULT_SECTION_NAME,
        //             "app" => $app->getId(),
        //         ),
        //         "entityId" => array(
        //             "entityId" => null,
        //             "app" => $app->getId(),
        //         ),
        //     ),
        // ));
    }

    public function exec($appPackage, $module)
    {
        echo "Handle!";
        // $this->fileManipulator->createDir(
        //     $this->filePath->getSrcAppSectionPath($appSection)
        // );
        // $this->fileManipulator->createDir(
        //     $this->filePath->getSrcUiAppGroupPath($appGroup)
        // );

        // $this->em->persist($appSection);
        // $this->em->flush();
    }
}
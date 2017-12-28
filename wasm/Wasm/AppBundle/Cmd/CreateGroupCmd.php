<?php
namespace Wasm\AppBundle\Cmd;

class CreateGroupCmd
{
    const DEFAULT_GROUP_NAME = "Apps";

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

    public function execDefaultGroup()
    {
        $this->submitForm->exec(array(
            "Wasm_App_Group" => array(
                "state" => array(
                    "name" => self::DEFAULT_GROUP_NAME,
                    "isDefault" => true,
                ),
            ),
        ));
    }

    public function exec($group)
    {
        $this->fileCrud->createDir(
            $this->filePath
                ->src()
                ->group()
                ->getPath($group)
        );
        $this->fileCrud->createDir(
            $this->filePath
                ->srcui()
                ->src()
                ->group()
                ->getPath($group)
        );
        
        $this->em->persist($group);
        $this->em->flush();
    }
}
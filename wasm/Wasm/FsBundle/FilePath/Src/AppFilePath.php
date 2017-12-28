<?php
namespace Wasm\FsBundle\FilePath\Src;

use Wasm\AppBundle\Entity\App;

class AppFilePath
{
    private $groupFilePath;
    private $sectionFilePath;

    public function __construct($sectionFilePath)
    {
        $this->sectionFilePath = $sectionFilePath;
        $this->sectionFilePath->initialize($this);
    }

    public function initialize($groupFilePath)
    {
        $this->groupFilePath = $groupFilePath;
    }

    public function section()
    {
        return $this->sectionFilePath;
    }

    // /wsrc/Apps/DemoApp/
    public function getPath(App $app)
    {
        return $this->groupFilePath->getPath($app->getGroup()) . 
               $app->getNamespace() . "/";
    }

    // /wsrc/Apps/DemoAppCms/
    public function getCmsPath(App $app)
    {
        return $this->groupFilePath->getPath($app->getGroup()) .
               $app->getNamespace() . "Cms/";
    }
}
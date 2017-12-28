<?php
namespace Wasm\FsBundle\FilePath\Srcui\Src;

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

    // /wsrcui/wsrc/Apps/DemoApp/
    public function getPath(App $app)
    {
        return $this->groupFilePath->getPath($app->getGroup()) . 
               $app->getNamespace() . "/";
    }

    // /wsrcui/wsrc/Apps/DemoAppCms/
    public function getCmsPath(App $app)
    {
        return $this->groupFilePath->getPath($app->getGroup()) .
               $app->getNamespace() . "Cms/";
    }
}
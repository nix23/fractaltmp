<?php
namespace Wasm\ModRenderBundle\Model;

use Wasm\UtilBundle\Util\Str;

class BlueprintData
{
    const COPY_FILE = 0;
    const MERGE_YML_CONFIG = 1;

    private $copyType = self::COPY_FILE;

    public $filename;
    public $filepath;
    public $filecontent;

    public $dirs = array();
    public $renderFilename;
    public $renderCode;

    public function __construct($filename, $filepath)
    {
        $this->filename = $filename;
        $this->filepath = $filepath . "/" . $filename;
        $this->filecontent = file_get_contents($this->filepath);

        $this->findDirs($filepath);
        // @todo -> Detect if services.yml
    }

    public function getId()
    {
        $id = "";
        if(count($this->dirs) > 0)
            $id = implode("/", $this->dirs) . "/";

        return $id . $this->filename;
    }

    private function findDirs($filepath)
    {
        $dirs = explode("/", $filepath);
        $rootFound = false;
        foreach($dirs as $dir) {
            if($dir == "Blueprints") {
                $rootFound = true;
                continue;
            }

            if($rootFound)
                $this->dirs[] = $dir;
        }
    }

    // public function getDirsPath()
    // {
    //     $path = implode("/", $this->dirs);
    //     return (Str::isNotBlank($path)) ? $path . "/" : $path;
    // }

    public function shouldCopyFile()
    {
        return $this->copyType == self::COPY_FILE;
    }

    public function shouldMergeYmlConfig()
    {
        return $this->copyType == self::MERGE_YML_CONFIG;
    }
}
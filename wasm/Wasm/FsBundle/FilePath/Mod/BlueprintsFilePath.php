<?php
namespace Wasm\FsBundle\FilePath\Mod;

use Wasm\ModBundle\Entity\Mod;

class BlueprintsFilePath
{
    private $modFilePath;
    
    public function initialize($modFilePath)
    {
        $this->modFilePath = $modFilePath;
    }

    // /wmod/Wasm/Slider/Blueprints/
    public function getPath(Mod $mod)
    {
        return $this->modFilePath->getPath($mod) . "Blueprints/";
    }
}
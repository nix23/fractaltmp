<?php
namespace Wasm\FsBundle\FilePath\Mod;

use Wasm\ModBundle\Entity\Mod;

class ModFilePath
{
    private $groupFilePath;
    private $blueprintsFilePath;

    public function __construct($blueprintsFilePath)
    {
        $this->blueprintsFilePath = $blueprintsFilePath;
        $this->blueprintsFilePath->initialize($this);
    }

    public function initialize($groupFilePath)
    {
        $this->groupFilePath = $groupFilePath;
    }

    public function blueprints()
    {
        return $this->blueprintsFilePath;
    }

    // /wmod/Wasm/Slider/Blueprints/
    public function getPath(Mod $mod)
    {
        return $this->groupFilePath->getPath($mod) .  $mod->getName() . "/";
    }
}
<?php
namespace Wasm\ModRenderBundle\Blueprints;

use Wasm\ModRenderBundle\Model\BlueprintData;

class ScanModBlueprints
{
    private $filePath;
    private $fileCrud;

    public function __construct($filePath, $fileCrud)
    {
        $this->filePath = $filePath;
        $this->fileCrud = $fileCrud;
    }

    public function execSrc($mod, $onlyBlueprints)
    {
        return $this->scanModBlueprints(
            $this->filePath
                ->mod()
                ->vendor()
                ->group()
                ->mod()
                ->blueprints()
                ->getPath($mod),
            $onlyBlueprints
        );
    }

    public function execSrcui($mod, $onlyBlueprints)
    {
        return $this->scanModBlueprints(
            $this->filePath
                ->srcui()
                ->mod()
                ->vendor()
                ->group()
                ->mod()
                ->blueprints()
                ->getPath($mod),
            $onlyBlueprints
        );
    }

    private function scanModBlueprints(
        $blueprintsFilePath,
        $onlyBlueprints = array()
    ) {
        $blueprints = array();
        $files = $this->fileCrud->readFilesRecursive(
            $blueprintsFilePath
        );
        
        foreach($files as $file) {
            $blueprint = new BlueprintData($file["name"], $file["path"]);
            if(count($onlyBlueprints) > 0 &&
               !in_array($blueprint->getId(), $onlyBlueprints))
                continue;

            $blueprints[] = $blueprint;
        }

        return $blueprints;
    }
}
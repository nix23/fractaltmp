<?php
namespace Wasm\ModRenderBundle\Cmd;

use Wasm\AppBundle\Entity\Package;
use Wasm\ModBundle\Entity\Mod;
use Wasm\ModBundle\Entity\ModInstance;

class RenderModInstanceCmd
{
    private $filePath;
    private $fileCrud;
    private $scanModBlueprints;
    private $renderBlueprint;

    public function __construct(
        $filePath, 
        $fileCrud, 
        $scanModBlueprints,
        $renderBlueprint
    ) {
        $this->filePath = $filePath;
        $this->fileCrud = $fileCrud;
        $this->scanModBlueprints = $scanModBlueprints;
        $this->renderBlueprint = $renderBlueprint;
    }

    public function exec(
        Package $package, 
        Mod $mod,
        ModInstance $modInstance,
        $params = array(),
        $onlyBlueprints = array()
    ) {
        if($this->hasModDefinitionForSrc($mod))
            $this->execModInstanceForSrc(
                $package, 
                $mod, 
                $modInstance,
                $params,
                $onlyBlueprints
            );
        if($this->hasModDefinitionForSrcui($mod))
            $this->execModInstanceForSrcui(
                $package, 
                $mod, 
                $modInstance,
                $params,
                $onlyBlueprints
            );
    }

    private function hasModDefinitionForSrc($mod)
    {
        return $this->fileCrud->exists(
            $this->filePath
                ->mod()
                ->vendor()
                ->group()
                ->mod()
                ->getPath($mod)
        );
    }

    private function hasModDefinitionForSrcui($mod)
    {
        return $this->fileCrud->exists(
            $this->filePath
                ->srcui()
                ->mod()
                ->vendor()
                ->group()
                ->mod()
                ->getPath($mod)
        );
    }

    private function execModInstanceForSrc(
        $package, 
        $mod,
        $modInstance,
        $params,
        $onlyBlueprints
    ) {
        $blueprints = $this
            ->scanModBlueprints
            ->execSrc(
                $mod,
                $onlyBlueprints
            );
        foreach($blueprints as $blueprint)
            $this->renderBlueprint->execSrc(
                $blueprint,
                $package,
                $mod,
                $modInstance,
                $params
            );

        $this->renderBlueprintsToTargetPackage(
            $this->filePath
                ->src()
                ->group()
                ->app()
                ->section()
                ->package()
                ->getPath($package),
            $blueprints
        );
    }

    private function execModInstanceForSrcui(
        $package, 
        $mod,
        $modInstance,
        $params,
        $onlyBlueprints
    ) {
        $blueprints = $this
            ->scanModBlueprints
            ->execSrcui(
                $mod,
                $onlyBlueprints
            );
        foreach($blueprints as $blueprint)
            $this->renderBlueprint->execSrcui(
                $blueprint,
                $package,
                $mod,
                $modInstance,
                $params
            );

        $this->renderBlueprintsToTargetPackage(
            $this->filePath
                ->srcui()
                ->src()
                ->group()
                ->app()
                ->section()
                ->package()
                ->getPath($package),
            $blueprints
        );
    }

    private function renderBlueprintsToTargetPackage(
        $packageFilePath,
        $blueprints
    ) {
        foreach($blueprints as $blueprint) {
            $blueprintTargetFilePath = substr(
                $packageFilePath, 0, strlen($packageFilePath)
            );
            if(count($blueprint->dirs) > 0) 
                $blueprintTargetFilePath = $this->writeDirs(
                    $blueprint, $blueprintTargetFilePath
                );

            $blueprintTargetFilePath .= $blueprint->renderFilename;
            $this->fileCrud->createFile(
                $blueprintTargetFilePath,
                $blueprint->renderCode
            );
        }
    }

    private function writeDirs($blueprint, $blueprintTargetFilePath)
    {
        foreach($blueprint->dirs as $blueprintDir) {
            $blueprintTargetFilePath .= $blueprintDir . "/";
        
            if($this->fileCrud->exists($blueprintTargetFilePath))
                continue;

            $this->fileCrud->createDir($blueprintTargetFilePath);
        }

        return $blueprintTargetFilePath;
    }
}
<?php
namespace Wasm\Slider\Blueprints;

// @todo -> Impl.GenInt, Ext.AbsGen?
class Generator
{
    public function generateModuleInstance($bundlePath)
    {
        // $this->generateController(); genEntities(), repo, moduleManager
        // or scan all Dirs???
    }

    private function getBlueprints()
    {
        $blueprints = array(); 
        // findAll BluePrints and transform to DataStruct
        // (Collection of BluePrint models)
        // { path: "", dirs: ["Controller", "Subfolder"] }

        // GeneratorBundle/Generator.php
        //     -> Launches ModuleGenerator
        //         -> ModuleGenerator builds BluePrints[]
        //     -> ModuleGenerator reads composeWith calls
        //         -> and builds [[ModuleABluePrints], [MOduleNBluePrints]]
        //     -> ComposedBlueprints[]
        //         -> can be dumped to Browser on ModuleDevelopment page
        //         -> or dump in FS demo App?

        // renderFile(sourcePath, targetPath, args)
    }
}
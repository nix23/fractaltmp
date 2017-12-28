<?php
namespace Wasm\FsBundle\File;

use Wasm\AppBundle\Entity\Group;
use Wasm\AppBundle\Entity\App;
use Wasm\AppBundle\Entity\Section;
use Wasm\AppBundle\Entity\Package;
use Wasm\ModBundle\Entity\Mod;

class Path
{
    private $rootFilePath;
    private $modFilePath;
    private $srcFilePath;
    private $srcuiFilePath;
    private $webFilePath;

    public function __construct(
        $rootFilePath,
        $modFilePath,
        $srcFilePath,
        $srcuiFilePath,
        $webFilePath
    ) {
        $this->rootFilePath = $rootFilePath;
        $this->modFilePath = $modFilePath;
        $this->srcFilePath = $srcFilePath;
        $this->srcuiFilePath = $srcuiFilePath;
        $this->webFilePath = $webFilePath;
    }

    public function root()
    {
        return $this->rootFilePath;
    }

    public function mod()
    {
        return $this->modFilePath;
    }

    public function src()
    {
        return $this->srcFilePath;
    }

    public function srcui()
    {
        return $this->srcuiFilePath;
    }

    public function web()
    {
        return $this->webFilePath;
    }

    // public function __call($method, $args)
    // {
    //     foreach($this->resolvers as $resolver) {
    //         if(method_exists($resolver, $method))
    //             return call_user_func_array(
    //                 array($resolver, $method),
    //                 $args
    //             );
    //     }

    //     throw new \Exception("FilePath: can't find resolver for method: $method");
    // }

    // Deprecated -> Can be used as single container for all other filePath 
    // class methods (If passing each filePath service will become too uncomfortable)
    // public function getModPath()
    // public function getModModulePath(Module $module)
    // public function getModModuleBlueprintsPath(Module $module)
    // public function getSrcPath()
    // public function getSrcAppSectionPath() ...
    // pf getSrcUiPath()
    // public function getSrcUiSrcPath()
    // public function getSrcUiAppGroupPath(AppGroup $appGroup)
}
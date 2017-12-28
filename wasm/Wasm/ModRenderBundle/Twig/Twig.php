<?php
namespace Wasm\ModRenderBundle\Twig;

use Wasm\ModRenderBundle\Model\BlueprintData;
use Wasm\ModBundle\Entity\Mod;
use Wasm\ModRenderBundle\Twig\TwigLexer;

class Twig
{
    private $filePath;
    private $twigMod;
    private $twigSrcuiMod;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->twigMod = $this->createTwigInstance(
            $filePath->mod()->getPath()
        );
        $this->twigSrcuiMod = $this->createTwigInstance(
            $filePath->srcui()->mod()->getPath()
        );
    }

    private function createTwigInstance($rootFilePath)
    {
        $loader = new \Twig_Loader_Filesystem($rootFilePath);
        $twig = new \Twig_Environment($loader, array());
        
        $lexer = new TwigLexer($twig, array(
            'tag_variable' => array('<%', '%>'),
        ));
        $twig->setLexer($lexer);
        
        return $twig;
    }

    private function getBlueprintPath($blueprint, $mod)
    {
        $filePath = array_merge(
            array(
                $mod->getVendor(),
                $mod->getGroupName(),
                $mod->getName(),
                "Blueprints",
            ),
            $blueprint->dirs
        );
        return implode("/", $filePath) . "/" . $blueprint->filename;
    }

    public function renderModFile(
        BlueprintData $blueprint, 
        Mod $mod,
        $params = array()
    ) {
        $blueprint->renderCode = $this->twigMod->render(
            $this->getBlueprintPath($blueprint, $mod),
            $params
        );
    }

    public function renderSrcuiModFile(
        BlueprintData $blueprint,
        Mod $mod,
        $params = array()
    ) {
        $blueprint->renderCode = $this->twigSrcuiMod->render(
            $this->getBlueprintPath($blueprint, $mod),
            $params
        );
    }
}
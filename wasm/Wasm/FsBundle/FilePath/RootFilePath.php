<?php
namespace Wasm\FsBundle\FilePath;

class RootFilePath
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    // /
    public function getPath()
    {
        $root = $this->container->get('kernel')->getRootDir();
        $root .= "/../../../";

        return $root;
    }
}
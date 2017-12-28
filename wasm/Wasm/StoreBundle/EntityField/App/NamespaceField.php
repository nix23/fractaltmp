<?php
namespace Wasm\StoreBundle\EntityField\App;

use Doctrine\ORM\Mapping AS ORM;

trait NamespaceField
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $namespace = "";

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }
}
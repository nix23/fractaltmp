<?php
namespace Wasm\ApiBundle\Serializer\PropsTransformer\TransformerBase;

use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractPropTransformer
{
    protected $container;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
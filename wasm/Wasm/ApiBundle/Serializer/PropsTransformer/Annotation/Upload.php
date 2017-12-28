<?php
namespace Wasm\ApiBundle\Serializer\PropsTransformer\Annotation;

/**
 * @Annotation
 * @Target({"METHOD", "PROPERTY"})
 */
class Upload
{
    public $cloneName = null;

    public function hasCloneName()
    {
        return $this->cloneName !== null;
    }
}
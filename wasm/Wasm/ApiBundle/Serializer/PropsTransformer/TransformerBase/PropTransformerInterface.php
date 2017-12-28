<?php
namespace Wasm\ApiBundle\Serializer\PropsTransformer\TransformerBase;

interface PropTransformerInterface
{
    public function transform($propValue, $annotation);
}
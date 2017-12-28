<?php
namespace Wasm\ApiBundle\Serializer\PropsTransformer;

use Wasm\ApiBundle\Serializer\PropsTransformer\TransformerBase\AbstractPropTransformer;
use Wasm\ApiBundle\Serializer\PropsTransformer\TransformerBase\PropTransformerInterface;

class UploadTransformer extends AbstractPropTransformer implements PropTransformerInterface
{
    public function transform($propValue, $annotation)
    {
        $uploader = $this->container->get("ntech_upload");
        if($annotation->hasCloneName())
            $url = $uploader->getUrl($propValue, $annotation->cloneName);
        else
            $url = $uploader->getUrl($propValue);

        return $url;
    }
}
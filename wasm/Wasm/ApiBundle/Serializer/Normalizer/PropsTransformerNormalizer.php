<?php
namespace Wasm\ApiBundle\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Wasm\ApiBundle\Serializer\PropsTransformerInterface;

/**
 * Props transformer normalizer
 */
class PropsTransformerNormalizer extends SerializerAwareNormalizer implements NormalizerInterface
{
    private $propsTransformer;

    public function __construct($propsTransformer)
    {
        $this->propsTransformer = $propsTransformer;
    }

    public function normalize($object, $format = null, array $context = array())
    {
        // We should call original normalizer to get
        // array with original object normalized data first.
        // Then we will transform it's special props marked with
        // PropsTransformer annotations.
        $data = $this->serializer->normalize(
            $object, $format, $context
        );

//         $isDebug = false;
//         if(get_class($object) == "Proxies\__CG__\Ntech\CoreBundle\Entity\Account"
// )
//             $isDebug = true;
            //cl("Proxy!");
        
        // Restoring object state to allow normalize same
        // object in other entities.(When is embedded entity)
        unset($object->__isPropsTransformerNormalizerCall);

        $data = $this->propsTransformer->transform(
            $object,
            $data
            // function($object) use ($format, $context) {
            //     return $this->serializer->normalize(
            //         $object, $format, $context
            //     );
            // }
        );
        
        return $data;
    }

    // We should check for infinite recursion here. When 
    // $this->serializer->normalize is called from the
    // upper normalize fn (to get original transformation data)
    // we should NOT pass it to that upper normalize fn again.
    // (It will cause infinite recursion)
    public function supportsNormalization($data, $format = null)
    {
//         if(is_object($data))
//             if(get_class($data) != "Ntech\CoreBundle\Entity\Chat\Chat" &&
//                 get_class($data) != "DateTime" && 
//                 get_class($data) != "Ntech\CoreBundle\Entity\Chat\ChatMember"
// )
//                 cl(get_class($data));
        // else
        //     cl($data);

        if(!($data instanceof PropsTransformerInterface))
            return false;
        // if(!($data instanceof PropsTransformerInterface) &&
        //    !$this->isArrayOfPropTransformerInstances($data))
        //     return false;

        // if(is_array($data))
        //     cl(get_class($data[0]));

        $isCallFromPropsTransformerNormalizer = property_exists(
            $data, "__isPropsTransformerNormalizerCall"
        );

        if($isCallFromPropsTransformerNormalizer) 
            return false;

        $data->__isPropsTransformerNormalizerCall = true;
        return true;
    }

    // Used with 1:M relationships. Like 
    // private function isArrayOfPropTransformerInstances($data)
    // {
    //     if(!is_array($data))
    //         return false;

    //     $areAllInstances = true;
    //     foreach($data as $instance) {
    //         if(!$instance instanceof PropsTransformerInterface)
    //             $areAllInstances = false;
    //     }

    //     return $areAllInstances;
    // }
}
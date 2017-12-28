<?php
namespace Wasm\ApiBundle\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Date time normalizer
 */
class DateTimeNormalizer extends SerializerAwareNormalizer implements NormalizerInterface
{
    public function normalize($object, $format = null, array $context = array())
    {
        return $object->format("Y-m-d H:i:s");
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof \DateTime;
    }
}
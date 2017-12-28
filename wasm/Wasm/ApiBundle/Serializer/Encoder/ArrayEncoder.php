<?php
namespace Wasm\ApiBundle\Serializer\Encoder;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

/**
 * Array Encoder
 */
class ArrayEncoder implements EncoderInterface, DecoderInterface
{
    public function encode($data, $format, array $context = array())
    {
        return $data;
    }

    public function supportsEncoding($format)
    {
        return 'array' === $format;
    }

    public function decode($data, $format, array $context = array())
    {
        return $data;
    }

    public function supportsDecoding($format)
    {
        return 'array' === $format;
    }
}
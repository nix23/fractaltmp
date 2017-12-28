<?php
namespace <%PKGCLASS%>\Serializer;

class <%MODNAME%>Serializer
{
    public function serialize($item)
    {
        return array(
            "id" => $item->getId(),
            "name" => $item->getName(),
        );
    }
}
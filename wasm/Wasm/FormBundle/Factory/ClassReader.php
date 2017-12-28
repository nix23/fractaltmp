<?php
namespace Wasm\FormBundle\Factory;

class ClassReader
{
    // @todo -> Fix this -> Now we have multiple Bundle IDS
    // const FORM_CLASS_PREFIX = "\\Ntech\\";
    // Types of form ids:
    //      -> /wasm/NAMESPACE || /wsrc/NAMESPACE
    //      -> by default points to /wsrc
    //      -> if starts from 'special key' points to /wasm
    // const FORM_CLASS_PREFIX = "\\Wasm\\"; -> Deprecated
    const WASM_NAMESPACE_ID = "Wasm";

    const FORM_CLASS_POSTFIX = "Form";
    const STORE_CLASS_POSTFIX = "Store";

    private function getClassPrefix($namespaceId, $bundleId)
    {
        $path = "\\";
        $path .= ($namespaceId == self::WASM_NAMESPACE_ID)
            ? self::WASM_NAMESPACE_ID 
            : ""; // /wsrc

        $path .= "\\" . $bundleId;

        return $path;
    }

    public function getFormClassPrefix($namespaceId, $bundleId)
    {
        //$class = self::FORM_CLASS_PREFIX;
        $class = $this->getClassPrefix($namespaceId, $bundleId) . "Bundle\\Form\\";

        return $class;
    }

    public function getFormClassPostfix()
    {
        return self::FORM_CLASS_POSTFIX;
    }

    public function getStoreClassPrefix($namespaceId, $bundleId)
    {
        //$class = self::FORM_CLASS_PREFIX;
        $class = $this->getClassPrefix($namespaceId, $bundleId) . "Bundle\\Form\\";

        return $class;
    }

    public function getStoreClassPostfix()
    {
        return self::STORE_CLASS_POSTFIX;
    }
}
<?php
namespace Wasm\AppSceneBundle\Store;

use Wasm\StoreBundle\Store\Repo;
use Wasm\AppSceneBundle\Entity\Layout;

class LayoutRepo extends Repo
{
    public function getAllAppLayouts()
    {
        $q = $this->q("
            SELECT l FROM WasmAppSceneBundle:Layout l
            WHERE l.layoutType = :layoutType
        ")
            ->setParameter("layoutType", Layout::LAYOUT_TYPE_APP);

        return $this->rows($q);
    }

    public function getAllWebLayouts()
    {
        $q = $this->q("
            SELECT l FROM WasmAppSceneBundle:Layout l
            WHERE l.layoutType = :layoutType
        ")
            ->setParameter("layoutType", Layout::LAYOUT_TYPE_WEB);

        return $this->rows($q);
    }

    public function getByName($name, $type)
    {
        $q = $this->q("
            SELECT l FROM WasmAppSceneBundle:Layout l
            WHERE l.name = :name
        ")
            ->setParameter("name", $name);

        return $this->row($q);
    }

    public function getAppByName($name)
    {
        return $this->getByName($name, Layout::LAYOUT_TYPE_APP);
    }

    public function getWebByName($name)
    {
        return $this->getByName($name, Layout::LAYOUT_TYPE_WEB);
    }
}
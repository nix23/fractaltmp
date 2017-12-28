<?php
namespace Wasm\AppBundle\Form\DataSource;

use Wasm\FormBundle\Util\DataSource;

class GroupData
{
    private $repo;

    public function initialize($em)
    {
        $this->repo = $em->getEm()->getRepository("WasmAppBundle:Group");
    }

    public function getGroupData()
    {
        return $this->repo->findAll();
    }

    public static function getGroupDataProps($group)
    {
        return array("label" => $group->getName());
    }
}
<?php
namespace Wasm\StoreBundle\EntityField\App;

use Doctrine\ORM\Mapping AS ORM;

trait GroupNameField
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $groupName = "";

    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;
    }

    public function getGroupName()
    {
        return $this->groupName;
    }
}
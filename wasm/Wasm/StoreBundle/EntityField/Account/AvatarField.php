<?php
namespace Wasm\StoreBundle\EntityField\Account;

use Doctrine\ORM\Mapping AS ORM;
use Wasm\UtilBundle\Util\Str;

trait AvatarField
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avatar = "";

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function getHasAvatar()
    {
        return Str::isNotBlank($this->getAvatar());
    }
}
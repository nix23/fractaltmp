<?php
namespace <%PKGCLASS%>\Entity\User;

use Wasm\UtilBundle\Util\Str;

// @todo -> ~== AccountTypeTrait.php?
trait UserSocialIdTrait
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $facebookId = "";

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $linkedinId = "";

    public function getFacebookId()
    {
        return $this->facebookId;
    }

    public function setFacebookId($facebookId)
    {
        $this->facebookId = Str::emptyStrIfNull($facebookId);
    }

    public function hasFacebookId()
    {
        return Str::isNotBlank($this->getFacebookId());
    }

    public function getLinkedinId()
    {
        return $this->linkedinId;
    }

    public function setLinkedinId($linkedinId)
    {
        $this->linkedinId = Str::emptyStrIfNull($linekdinId);
    }

    public function hasLinkedinId()
    {
        return Str::isNotBlank($this->getLinkedinId());
    }

    public function getHasSocialId()
    {
        return (
            $this->hasFacebookId() ||
            $this->hasLinkedinId()
        );
    }
}
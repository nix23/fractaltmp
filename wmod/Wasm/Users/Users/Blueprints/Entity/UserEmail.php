<?php
namespace <%PKGCLASS%>\Entity;

use Doctrine\ORM\Mapping as ORM;

use Wasm\StoreBundle\EntityField;
use Wasm\UtilBundle\Util\Str;

/**
 * @ORM\Table(
 *     name="<%TABLE%>"
 * )
 * @ORM\Entity(
 *     repositoryClass="<%PKGCLASS%>\Repo\UserEmailRepo"
 * )
 */
class UserEmail
{
    use EntityField\IdField;
    use EntityField\User\EmailField;
    use EntityField\IsDefaultField;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $activationCode;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="UserCredentials",
     *     inversedBy="userEmails"
     * )
     */
    private $userCredentials;

    public function getUserCredentials()
    {
        return $this->userCredentials;
    }

    public function setUserCredentials($userCredentials)
    {
        $this->userCredentials = $userCredentials;
    }

    public function setActivationCode($code)
    {
        $this->activationCode = $code;
    }

    public function getActivationCode()
    {
        return $this->activationCode;
    }

    public function getIsActivated()
    {
        return Str::isBlank($this->getActivationCode());
    }

    // @todo -> pf hasAdditionalEmails()
}
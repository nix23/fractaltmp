<?php
namespace <%PKGCLASS%>\Entity;

use Doctrine\ORM\Mapping as ORM;

use Wasm\StoreBundle\EntityField;
use Wasm\UtilBundle\Util\DateTime;
use Wasm\UtilBundle\Util\Str;

/**
 * @ORM\Table(
 *     name="<%TABLE%>"
 * )
 * @ORM\Entity(
 *     repositoryClass="<%PKGCLASS%>\Repo\UserPwdResetRepo"
 * )
 */
class UserPwdReset
{
    const TOKEN_MAX_USAGES_COUNT = 15;

    use EntityField\IdField;
    use EntityField\CreatedAtField;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="integer")
     */
    private $tokenUsagesCount = 0;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="UserCredentials",
     *     inversedBy="userPwdResets"
     * )
     */
    private $userCredentials;

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function wasTokenCreatedInLastHour()
    {
        return DateTime::isInLastHour($this->getCreatedAt());
    }

    public function getTokenUsagesCount()
    {
        return $this->tokenUsagesCount;
    }

    public function setTokenUsagesCount($count)
    {
        $this->tokenUsagesCount = $count;
    }

    public function getUserCredentials()
    {
        return $this->userCredentials;
    }

    public function setUserCredentials($userCredentials)
    {
        $this->userCredentials = $userCredentials;
    }

    public function markTokenUsage()
    {
        $count = (int)$this->getTokenUsagesCount();
        $this->setTokenUsagesCount(++$count);
    }

    public function canUseToken()
    {
        $count = (int)$this->getTokenUsagesCount();
        return (
            $count <= self::TOKEN_MAX_USAGES_COUNT &&
            Str::isNotBlank($this->getToken())
        );
    }

    public function flushToken()
    {
        $this->setToken("");
    }
}
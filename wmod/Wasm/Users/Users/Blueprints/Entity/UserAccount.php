<?php
namespace <%PKGCLASS%>\Entity;

use Doctrine\ORM\Mapping as ORM;

use Wasm\StoreBundle\EntityField;

/**
 * @ORM\Table(
 *     name="<%TABLE%>"
 * )
 * @ORM\Entity(
 *     repositoryClass="<%PKGCLASS%>\Repo\UserAccountRepo"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class UserAccount
{
    use EntityField\IdField;
    // use EntityField\NameField;
    // use EntityField\DescriptionField;
    // use EntityField\SortField;
    // use EntityField\IsEnabledField;
    use EntityField\CreatedAtField;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="User",
     *     inversedBy="userAccount"
     * )
     */
    private $user;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Account",
     *     inversedBy="userAccount"
     * )
     */
    private $account;

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function setAccount($account)
    {
        $this->account = $account;
    }
}
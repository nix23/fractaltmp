<?php
namespace <%PKGCLASS%>\Entity;

use Doctrine\ORM\Mapping as ORM;

use Wasm\StoreBundle\EntityField;

/**
 * @ORM\Table(
 *     name="<%TABLE%>"
 * )
 * @ORM\Entity(
 *     repositoryClass="<%PKGCLASS%>\Repo\UserRepo"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class User
{
    use EntityField\IdField;
    // use EntityField\NameField;
    // use EntityField\DescriptionField;
    // use EntityField\SortField;
    // use EntityField\IsEnabledField;
    use EntityField\CreatedAtField;

    /**
     * @ORM\OneToOne(
     *     targetEntity="UserCredentials",
     *     mappedBy="user"
     * )
     */
    private $userCredentials;

    /**
     * @ORM\OneToMany(
     *     targetEntity="UserAccount",
     *     mappedBy="user"
     * )
     */
    private $userAccount;

    public function getUserCredentials()
    {
        return $this->userCredentials;
    }

    public function setUserCredentials($userCredentials)
    {
        $this->userCredentials = $userCredentials;
    }

    public function getUserAccount()
    {
        return $this->userAccount;
    }

    public function setUserAccount($userAccount)
    {
        $this->userAccount = $userAccount;
    }
}
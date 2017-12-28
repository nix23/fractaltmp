<?php
namespace <%PKGCLASS%>\Entity;

use Doctrine\ORM\Mapping as ORM;

use Wasm\StoreBundle\EntityField;
{% if accountTypes is defined %}
use <%PKGCLASS%>\Entity\Account\AccountTypeTrait;
{% endif %}

/**
 * @ORM\Table(
 *     name="<%TABLE%>"
 * )
 * @ORM\Entity(
 *     repositoryClass="<%PKGCLASS%>\Repo\AccountRepo"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class Account
{
    use EntityField\IdField;
    // use EntityField\NameField;
    // use EntityField\DescriptionField;
    // use EntityField\SortField;
    // use EntityField\IsEnabledField;
    use EntityField\CreatedAtField;
    {% if accountTypes is defined %}
    use AccountTypeTrait;
    {% endif %}

    /**
     * @ORM\OneToMany(
     *     targetEntity="UserAccount",
     *     mappedBy="user"
     * )
     */
    private $userAccount;

    public function getUserAccount()
    {
        return $this->userAccount;
    }

    public function setUserAccount($userAccount)
    {
        $this->userAccount = $userAccount;
    }
}
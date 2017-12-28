<?php
namespace <%PKGCLASS%>\Entity;

use Doctrine\ORM\Mapping as ORM;

use Wasm\StoreBundle\EntityField;

/**
 * @ORM\Table(
 *     name="<%TABLE%>"
 * )
 * @ORM\Entity(
 *     repositoryClass="<%PKGCLASS%>\Repo\UserSocialDataRepo"
 * )
 */
class UserSocialData
{
    use EntityField\IdField;
    use EntityField\Account\FirstNameField;
    use EntityField\Account\LastNameField;
    use EntityField\Account\AvatarField;

    /**
     * @ORM\OneToOne(
     *     targetEntity="UserCredentials",
     *     inversedBy="userSocialData"
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
}
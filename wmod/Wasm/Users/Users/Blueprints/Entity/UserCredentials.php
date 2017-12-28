<?php
namespace <%PKGCLASS%>\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Wasm\StoreBundle\EntityField;
use <%PKGCLASS%>\Entity\User\SocialIdTrait;

/**
 * @ORM\Table(
 *     name="<%TABLE%>"
 * )
 * @ORM\Entity(
 *     repositoryClass="<%PKGCLASS%>\Repo\UserCredentialsRepo"
 * )
 */
class UserCredentials
{
    use EntityField\IdField;
    use EntityField\User\UsernameField;
    use EntityField\User\PasswordField;
    use SocialIdTrait;

    /**
     * @ORM\OneToOne(
     *     targetEntity="User",
     *     inversedBy="userCredentials"
     * )
     */
    private $user;

    /**
     * @ORM\OneToMany(
     *     targetEntity="UserEmail",
     *     mappedBy="user"
     * )
     */
    private $userEmails;

    /**
     * @ORM\OneToOne(
     *     targetEntity="UserSocialData",
     *     mappedBy="userCredentials"
     * )
     */
    private $userSocialData;

    /**
     * @ORM\OneToMany(
     *     targetEntity="UserPwdReset",
     *     mappedBy="userCredentials"
     * )
     */
    private $userPwdResets;

    public function __construct()
    {
        $this->userEmails = new ArrayCollection();
        $this->userPwdResets = new ArrayCollection();
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function addUserEmail($userEmail)
    {
        $this->userEmails[] = $userEmail;
    }

    public function removeUserEmail($userEmail)
    {
        $this->userEmails->removeElement($userEmail);
    }

    public function getUserEmails()
    {
        return $this->userEmails;
    }

    public function getUserDefaultEmail()
    {
        foreach($this->getUserEmails() as $email)
            if($email->isDefault())
                return $email;
    }

    public function getUserSocialData()
    {
        return $this->userSocialData;
    }

    public function setUserSocialData($data)
    {
        $this->userSocialData = $data;
    }

    public function addPwdReset($pwdReset)
    {
        $this->pwdResets[] = $pwdReset;
    }

    public function removePwdReset($pwdReset)
    {
        $this->pwdResets->removeElement($pwdReset);
    }

    public function getPwdResets()
    {
        return $this->pwdResets;
    }
}
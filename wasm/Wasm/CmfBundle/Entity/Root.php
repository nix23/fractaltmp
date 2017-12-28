<?php
namespace Wasm\CmfBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Wasm\StoreBundle\EntityField\IdField;
use Wasm\StoreBundle\EntityField\CreatedAtField;
use Wasm\StoreBundle\EntityField\User\UsernameField;
use Wasm\StoreBundle\EntityField\User\PasswordField;

/**
 * Root
 *
 * @ORM\Table(name="wasm_cmf_root")
 * @ORM\Entity(repositoryClass="Wasm\CmfBundle\Repository\RootRepository")
 * @ORM\HasLifecycleCallbacks
 *
 */
class Root implements UserInterface
{
    use IdField;
    use UsernameField;
    use PasswordField;
    use CreatedAtField;

    public function getRoles()
    {
        return array('ROLE_WASM_ROOT');
    }

    public function getSalt()
    {
        // @todo -> Replace with real rand
        return 'wasmuniq337';
    }

    public function eraseCredentials()
    {
        ;
    }

    public function equals(UserInterface $root)
    {
        return $root->getUsername() == $this->getUsername();
    }

}

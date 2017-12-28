<?php
namespace Wasm\AppBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Wasm\AppBundle\Entity\App;
use Wasm\StoreBundle\EntityField;
/**
 * Application group
 *
 * @ORM\Table(
 *     name="wasm_app_group"
 * )
 * @ORM\Entity(
 *     repositoryClass="Wasm\AppBundle\Store\GroupRepo"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class Group
{
    use EntityField\IdField;
    use EntityField\NameField;
    use EntityField\DescriptionField;
    use EntityField\SortField;
    use EntityField\IsDefaultField;
    use EntityField\IsEnabledField;
    use EntityField\CreatedAtField;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App", 
     *     mappedBy="group"
     * )
     */
    private $apps;

    public function __construct()
    {
        $this->apps = new ArrayCollection();
    }

    public function addApp(App $app)
    {
        $this->apps[] = $app;
        return $this;
    }

    public function removeApp(App $app)
    {
        $this->apps->removeElement($app);
    }

    public function getApps()
    {
        return $this->apps;
    }
}

<?php
namespace Wasm\ModBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Wasm\AppBundle\Entity\Section;
use Wasm\ModBundle\Entity\ModInstance;
use Wasm\StoreBundle\EntityField;

/**
 * @ORM\Table(
 *     name="wasm_mod"
 * )
 * @ORM\Entity(
 *     repositoryClass="Wasm\ModBundle\Store\ModRepo"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class Mod
{
    use EntityField\IdField;
    use EntityField\App\VendorField;
    use EntityField\App\GroupNameField;
    use EntityField\NameField;
    use EntityField\DescriptionField;
    use EntityField\SortField;
    use EntityField\IsEnabledField;
    use EntityField\CreatedAtField;

    // @ping -> Decide if required(Not used yet)
    /*/**
     * @ORM\Column(type="boolean")
     */
    //private $isStatic = false;

    /**
     * @ORM\OneToMany(
     *     targetEntity="ModInstance", 
     *     mappedBy="module"
     * )
     */
    private $modInstances;

    public function __construct()
    {
        $this->modInstances = new ArrayCollection();
    }

    // public function setIsStatic($isStatic)
    // {
    //     $this->isStatic = $isStatic;
    // }

    // public function getIsStatic()
    // {
    //     return $this->isStatic;
    // }

    public function addModInstance(ModInstance $modInstance)
    {
        $this->modInstances[] = $modInstance;
        return $this;
    }

    public function removeModInstance(ModInstance $modInstance)
    {
        $this->modInstances->removeElement($modInstance);
    }

    public function getModInstances()
    {
        return $this->modInstances;
    }
}

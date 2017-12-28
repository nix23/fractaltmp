<?php
namespace Wasm\ModBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Wasm\AppSceneBundle\Entity\LayoutModInstance;
use Wasm\ModBundle\Entity\Mod;
use Wasm\ModBundle\Entity\Render;
use Wasm\StoreBundle\EntityField;

/**
 * @ORM\Table(
 *     name="wasm_mod_instance"
 * )
 * @ORM\Entity(
 *     repositoryClass="Wasm\ModBundle\Store\ModInstanceRepo"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class ModInstance
{
    use EntityField\IdField;
    use EntityField\DescriptionField;
    use EntityField\SortField;
    use EntityField\IsEnabledField;
    use EntityField\CreatedAtField;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modNamePrefix;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Mod", 
     *     inversedBy="modInstances"
     * )
     */
    private $mod;

    /**
     * @ORM\OneToMany(
     *     targetEntity="\Wasm\AppSceneBundle\Entity\LayoutModInstance", 
     *     mappedBy="modInstance"
     * )
     */
    private $layoutModInstances;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Render", 
     *     mappedBy="modInstance"
     * )
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $renders;

    public function __construct()
    {
        $this->layoutModInstances = new ArrayCollection();
        $this->renders = new ArrayCollection();
    }

    public function setModNamePrefix($modNamePrefix)
    {
        $this->modNamePrefix = $modNamePrefix;
        return $this;
    }

    public function getModNamePrefix()
    {
        return $this->modNamePrefix;
    }

    public function getFullName()
    {
        return implode("", $this->getFullNameArray());
    }

    public function getFullNameArray()
    {
        return array(
            $this->getModNamePrefix(),
            $this->getMod()->getName(),
        );
    }

    public function setMod(Mod $mod = null)
    {
        $this->mod = $mod;
        return $this;
    }

    public function getMod()
    {
        return $this->mod;
    }

    public function addLayoutModInstance(LayoutModInstance $instance)
    {
        $this->layoutModInstances[] = $instance;
    }

    public function removeLayoutModuleInstance(LayoutModInstance $instance)
    {
        $this->layoutModInstances->removeElement($instance);
    }

    public function getLayoutModuleInstances()
    {
        return $this->layoutModuleInstances;
    }

    public function addRender(Render $render)
    {
        $this->renders[] = $render;
    }

    public function removeRender(Render $render)
    {
        $this->renders->removeElement($render);
    }

    public function getRenders()
    {
        return $this->renders;
    }
}

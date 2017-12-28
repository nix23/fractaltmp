<?php
namespace Wasm\AppSceneBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Wasm\AppSceneBundle\Entity\Layout;
use Wasm\AppSceneBundle\Entity\LayoutModInstance;
use Wasm\StoreBundle\EntityField;

/**
 * @ORM\Table(
 *     name="wasm_layout_item"
 * )
 * @ORM\Entity(
 *     repositoryClass="Wasm\AppSceneBundle\Store\LayoutItemRepo"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class LayoutItem
{
    use EntityField\IdField;
    use EntityField\NameField;
    use EntityField\DescriptionField;
    use EntityField\SortField;
    use EntityField\IsEnabledField;
    use EntityField\CreatedAtField;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Layout", 
     *     inversedBy="layoutItems"
     * )
     */
    private $layout;

    /**
     * @ORM\OneToMany(
     *     targetEntity="LayoutModInstance",
     *     mappedBy="layoutItem"
     * )
     */
    private $layoutModInstances;

    public function __construct()
    {
        $this->layoutModInstances = new ArrayCollection();
    }

    public function setLayout(Layout $layout)
    {
        $this->layout = $layout;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function addLayoutModInstance(LayoutModInstance $instance)
    {
        $this->layoutModInstances[] = $instance;
    }

    public function removeLayoutModInstance(LayoutModInstance $instance)
    {
        $this->layoutModInstances->removeElement($instance);
    }

    public function getLayoutModInstances()
    {
        return $this->layoutModInstances;
    }
}
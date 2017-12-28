<?php
namespace Wasm\AppSceneBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Wasm\AppSceneBundle\Entity\SceneLayout;
use Wasm\AppSceneBundle\Entity\LayoutItem;
use Wasm\ModBundle\Entity\ModInstance;
use Wasm\StoreBundle\EntityField;

/**
 * @ORM\Table(
 *     name="wasm_layout_mod_instance"
 * )
 * @ORM\Entity(
 *     repositoryClass="Wasm\AppSceneBundle\Store\LayoutModInstanceRepo"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class LayoutModInstance
{
    use EntityField\IdField;
    use EntityField\NameField;
    use EntityField\DescriptionField;
    use EntityField\SortField;
    use EntityField\IsEnabledField;
    use EntityField\CreatedAtField;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="SceneLayout", 
     *     inversedBy="layoutModInstances"
     * )
     */
    private $sceneLayout;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="LayoutItem",
     *     inversedBy="layoutModInstances"
     * )
     */
    private $layoutItem;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="\Wasm\ModBundle\Entity\ModInstance",
     *     inversedBy="layoutModInstances"
     * )
     */
    private $modInstance;

    public function setSceneLayout(SceneLayout $sceneLayout)
    {
        $this->sceneLayout = $sceneLayout;
    }

    public function getSceneLayout()
    {
        return $this->sceneLayout;
    }

    public function setLayoutItem(LayoutItem $item)
    {
        $this->layoutItem = $item;
    }

    public function getLayoutItem()
    {
        return $this->layoutItem;
    }

    public function setModInstance(ModInstance $modInstance)
    {
        $this->modInstance = $modInstance;
    }

    public function getModInstance()
    {
        return $this->modInstance;
    }
}

<?php
namespace Wasm\AppSceneBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Wasm\AppSceneBundle\Entity\SceneLayout;
use Wasm\AppSceneBundle\Entity\LayoutItem;
use Wasm\StoreBundle\EntityField;

/**
 * @ORM\Table(
 *     name="wasm_layout"
 * )
 * @ORM\Entity(
 *     repositoryClass="Wasm\AppSceneBundle\Store\LayoutRepo"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class Layout
{
    const LAYOUT_TYPE_APP = 0;
    const LAYOUT_TYPE_WEB = 1;

    use EntityField\IdField;
    use EntityField\NameField;
    use EntityField\DescriptionField;
    use EntityField\SortField;
    use EntityField\IsEnabledField;
    use EntityField\CreatedAtField;

    /**
     * @ORM\Column(type="integer")
     */
    private $layoutType;

    /**
     * @ORM\OneToMany(
     *     targetEntity="SceneLayout", 
     *     mappedBy="layout"
     * )
     */
    private $sceneLayouts;

    /**
     * @ORM\OneToMany(
     *     targetEntity="LayoutItem",
     *     mappedBy="layout"
     * )
     */
    private $layoutItems;

    public function __construct()
    {
        $this->sceneLayouts = new ArrayCollection();
        $this->layoutItems = new ArrayCollection();
    }

    public function getFullName()
    {
        return implode("", $this->getFullNameArray());
    }

    public function getFullNameArray()
    {
        // @todo -> Finish this(Add group/subgroup like in MOD)
        //       -> Right now struct is 'undefined'
        return array(
            "BySize",
            "Grid",
            $this->getName(),
        );
    }

    public function setLayoutType($layoutType)
    {
        $this->layoutType = $layoutType;
    }

    public function getLayoutType()
    {
        return $this->layoutType;
    }

    public function getLayoutTypeLabels()
    {
        return array(
            "Web",
            "App",
        );
    }

    public function getLayoutTypeVals()
    {
        return array(
            self::LAYOUT_TYPE_WEB,
            self::LAYOUT_TYPE_APP,
        );
    }

    public function isAppLayout()
    {
        return $this->getLayoutType() == self::LAYOUT_TYPE_APP;
    }

    public function isWebLayout()
    {
        return $this->getLayoutType() == self::LAYOUT_TYPE_WEB;
    }

    public function addSceneLayout(SceneLayout $sceneLayout)
    {
        $this->sceneLayouts[] = $sceneLayout;
    }

    public function removeSceneLayout(SceneLayout $sceneLayout)
    {
        $this->sceneLayouts->removeElement($sceneLayout);
    }

    public function getSceneLayouts()
    {
        return $this->sceneLayouts;
    }

    public function addLayoutItem(LayoutItem $item)
    {
        $this->layoutItems[] = $item;
    }

    public function removeLayoutItem(LayoutItem $item)
    {
        $this->layoutItems->removeElement($item);
    }

    public function getLayoutItems()
    {
        return $this->layoutItems;
    }
}

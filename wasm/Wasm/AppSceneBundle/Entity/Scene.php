<?php
namespace Wasm\AppSceneBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Wasm\AppBundle\Entity\Package;
use Wasm\AppSceneBundle\Entity\SceneLayout;
use Wasm\AppSceneBundle\Entity\LayoutModInstance;
use Wasm\ModBundle\Entity\ModInstance;
use Wasm\StoreBundle\EntityField;

/**
 * @ORM\Table(
 *     name="wasm_scene"
 * )
 * @ORM\Entity(
 *     repositoryClass="Wasm\AppSceneBundle\Store\SceneRepo"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class Scene
{
    const SCENE_TYPE_APP = 0;
    const SCENE_TYPE_WEB = 1;

    use EntityField\IdField;
    use EntityField\NameField;
    use EntityField\DescriptionField;
    use EntityField\SortField;
    use EntityField\IsEnabledField;
    use EntityField\CreatedAtField;

    /**
     * @ORM\Column(type="integer")
     */
    private $sceneType;

    /**
     * @ORM\Column(
     *     type="boolean"
     * )
     */
    private $isBootstrapScene = false;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="\Wasm\AppBundle\Entity\Package", 
     *     inversedBy="scenes"
     * )
     */
    private $package;

    /**
     * @ORM\OneToMany(
     *     targetEntity="SceneLayout",
     *     mappedBy="scene"
     * )
     */
    private $sceneLayouts;

    /**
     * @ORM\OneToOne(
     *     targetEntity="\Wasm\ModBundle\Entity\ModInstance"
     * )
     */
    private $modInstance;

    public function __construct()
    {
        $this->sceneLayouts = new ArrayCollection();
    }

    public function setSceneType($sceneType)
    {
        $this->sceneType = $sceneType;
    }

    public function getSceneType()
    {
        return $this->sceneType;
    }

    public function getSceneTypeLabels()
    {
        return array(
            "Web",
            "App",
        );
    }

    public function getSceneTypeVals()
    {
        return array(
            self::SCENE_TYPE_WEB,
            self::SCENE_TYPE_APP,
        );
    }

    public function isAppScene()
    {
        return $this->getSceneType() == self::SCENE_TYPE_APP;
    }

    public function isWebScene()
    {
        return $this->getSceneType() == self::SCENE_TYPE_WEB;
    }

    public function setIsBootstrapScene($isBootstrapScene)
    {
        $this->isBootstrapScene = $isBootstrapScene;
    }

    public function getIsBootstrapScene()
    {
        return $this->isBootstrapScene;
    }

    public function setPackage($package)
    {
        $this->package = $package;
    }

    public function getPackage()
    {
        return $this->package;
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

    public function setModInstance(ModInstance $modInstance)
    {
        $this->modInstance = $modInstance;
    }

    public function getModInstance()
    {
        return $this->modInstance;
    }
}

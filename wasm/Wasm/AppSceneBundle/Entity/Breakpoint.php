<?php
namespace Wasm\AppSceneBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Wasm\AppSceneBundle\Entity\SceneLayout;
use Wasm\StoreBundle\EntityField;

/**
 * @ORM\Table(
 *     name="wasm_breakpoint"
 * )
 * @ORM\Entity(
 *     repositoryClass="Wasm\AppSceneBundle\Store\BreakpointRepo"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class Breakpoint
{
    use EntityField\IdField;
    use EntityField\NameField;
    use EntityField\DescriptionField;
    use EntityField\SortField;
    use EntityField\IsEnabledField;
    use EntityField\CreatedAtField;

    /**
     * @ORM\OneToMany(
     *     targetEntity="SceneLayout", 
     *     mappedBy="breakpoint"
     * )
     */
    private $sceneLayouts;

    public function __construct()
    {
        $this->sceneLayouts = new ArrayCollection();
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
}
<?php
namespace Wasm\AppSceneBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Wasm\AppSceneBundle\Entity\Breakpoint;
use Wasm\AppSceneBundle\Entity\Scene;
use Wasm\AppSceneBundle\Entity\Layout;
use Wasm\AppSceneBundle\Entity\LayoutModInstance;
use Wasm\StoreBundle\EntityField;
use Wasm\UtilBundle\Util\Str;

/**
 * @ORM\Table(
 *     name="wasm_scene_layout"
 * )
 * @ORM\Entity(
 *     repositoryClass="Wasm\AppSceneBundle\Store\SceneLayoutRepo"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class SceneLayout
{
    const BREAKPOINT_TYPE_IS_MIN = 0;
    const BREAKPOINT_TYPE_IS_MAX = 1;
    const BREAKPOINT_TYPE_IS_ONLY = 2;
    const BREAKPOINT_TYPE_IS_BETWEEN = 3;

    use EntityField\IdField;
    use EntityField\NameField;
    use EntityField\DescriptionField;
    use EntityField\SortField;
    use EntityField\IsEnabledField;
    use EntityField\CreatedAtField;

    /**
     * @ORM\Column(type="integer")
     */
    private $breakpointType;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Scene", 
     *     inversedBy="sceneLayouts"
     * )
     */
    private $scene;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Layout", 
     *     inversedBy="sceneLayouts"
     * )
     */
    private $layout;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Breakpoint", 
     *     inversedBy="sceneLayouts"
     * )
     */
    private $breakpoint;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Breakpoint"
     * )
     */
    private $secondBreakpoint;

    /**
     * @ORM\OneToMany(
     *     targetEntity="LayoutModInstance",
     *     mappedBy="scene"
     * )
     */
    private $layoutModInstances;

    public function setBreakpointType($breakpointType)
    {
        $this->breakpointType = $breakpointType;
    }

    public function getBreakpointType()
    {
        return $this->breakpointType;
    }

    public function getBreakpointTypeLabels()
    {
        return array(
            "Is min",
            "Is max",
            "Is only",
            "Is between",
        );
    }

    public function getBreakpointTypeVals()
    {
        return array(
            self::BREAKPOINT_TYPE_IS_MIN,
            self::BREAKPOINT_TYPE_IS_MAX,
            self::BREAKPOINT_TYPE_IS_ONLY,
            self::BREAKPOINT_TYPE_IS_BETWEEN,
        );
    }

    public function getBreakpointTypeLabelForSourceCode()
    {
        $label = Str::spacesToNextUpper(
            $this->getBreakpointTypeLabels()[
                $this->getBreakpointType()
            ]
        );
        $label .= Str::ucfirst($this->breakpoint->getName());

        if($this->isBreakpointTypeBetween())
            $label .= "And" . Str::ucfirst($this->secondBreakpoint->getName());

        return $label;
    }

    public function isBreakpointTypeMin()
    {
        return $this->getBreakpointType() == self::BREAKPOINT_TYPE_IS_MIN;
    }

    public function isBreakpointTypeMax()
    {
        return $this->getBreakpointType() == self::BREAKPOINT_TYPE_IS_MAX;
    }

    public function isBreakpointTypeOnly()
    {
        return $this->getBreakpointType() == self::BREAKPOINT_TYPE_IS_ONLY;
    }

    public function isBreakpointTypeBetween()
    {
        return $this->getBreakpointType() == self::BREAKPOINT_TYPE_IS_BETWEEN;
    }

    public function setScene(Scene $scene)
    {
        $this->scene = $scene;
    }

    public function getScene()
    {
        return $this->scene;
    }

    public function setLayout(Layout $layout)
    {
        $this->layout = $layout;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function setBreakpoint(Breakpoint $breakpoint)
    {
        $this->breakpoint = $breakpoint;
    }

    public function getBreakpoint()
    {
        return $this->breakpoint;
    }

    public function setSecondBreakpoint(Breakpoint $breakpoint)
    {
        $this->secondBreakpoint = $breakpoint;
    }

    public function getSecondBreakpoint()
    {
        return $this->secondBreakpoint;
    }

    public function addLayoutModInstance(LayoutModInstance $instance)
    {
        $this->layoutModInstances[] = $instance;
    }

    public function removeLayoutModuleInstance(LayoutModInstance $instance)
    {
        $this->layoutModInstances->removeElement($instance);
    }

    public function getLayoutModInstances()
    {
        return $this->layoutModInstances;
    }
}
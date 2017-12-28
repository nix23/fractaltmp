<?php
namespace Wasm\AppBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Wasm\AppBundle\Entity\Section;
use Wasm\AppSceneBundle\Entity\Scene;
use Wasm\StoreBundle\EntityField;

/**
 * @ORM\Table(
 *     name="wasm_app_package"
 * )
 * @ORM\Entity(
 *     repositoryClass="Wasm\AppBundle\Store\PackageRepo"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class Package
{
    // @todo -> Refactor to 'BACKEND_NAME_POSTFIX'?
    const NAME_POSTFIX = "Bundle";
    const PACKAGE_TYPE_APP_AND_CMSAPP = 0;
    const PACKAGE_TYPE_ONLY_APP = 1;
    const PACKAGE_TYPE_ONLY_CMSAPP = 2;

    use EntityField\IdField;
    use EntityField\NameField;
    use EntityField\DescriptionField;
    use EntityField\SortField;
    use EntityField\IsEnabledField;
    use EntityField\CreatedAtField;

    /**
     * @ORM\Column(type="integer")
     */
    private $packageType = self::PACKAGE_TYPE_APP_AND_CMSAPP;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Section", 
     *     inversedBy="packages"
     * )
     */
    private $section;

    /**
     * @ORM\OneToMany(
     *     targetEntity="\Wasm\AppSceneBundle\Entity\Scene", 
     *     mappedBy="package"
     * )
     */
    private $scenes;

    public function __construct()
    {
        $this->scenes = new ArrayCollection();
    }

    public function setPackageType($packageType)
    {
        $this->packageType = $packageType;
        return $this;
    }

    public function getPackageType()
    {
        return $this->packageType;
    }

    public function getPackageTypeLabels()
    {
        return array(
            "App and Cms",
            "Only App",
            "Only Cms",
        );
    }

    public function getPackageTypeVals()
    {
        return array(
            self::PACKAGE_TYPE_APP_AND_CMSAPP,
            self::PACKAGE_TYPE_ONLY_APP,
            self::PACKAGE_TYPE_ONLY_CMSAPP,
        );
    }

    public function isPackageTypeAppAndCmsApp()
    {
        return $this->getPackageType() == self::PACKAGE_TYPE_APP_AND_CMSAPP;
    }

    public function isPackageTypeOnlyApp()
    {
        return $this->getPackageType() == self::PACKAGE_TYPE_ONLY_APP;
    }

    public function isPackageTypeOnlyCmsApp()
    {
        return $this->getPackageType() == self::PACKAGE_TYPE_ONLY_CMSAPP;
    }

    public function getFullName()
    {
        return implode("", $this->getFullNameArray());
    }

    public function getFullNameArray()
    {
        return array($this->getName(), self::NAME_POSTFIX);
    }

    public function getFullNameByAppGroupArray()
    {
        return array(
            $this->getSection()->getApp()->getGroup()->getName(),
            $this->getSection()->getApp()->getNamespace(),
            $this->getSection()->getName(),
            $this->getName(),
        );
    }

    public function setSection(Section $section = null)
    {
        $this->section = $section;
        return $this;
    }

    public function getSection()
    {
        return $this->section;
    }

    public function addScene(Scene $scene)
    {
        $this->scenes[] = $scene;
    }

    public function removeScene(Scene $scene)
    {
        $this->scenes->removeElement($scene);
    }

    public function getScenes()
    {
        return $this->scenes;
    }

    public function getAppScenes()
    {
        $scenes = array();
        foreach($this->getScenes() as $scene)
            if($scene->isAppScene())
                $scenes[] = $scene;

        return $scenes;
    }

    public function getWebScenes()
    {
        $scenes = array();
        foreach($this->getScenes() as $scene)
            if($scene->isWebScene())
                $scenes[] = $scene;

        return $scenes;
    }
}

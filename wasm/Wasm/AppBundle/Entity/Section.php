<?php
namespace Wasm\AppBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Wasm\AppBundle\Entity\App;
use Wasm\AppBundle\Entity\Package;
use Wasm\StoreBundle\EntityField;

/**
 * Application Section
 *
 * @ORM\Table(
 *     name="wasm_app_section"
 * )
 * @ORM\Entity(
 *     repositoryClass="Wasm\AppBundle\Store\SectionRepo"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class Section
{
    const SECTION_TYPE_APP_AND_CMSAPP = 0;
    const SECTION_TYPE_ONLY_APP = 1;
    const SECTION_TYPE_ONLY_CMSAPP = 2;

    use EntityField\IdField;
    use EntityField\NameField;
    use EntityField\DescriptionField;
    use EntityField\SortField;
    use EntityField\IsEnabledField;
    use EntityField\CreatedAtField;

    /**
     * @ORM\Column(type="integer")
     */
    private $sectionType = self::SECTION_TYPE_APP_AND_CMSAPP;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="App", 
     *     inversedBy="sections"
     * )
     */
    private $app;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Package", 
     *     mappedBy="section"
     * )
     */
    private $packages;

    public function __construct()
    {
        $this->packages = new ArrayCollection();
    }

    public function setSectionType($sectionType)
    {
        $this->sectionType = $sectionType;
        return $this;
    }

    public function getSectionType()
    {
        return $this->sectionType;
    }

    public function getSectionTypeLabels()
    {
        return array(
            "App and Cms",
            "Only App",
            "Only Cms",
        );
    }

    public function getSectionTypeVals()
    {
        return array(
            self::SECTION_TYPE_APP_AND_CMSAPP,
            self::SECTION_TYPE_ONLY_APP,
            self::SECTION_TYPE_ONLY_CMSAPP,
        );
    }

    public function isSectionTypeAppAndCmsApp()
    {
        return $this->getSectionType() == self::SECTION_TYPE_APP_AND_CMSAPP;
    }

    public function isSectionTypeOnlyApp()
    {
        return $this->getSectionType() == self::SECTION_TYPE_ONLY_APP;
    }

    public function isSectionTypeOnlyCmsApp()
    {
        return $this->getSectionType() == self::SECTION_TYPE_ONLY_CMSAPP;
    }

    public function setApp(App $app = null)
    {
        $this->app = $app;
        return $this;
    }

    public function getApp()
    {
        return $this->app;
    }

    public function addPackage(Package $package)
    {
        $this->packages[] = $package;
        return $this;
    }

    public function removePackage(Package $package)
    {
        $this->packages->removeElement($package);
    }

    public function getPackages()
    {
        return $this->packages;
    }
}

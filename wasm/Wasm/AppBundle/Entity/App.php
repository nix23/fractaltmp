<?php
namespace Wasm\AppBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Wasm\AppBundle\Entity\Section;
use Wasm\StoreBundle\EntityField;
use Wasm\ModBundle\Entity\ModInstance;

// @todo -> bind domain/domains to each App
/**
 * Application
 *
 * @ORM\Table(
 *     name="wasm_app"
 * )
 * @ORM\Entity(
 *     repositoryClass="Wasm\AppBundle\Store\AppRepo"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class App
{
    use EntityField\IdField;
    use EntityField\NameField;
    use EntityField\DescriptionField;
    use EntityField\SortField;
    use EntityField\IsEnabledField;
    use EntityField\CreatedAtField;
    use EntityField\App\NamespaceField;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Group",
     *     inversedBy="apps"
     * )
     */
    private $group;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Section", 
     *     mappedBy="app"
     * )
     */
    private $sections;

    /**
     * @ORM\OneToOne(
     *     targetEntity="\Wasm\ModBundle\Entity\ModInstance"
     * )
     */
    private $modInstance;


    public function __construct()
    {
        $this->sections = new ArrayCollection();
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function setGroup($group)
    {
        $this->group = $group;
    }

    public function addSection(Section $section)
    {
        $this->sections[] = $section;
        return $this;
    }

    public function removeSection(Section $section)
    {
        $this->sections->removeElement($section);
    }

    public function getSections()
    {
        return $this->sections;
    }

    public function getCmsSections()
    {
        $cmsSections = array();
        $sections = $this->getSections();
        foreach($sections as $section) {
            if($section->isSectionTypeOnlyCmsApp())
                $cmsSections[] = $section;
        }

        return $cmsSections;
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

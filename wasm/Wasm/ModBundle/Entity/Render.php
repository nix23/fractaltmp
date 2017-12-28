<?php
namespace Wasm\ModBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Wasm\ModBundle\Entity\ModInstance;
use Wasm\StoreBundle\EntityField;

/**
 * @ORM\Table(
 *     name="wasm_mod_instance_render"
 * )
 * @ORM\Entity(
 *     repositoryClass="Wasm\ModBundle\Store\RenderRepo"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class Render
{
    use EntityField\IdField;
    //use EntityField\DescriptionField;
    use EntityField\SortField;
    //use EntityField\IsEnabledField;
    use EntityField\CreatedAtField;

    // @todo -> Serialize to json!
    /**
     * @ORM\Column(type="blob")
     */
    private $params = "";

    /**
     * @ORM\ManyToOne(
     *     targetEntity="\Wasm\ModBundle\Entity\ModInstance", 
     *     inversedBy="modInstanceParams"
     * )
     */
    private $modInstance;

    public function setParams($params)
    {
        $this->params = serialize($params);
    }

    public function getParams()
    {
        return unserialize($this->params);
    }

    public function setModInstance(ModInstance $modInstance = null)
    {
        $this->modInstance = $modInstance;
        return $this;
    }

    public function getModInstance()
    {
        return $this->modInstance;
    }
}

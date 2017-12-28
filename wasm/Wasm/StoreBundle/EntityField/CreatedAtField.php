<?php
namespace Wasm\StoreBundle\EntityField;

use Doctrine\ORM\Mapping AS ORM;

trait CreatedAtField
{
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtBeforePersist()
    {
        $this->setCreatedAt(new \DateTime());
    }
}
<?php
namespace Wasm\StoreBundle\EntityField;

use Doctrine\ORM\Mapping AS ORM;

trait SortField
{
    /**
     * @ORM\Column(type="integer")
     */
    private $sort = 0;

    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    public function getSort()
    {
        return $this->sort;
    }
}

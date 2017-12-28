<?php
namespace Wasm\StoreBundle\Store;

use Doctrine\ORM\EntityRepository;

class Repo extends EntityRepository
{
    protected function q($q)
    {
        return $this->getEntityManager()->createQuery($q);
    }

    protected function row($q)
    {
        return $q->getSingleResult();
    }

    protected function rows($q)
    {
        return $q->getResult();
    }

    protected function max($q)
    {
        $res = $q->getSingleScalarResult();
        return ($res) ? (int)$res : 0;
    }
}
<?php
namespace Wasm\StoreBundle\Manager;

use Doctrine\ORM\EntityManager as DoctrineEntityManager;

class Em
{
    private $em;

    public function __construct(DoctrineEntityManager $em)
    {
        $this->em = $em;
    }

    public function getEm()
    {
        return $this->em;
    }

    public function persist($entity)
    {
        $this->em->persist($entity);
    }

    public function flush()
    {
        $this->em->flush();
    }
}
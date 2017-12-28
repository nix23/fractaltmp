<?php
namespace Wasm\AppSceneBundle\Cmd;

use Wasm\AppSceneBundle\Entity\Breakpoint;

class CreateBreakpointsCmd
{
    private $em;
    private $breakpointsRepo;

    public function __construct(
        $em
    ) {
        $this->em = $em;
        $this->breakpointsRepo = $em->getEm()->getRepository("WasmAppSceneBundle:Breakpoint");
    }

    public function exec()
    {
        $breakpoints = array("xs", "sm", "md", "lg", "xl");
        $i = -1;
        foreach($breakpoints as $breakpointName) {
            $breakpoint = new Breakpoint();
            $breakpoint->setName($breakpointName);
            $breakpoint->setSort(++$i);

            $this->em->persist($breakpoint);
        }

        $this->em->flush();
    }
}
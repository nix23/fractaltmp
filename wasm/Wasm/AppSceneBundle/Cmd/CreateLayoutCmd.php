<?php
namespace Wasm\AppSceneBundle\Cmd;

use Wasm\AppSceneBundle\Entity\Layout;
use Wasm\AppSceneBundle\Entity\LayoutItem;

class CreateLayoutCmd
{
    private $em;

    public function __construct(
        $em
    ) {
        $this->em = $em;
    }

    public function exec()
    {
        $layout = new Layout();
        $layout->setName("1Col2Rows");
        $layout->setLayoutType(Layout::LAYOUT_TYPE_WEB);
        $this->em->persist($layout);

        $layoutItem = new LayoutItem();
        $layoutItem->setName("ModRow1");
        $layoutItem->setLayout($layout);
        $layout->addLayoutItem($layoutItem);
        $this->em->persist($layoutItem);

        $layoutItem = new LayoutItem();
        $layoutItem->setName("ModRow2");
        $layoutItem->setLayout($layout);
        $layout->addLayoutItem($layoutItem);
        $this->em->persist($layoutItem);

        $this->em->persist($layout);
        $this->em->flush();
    }
}
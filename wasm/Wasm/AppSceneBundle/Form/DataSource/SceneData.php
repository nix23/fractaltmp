<?php
namespace Wasm\AppSceneBundle\Form\DataSource;

use Wasm\FormBundle\Util\DataSource;
use Wasm\AppSceneBundle\Entity\Scene;

class SceneData
{
    public function getSceneTypeData()
    {
        return DataSource::createItems(
            Scene::getSceneTypeLabels(),
            Scene::getSceneTypeVals()
        );
    }
}
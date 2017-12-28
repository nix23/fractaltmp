<?php
namespace Wasm\ModBundle\Store;

use Wasm\ModBundle\Store\Render\DebugRenderStore;

class RenderStore
{
    public function __construct($em)
    {

    }

    public function getRenderParams($package, $modInstance, $debug = false)
    {
        DebugRenderStore::setDebug($debug);
        DebugRenderStore::renderStart($package, $modInstance);

        $params = array();
        foreach($modInstance->getRenders() as $render) {
            $params = $this->mergeParams($params, $render->getParams());
            //$params = array_merge_recursive($params, $nextParams);
        }

        DebugRenderStore::renderEnd($package, $modInstance);

        return $params;
    }

    private function mergeParams(
        $params = array(), 
        $nextParams = array(),
        $deepness = 0
    ) {
        DebugRenderStore::enterMergeParams($params, $nextParams, $deepness);
        if(is_array($nextParams) && count($nextParams) == 0)
            return array();

        foreach($nextParams as $nextParamKey => $nextParamVal) {
            if(is_array($nextParamVal)) {
                $nextResultVal = $this->mergeParams(
                    array_key_exists($nextParamKey, $params) ? $params[$nextParamKey] : array(), 
                    $nextParams[$nextParamKey],
                    $deepness + 1
                );

                $isAssocParams = (
                    is_array($params) &&
                    count(array_filter(array_keys($params), 'is_string')) > 0
                );
                $isAssocNextParams = (
                    is_array($nextParams) &&
                    count(array_filter(array_keys($nextParams), 'is_string')) > 0
                );

                if(!$isAssocParams && !$isAssocNextParams) {
                    $this->pushResultInNumericArray(
                        $params,
                        $nextResultVal
                    );
                    continue;
                }

                $params[$nextParamKey] = $nextResultVal;
                continue;
            }
            
            $params[$nextParamKey] = $nextParamVal;
        }

        DebugRenderStore::exitMergeParams($params, $nextParams, $deepness);
        return $params;
    }

    private function pushResultInNumericArray(&$params, $result)
    {
        DebugRenderStore::pushNumericRes($params, $result);
        $resultTmp = $result;
        $result = array();
        $result[] = $resultTmp;
        //$result = array_merge(array(), $result); cli($result);
        // $isAssocResultVal = (
        //    is_array($result) && 
        //    count(array_filter(array_keys($result), 'is_string')) > 0
        // );
        // if($isAssocResultVal) {
        //     $params[] = $result;
        //     return $params;
        // }

        foreach($result as $entry) {
            $paramId = null;
            for($i = 0; $i < count($params); $i++) {
                // Numeric array value can be a scalar
                if(!is_array($entry) || !is_array($params))
                    continue;

                if(!array_key_exists("id", $entry) ||
                   !array_key_exists("id", $params[$i]))
                    continue;

                if($entry["id"] == $params[$i]["id"]) {
                    $paramId = $i;
                    break;
                }
            }

            if($paramId !== null)
                $params[$paramId] = array_merge($params[$paramId], $entry);
            else
                $params[] = $entry;
        }

        return $params;
    }
}
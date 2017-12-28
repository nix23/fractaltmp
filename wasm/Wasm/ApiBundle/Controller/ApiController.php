<?php
namespace Wasm\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/*
    @todo -> Create class to create Api response
    + (add created_at to response ?)
*/
abstract class ApiController extends Controller
{
    protected function cursor()
    {
        return $this->get("Wasm.Api.DataPager.Cursor");
    }

    protected function pageSize()
    {
        return $this
            ->get("Wasm.Api.DataPager.DataPager")
            ->pageSize();
    }

    protected function serialize($data, $groups = null)
    {
        return $this
            ->get("Wasm.Api.Factory.ApiResponseFactory")
            ->serialize($data, $groups);
    }

    // @todo -> Limit fields(Only fieldA,B)
    //       -> Add model transformers? (Entity to json)
    //       -> extends BaseTransformer, by def getId, ...
    //       -> Trim
    // @todo -> @Groups({"EntityName_Default", "LinkedEntity_Default"})
    protected function response(
        $data = array(), 
        $groups = null, 
        $statusCode = 200
    ) {
        return $this
            ->get("Wasm.Api.Factory.ApiResponseFactory")
            ->createResponse($data, $groups, $statusCode);
    }

    // @todo -> tmp -> Exceptions will be thrown in commands/repos
    // @todo -> Move is_array check to factory?
    protected function throwValidationException($errors)
    {
        return $this
            ->get("Wasm.Api.Factory.ApiResponseFactory")
            ->throwValidationException($errors);
    }
}
<?php
namespace <%PKGCLASS%>\Controller;

// @todo -> Replace all this includes to USEDEFCTRLCLASSES
use Wasm\ApiBundle\Controller\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("<%ROUTE%>")
 */
class <%MODNAME%>Controller extends ApiController
{
    /**
     * @Route
     * @Method("GET")
     */
    public function getAction(Request $request)
    {
        $items = $this
            ->get("<%PKGNAME%>.Store.<%MODNAME%>Store")
            ->getItems($request);

        return $this->response();
    }
}
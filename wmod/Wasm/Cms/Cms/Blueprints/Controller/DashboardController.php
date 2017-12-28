<?php
namespace <%PKGCLASS%>\Controller;

use Wasm\ApiBundle\Controller\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("<%ROUTE%>")
 */
class DashboardController extends ApiController
{
    /**
     * @Route
     * @Method("GET")
     */
    public function getAction(Request $request)
    {
        $items = $this
            ->get("<%PKGNAME%>.Store.DashboardStore")
            ->getItems($request);

        return $this->response();
    }
}
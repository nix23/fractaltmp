<?php
namespace Wasm\BootBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Wasm\ApiBundle\Controller\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

// @todo -> Is BootBundle inside each app required???
// (Or just use this with correct parsed Domain name(or ip-url))
/**
 * @Route("/")
 */
class BootController extends ApiController
{
    /**
     * @Route("/test")
     * @Method("GET")
     */
    public function getAction(Request $request)
    {
        $finder = new \Symfony\Component\Finder\Finder();
        $bootHtml = $finder->files()->in("/wweb/")->name('index.html')[0];

        // return $this->response(
        //     array("item" => $account),
        //     array("entity", "tokendata", "rootdata", "accountdata")
        // );
    }
}
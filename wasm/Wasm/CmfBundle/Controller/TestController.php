<?php
namespace Wasm\CmfBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Wasm\ApiBundle\Controller\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/test/test")
 */
class TestController extends ApiController
{
    /**
     * @Route("/test")
     * @Method("POST")
     */
    public function getAction(Request $request)
    {
        // $socialId = $request->request->get("socialId");
        // $repo = $this->repo("Account");

        // $account = $repo->findByFacebookId($socialId);
        // if(!$account)
        //     $this->throwValidationException(
        //         "Auth error: Selected facebook account is not registred."
        //     );

        // // @todo -> Check if registred but not activated???

        // $createAccessToken = $this->get("ntech.auth.command.createAccessToken");
        // $createAccessToken->handle($account);

        // return $this->response(
        //     array("item" => $account),
        //     array("entity", "tokendata", "rootdata", "accountdata")
        // );
    }
}
<?php
namespace Wasm\FormBundle\Controller;

use Wasm\ApiBundle\Controller\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/forms")
 */
class FormController extends ApiController
{
    /**
     * @Route
     * @Method("GET")
     */
    public function getAction(Request $request)
    {
        $forms = $request->query->get("forms");

        $formFields = $this
            ->get("Wasm.ApiForm.Repo.FormRepo")
            ->getFormFieldsWithStates($forms);

        return $this->response(array(
            "forms" => $formFields,
        ));
    }

    /**
     * @Route("/fields/data")
     * @Method("GET")
     */
    public function getFieldsData(Request $request)
    {
        $data = $this
            ->get("Wasm.ApiForm.Repo.FormRepo")
            ->getFieldsData($request->query->get("fields"));

        return $this->response(array(
            "data" => $data,
        ));
    }

    /**
     * @Route("/validate")
     * @Method("POST")
     */
    public function validateAction(Request $request)
    {
        $forms = $request->request->get("forms");

        $this
            ->get("Wasm.ApiForm.Cmd.ValidateFormCmd")
            ->exec($forms);

        return $this->response();
    }

    // @todo -> Put on Updates??? (Return correct status codes!!!)
    // @todo -> if(201 == $statusCode) -> $res->headers->set('Location',
    //                                  $router->genUrl('route', id), true // absolute)
    // Restrict Updates in posts... Updates in PUT + Enforce Security
    /**
     * @Route
     * @Method("POST")
     */
    public function postAction(Request $request)
    {
        $forms = $request->request->get("forms");
        $storeId = $request->request->get("storeId");
        
        $storeData = $this
            ->get("Wasm.ApiForm.Model.StoreData");
        $this
            ->get("Wasm.ApiForm.Cmd.PersistFormCmd")
            ->exec($forms, $storeId, $storeData);

        return $this->response(
            array("item" => $storeData->entity), 
            $storeData->entityGroups,
            $storeData->statusCode
        );
    }

    /**
     * @Route("/submitformdemo")
     * @Method("POST")
     */
    public function postSubmitAction(Request $request)
    {
        $this
            ->get("Wasm.App.Cmd.CreateGroupCmd")
            ->execDefaultGroup();
        $this
            ->get("Wasm.App.Cmd.CreateAppCmd")
            ->execDemoApp();

        return $this->response();
    }
}
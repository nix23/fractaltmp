<?php
namespace Wasm\FormBundle\Cmd;

use Wasm\FormBundle\Util\FormsData;

class TransformFormCmd
{
    private $request;
    private $requestTransformer;
    private $stateTransformer;
    private $fieldsReader;

    public function __construct(
        $requestStack,
        $requestTransformer,
        $stateTransformer,
        $fieldsReader
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->requestTransformer = $requestTransformer;
        $this->stateTransformer = $stateTransformer;
        $this->fieldsReader = $fieldsReader;
    }

    public function exec()
    {
        $forms = $this->request->request->get("forms");
        $formsData = $this->requestTransformer->transformToFormsData($forms);
        $this->fieldsReader->findFieldsToStore($formsData);
        $this->stateTransformer->transformStateToForms($formsData);

        return $formsData;
    }

    public function execById($formId)
    {
        $formsData = $this->exec();
        return FormsData::getFormDataByFormId($formId, $formsData);
    }
}
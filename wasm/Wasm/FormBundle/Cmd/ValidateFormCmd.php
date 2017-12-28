<?php
namespace Wasm\FormBundle\Cmd;

class ValidateFormCmd
{
    private $requestTransformer;
    private $stateTransformer;
    private $fieldsReader;
    private $validator;

    public function __construct(
        $requestTransformer,
        $stateTransformer,
        $fieldsReader,
        $validator
    ) {
        $this->requestTransformer = $requestTransformer;
        $this->stateTransformer = $stateTransformer;
        $this->fieldsReader = $fieldsReader;
        $this->validator = $validator;
    }

    public function exec($forms = array())
    {
        $formsData = $this->requestTransformer->transformToFormsData($forms);
        $this->fieldsReader->findFieldsToStore($formsData);
        $this->stateTransformer->transformStateToForms($formsData);
        $this->validator->validate($formsData);
    }
}
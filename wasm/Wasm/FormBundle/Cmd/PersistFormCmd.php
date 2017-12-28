<?php
namespace Wasm\FormBundle\Cmd;

class PersistFormCmd
{
    private $requestTransformer;
    private $stateTransformer;
    private $fieldsReader;
    private $validator;
    private $store;

    public function __construct(
        $requestTransformer,
        $stateTransformer,
        $fieldsReader,
        $validator,
        $store
    ) {
        $this->requestTransformer = $requestTransformer;
        $this->stateTransformer = $stateTransformer;
        $this->fieldsReader = $fieldsReader;
        $this->validator = $validator;
        $this->store = $store;
    }

    public function exec($forms = array(), $storeId, $storeData)
    {
        // $statusCode = !$entity->hasId() ? 201 : 204; (Or 204->200 if entity in JSON)

        $formsData = $this->requestTransformer->transformToFormsData($forms);
        $this->fieldsReader->findFieldsToStore($formsData);
        $this->stateTransformer->transformStateToForms($formsData);
        $this->validator->validate($formsData);
        $this->store->persistForms($formsData, $storeId, $storeData);

        //$entity = $this->getRootEntity($form);
        // $state = $this->filterValidState(
        //     $state,
        //     $this->formDefinitionParser->getFormDefinition($form)
        // );
    }
}
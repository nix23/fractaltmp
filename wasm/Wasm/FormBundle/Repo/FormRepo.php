<?php
namespace Wasm\FormBundle\Repo;

class FormRepo
{
    private $requestTransformer;
    private $fieldsReader;
    private $fieldDataResolver;
    private $stateFactory;

    public function __construct(
        $requestTransformer,
        $fieldsReader,
        $fieldDataResolver,
        $stateFactory
    ) {
        $this->requestTransformer = $requestTransformer;
        $this->fieldsReader = $fieldsReader;
        $this->fieldDataResolver = $fieldDataResolver;
        $this->stateFactory = $stateFactory;
    }

    public function getFormFieldsWithStates($formIds = array())
    {
        $formsData = $this->requestTransformer
            ->transformToFormsData($formIds);

        return $this->stateFactory->createFormInitialStates(
            $this->fieldsReader->getFieldsByFormsData($formsData),
            $formsData
        );
    }

    public function getFieldsData($fields)
    {
        return $this->fieldDataResolver
            ->getCollectionData($fields);
    }
}
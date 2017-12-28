<?php
namespace Wasm\FormBundle\Cmd;

class SubmitFormCmd
{
    const DEFAULT_STORE_ID = "FormCustomStore";

    private $persistForm;
    private $storeData;

    public function __construct($persistForm, $storeData) {
        $this->persistForm = $persistForm;
        $this->storeData = $storeData;
    }

    public function exec($forms = array(), $storeId = self::DEFAULT_STORE_ID)
    {
        $this
            ->persistForm
            ->exec($forms, $storeId, $this->storeData);
    }
}
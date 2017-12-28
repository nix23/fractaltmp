<?php
namespace Wasm\FormBundle\Validator;

class CollectionValidator
{
    private $formData = null;

    public function setFormData($formData)
    {
        $this->formData = $formData;
    }

    public function getFormData()
    {
        $formData = $this->formData;
        $this->formData = null;

        return $formData;
    }

    public function hasFormData()
    {
        return $this->formData != null;
    }
}
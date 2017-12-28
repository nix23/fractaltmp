<?php
namespace Wasm\FormBundle\Factory;

class FormFactory
{
    public function createForm($formData)
    {
        $formData->form = new $formData->formClass();
        return $formData->form;
    }

    public function createForms($formData)
    {
        $forms = array();
        $formData->form = array();
        $formData->entity = array();

        foreach($formData->state as $stateEntry) {
            $form = new $formData->formClass();

            $formData->form[] = $form;
            $forms[] = $form;
        }

        return $forms;
    }
}
<?php
// @Deprecated -> User services instead!
// namespace Ntech\ApiFormBundle\Controller\Boot;

// use Ntech\ApiBundle\Controller\Boot\ApiController;
// use Symfony\Component\Form\FormInterface; // @todo -> Rm

// abstract class FormApiController extends ApiController
// {
//     protected function getFormDefinitionsByIds($formIds)
//     {
//         return $this->get("ntech.form.definitionReader")->getFormDefinitions(
//             $formIds
//         );
//     }

//     protected function processForm($form, $state)
//     {
//         return $this->get("ntech.form.command.persistForm")->processForm(
//             $form, $state
//         );
//     }

//     // @todo -> Should it be here??? Now all form processing is incapsulated
//     // inside FormProcessor service;
//     protected function throwFormValidationException(FormInterface $form)
//     {
//         return $this->get("ntech.apiResponseFactory")->throwFormValidationException(
//             $form
//         );
//     }
// }
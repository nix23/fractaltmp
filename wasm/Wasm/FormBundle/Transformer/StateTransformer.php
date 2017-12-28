<?php
namespace Wasm\FormBundle\Transformer;

use Wasm\UtilBundle\Util\Str;

class StateTransformer
{
    private $formFactory;
    private $em;

    private $ensureCanTransform = true;

    public function __construct($formFactory, $em)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
    }

    public function disableEnsureCanTransform()
    {
        $this->ensureCanTransform = false;
    }

    public function transformStateToForms($formsData = array())
    {
        foreach($formsData as $formData) {
            if(!$formData->manyStates) {
                $this->formFactory->createForm($formData);
                $this->mapStateToForm(
                    $formData, $formData->form, $formData->state
                );
            }
            else {
                $this->formFactory->createForms($formData);
                foreach($formData->form as $key => $form) {
                    $this->mapStateToForm(
                        $formData, $form, $formData->state[$key]
                    );
                }
            }
        }
    }

    private function mapStateToForm($formData, $form, $state)
    {
        foreach($formData->storeFields as $fieldName) {
            if(property_exists($form, $fieldName) &&
               array_key_exists($fieldName, $state))
                $form->$fieldName = $state[$fieldName];
        }
    }

    public function transformFormToEntity($form, $formData, $entity)
    {
        $hasSetState = method_exists($form, "setState");
        if($hasSetState) 
            $form->setState(
                $this->em, $entity
            );

        $hasSetEntityState = method_exists($form, "setEntityState");
        if($hasSetEntityState) {
            $form->setEntityState(
                $form, $entity, $formData, $this->em
            );
            return;
        }

        foreach($formData->storeFields as $fieldName) {
            $setter = "set" . Str::ucfirst($fieldName);
            $formSetter = $setter . "State";

            if(!in_array($fieldName, $formData->stateFields))
                continue;

            if(in_array($fieldName, $formData->virtualFields))
                continue;

            if(!property_exists($form, $fieldName))
                $this->throwFieldToEntityTransformationException(
                    $formData, $fieldName, "Reason: no prop in form."
                );

            if(method_exists($form, $formSetter)) {
                $value = $form->$formSetter(
                    $this->em, $entity
                );

                if(method_exists($entity, $setter)) {
                    $entity->$setter($value);
                    $this->trySetOppositeRelationSide(
                        $entity, $value, $setter
                    );
                }
            }
            else {
                if(!$hasSetState && !method_exists($entity, $setter))
                    $this->throwFieldToEntityTransformationException(
                        $formData, $fieldName, "Reason: no setter in entity."
                    );

                if(method_exists($entity, $setter))
                    $entity->$setter($form->$fieldName);
            }
        }
    }

    // Example: on $appSection->setApp($app)
    //          try add opposite relation: 
    //              -> $app->addAppSection($appSection)
    //          $appSection = $entity,
    //          $app = $value,
    //          $setter = setApp
    // @todo -> try also setEname? (1:1) && M:M
    private function trySetOppositeRelationSide($entity, $value, $setter)
    {
        if(!is_object($value))
            return;

        $parts = explode("\\", get_class($entity));
        $entityClass = $parts[count($parts) - 1];
        $addFn = "add" . Str::ucfirst($entityClass);

        if(!method_exists($value, $addFn))
            return;

        $value->$addFn($entity);
    }

    private function throwFieldToEntityTransformationException(
        $formData, 
        $fieldName,
        $msgPostfix = ''
    ) {
        if(!$this->ensureCanTransform)
            return;

        $msg = "Can't transform form field to entity: ";
        $msg .= "FormId: '" . $formData->formId . "', ";
        $msg .= "FieldName: '" . $fieldName . "'. ";
        $msg .= $msgPostfix;

        throw new \Exception($msg);
    }
}
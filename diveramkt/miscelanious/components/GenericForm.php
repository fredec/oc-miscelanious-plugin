<?php

    namespace Diveramkt\Miscelanious\Components;

    use Diveramkt\Miscelanious\Classes\MagicForm;

    class GenericForm extends MagicForm {

        public function componentDetails() {
            return [
                'name'        => 'martin.forms::lang.components.generic_form.name',
                'description' => 'martin.forms::lang.components.generic_form.description',
            ];
        }

    }

?>
<?php

namespace Diveramkt\Miscelanious\Components;

use Diveramkt\Miscelanious\Classes\MagicForm;
use Martin\Forms\Classes\SendMail;

class GenericForm extends MagicForm {

    public function componentDetails() {
        return [
            'name'        => 'martin.forms::lang.components.generic_form.name',
            'description' => 'martin.forms::lang.components.generic_form.description',
        ];
    }

    public function onAjaxGenericForm(){
        $recordid=\Session::get('token_'.\Session::token().'_record_id');
        if($recordid && is_numeric($recordid)){
            $record=\Martin\Forms\Models\Record::where('id',$recordid)->first();
            $post=(array)json_decode(\Session::get('token_'.\Session::token()));
            if(isset($post['_token'])) unset($post['_token']);
            if(isset($post['send_secondary'])) unset($post['send_secondary']);
            if($post){
            // SEND NOTIFICATION EMAIL
                if ($this->property('mail_enabled')) {
                    SendMail::sendNotification($this->getProperties(), $post, $record, $record->files);
                }

        // SEND AUTORESPONSE EMAIL
                if ($this->property('mail_resp_enabled')) {
                    SendMail::sendAutoResponse($this->getProperties(), $post, $record);
                }
                \Session::forget('token_'.\Session::token());
            }
        }
    }

}

?>